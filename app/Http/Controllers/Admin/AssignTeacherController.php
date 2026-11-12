<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\DepartmentStaff;
use App\Models\MyClass;
use App\Models\Section;
use App\Models\Subject;
use App\Models\TeacherAssignment;
use App\Services\LmsAccess;
use App\Services\TimetableService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AssignTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('departmentRole:hod');
    }

    public function index()
    {
        $records = TeacherAssignment::with(['course', 'teacher', 'my_class', 'section'])
            ->whereHas('my_class', function ($q) { $q->whereIn('class_type_id', LmsAccess::departmentIds('hod')); })
            ->latest()->paginate(30);
        return view('pages.hod.assignments.index', compact('records'));
    }

    public function create()
    {
        return view('pages.hod.assignments.form', $this->formData());
    }

    public function store(Request $request)
    {
        $this->save($request);
        return redirect()->route('hod.assign-teachers.index')->with('flash_success', 'Teaching assignment saved.');
    }

    public function edit(TeacherAssignment $assign_teacher)
    {
        LmsAccess::assertClass($assign_teacher->my_class_id, 'hod');
        return view('pages.hod.assignments.form', $this->formData($assign_teacher));
    }

    public function update(Request $request, TeacherAssignment $assign_teacher)
    {
        LmsAccess::assertClass($assign_teacher->my_class_id, 'hod');
        $this->save($request, $assign_teacher);
        return redirect()->route('hod.assign-teachers.index')->with('flash_success', 'Teaching assignment updated.');
    }

    public function destroy(TeacherAssignment $assign_teacher)
    {
        LmsAccess::assertClass($assign_teacher->my_class_id, 'hod');
        $assign_teacher->delete();
        return back()->with('flash_success', 'Teaching assignment revoked. Existing submissions and marks are retained.');
    }

    private function save(Request $request, TeacherAssignment $record = null)
    {
        $data = $request->validate([
            'course_id' => 'required|integer|exists:courses,id',
            'my_class_id' => 'required|integer|exists:my_classes,id',
            'section_id' => ['nullable', 'integer', Rule::exists('sections', 'id')->where('my_class_id', $request->my_class_id)],
            'teacher_id' => 'required|integer|exists:users,id',
            'notes' => 'nullable|string|max:2000',
        ]);
        $class = LmsAccess::assertClass($data['my_class_id'], 'hod');
        $course = Course::findOrFail($data['course_id']);
        abort_unless((int) $course->my_class_id === (int) $class->id, 422, 'The subject must belong to the selected semester.');
        $teacher = User::where('user_type', 'teacher')->findOrFail($data['teacher_id']);
        abort_unless(DepartmentStaff::where('department_id', $class->class_type_id)->where('user_id', $teacher->id)->exists(), 422, 'The teacher must be a member of this department.');
        $data['section_id'] = $data['section_id'] ?? null;
        $data['assigned_by'] = Auth::id();

        DB::transaction(function () use ($data, $course, $record) {
            Course::whereKey($course->id)->lockForUpdate()->first();
            if ($record && ((int) $record->course_id !== (int) $data['course_id'] || (int) $record->my_class_id !== (int) $data['my_class_id'] || (int) $record->section_id !== (int) $data['section_id'])) {
                foreach ([Assignment::class, Quiz::class] as $activityModel) {
                    if ($activityModel::where('course_id', $record->course_id)->where('my_class_id', $record->my_class_id)->where('section_id', $record->section_id)->exists()) {
                        throw ValidationException::withMessages(['section_id' => 'This allocation has existing student work. Keep its subject, semester and section; change the teacher to transfer responsibility.']);
                    }
                }
            }
            $conflicts = TeacherAssignment::where('course_id', $course->id)->where('my_class_id', $data['my_class_id']);
            if ($record) { $conflicts->where('id', '<>', $record->id); }
            if ($data['section_id']) {
                $conflicts->where(function ($q) use ($data) { $q->whereNull('section_id')->orWhere('section_id', $data['section_id']); });
            }
            if ($conflicts->exists()) {
                throw ValidationException::withMessages(['section_id' => 'This subject already has a teacher for that section. Edit or revoke that assignment first.']);
            }
            $subject = $course->subject;
            if (!$subject) {
                $subject = Subject::updateOrCreate(['my_class_id' => $data['my_class_id'], 'name' => $course->title], [
                    'slug' => $course->code ?: 'COURSE-'.$course->id,
                    'teacher_id' => $data['teacher_id'],
                ]);
                $course->update(['subject_id' => $subject->id]);
            } else {
                $subject->update(['teacher_id' => $data['teacher_id']]);
            }
            if ($record) { $record->update($data); } else { TeacherAssignment::create($data); }
            $course->update(['teaching_initialized' => true]);
            // Keep existing work gradeable when a subject teacher is replaced.
            // Responsibility changes; submissions, marks and feedback are retained.
            foreach ([Assignment::class, Quiz::class] as $activityModel) {
                foreach ($activityModel::where('course_id', $course->id)->where('my_class_id', $data['my_class_id'])->where('section_id', $data['section_id'])->get() as $activity) {
                    $hasOwner = TeacherAssignment::where('course_id', $course->id)->where('my_class_id', $data['my_class_id'])->where('teacher_id', $activity->teacher_id)->where(function ($q) use ($data) {
                        $q->whereNull('section_id'); if ($data['section_id']) { $q->orWhere('section_id', $data['section_id']); }
                    })->exists();
                    if (!$hasOwner) { $activity->update(['teacher_id' => $data['teacher_id']]); }
                }
            }
            // A teacher reassignment must not introduce clashes in published lessons.
            app(TimetableService::class)->validateSubjectLessons($subject->id);
        });
    }

    private function formData($record = null)
    {
        $departmentIds = LmsAccess::departmentIds('hod');
        $classes = MyClass::with('section')->whereIn('class_type_id', $departmentIds)->orderBy('name')->get();
        return [
            'record' => $record,
            'classes' => $classes,
            'courses' => Course::whereIn('my_class_id', $classes->pluck('id'))->orderBy('title')->get(),
            'teachers' => User::where('user_type', 'teacher')->whereHas('departmentRoles', function ($q) use ($departmentIds) { $q->whereIn('department_id', $departmentIds); })->with('departmentRoles')->orderBy('name')->get(),
        ];
    }
}
