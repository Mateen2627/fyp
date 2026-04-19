<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\TeacherAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class AssignTeacherController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.assignments.index', [
            'records' => TeacherAssignment::with(['course', 'teacher', 'my_class'])->latest()->paginate(20),
            'class_assignment_enabled' => $this->classAssignmentEnabled(),
        ]);
    }

    public function create()
    {
        return view('pages.admin.modules.assignments.create', [
            'courses' => Course::orderBy('title')->get(),
            'classes' => MyClass::orderBy('name')->get(),
            'teachers' => $this->teachers(),
            'class_assignment_enabled' => $this->classAssignmentEnabled(),
        ]);
    }

    public function store(Request $request)
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ];

        if ($this->classAssignmentEnabled()) {
            $rules['my_class_id'] = 'nullable|exists:my_classes,id';
        }

        $data = $request->validate($rules);

        $teacher = User::findOrFail($data['teacher_id']);
        if (strtolower(trim((string) $teacher->user_type)) !== 'teacher') {
            return back()->withInput()->with('flash_danger', 'Selected user is not a teacher.');
        }

        $data['assigned_by'] = Auth::id();

        $lookup = [
            'course_id' => $data['course_id'],
            'teacher_id' => $data['teacher_id'],
        ];

        if ($this->classAssignmentEnabled()) {
            $lookup['my_class_id'] = $data['my_class_id'] ?? null;
        }

        TeacherAssignment::updateOrCreate($lookup, [
            'notes' => $data['notes'] ?? null,
            'assigned_by' => $data['assigned_by'],
            'my_class_id' => $this->classAssignmentEnabled() ? ($data['my_class_id'] ?? null) : null,
        ]);

        return redirect()->route('admin.assign-teachers.index')->with('flash_success', 'Teacher assigned to course successfully.');
    }

    public function edit(TeacherAssignment $assign_teacher)
    {
        return view('pages.admin.modules.assignments.edit', [
            'record' => $assign_teacher,
            'courses' => Course::orderBy('title')->get(),
            'classes' => MyClass::orderBy('name')->get(),
            'teachers' => $this->teachers(),
            'class_assignment_enabled' => $this->classAssignmentEnabled(),
        ]);
    }

    public function update(Request $request, TeacherAssignment $assign_teacher)
    {
        $rules = [
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ];

        if ($this->classAssignmentEnabled()) {
            $rules['my_class_id'] = 'nullable|exists:my_classes,id';
        }

        $data = $request->validate($rules);

        $teacher = User::findOrFail($data['teacher_id']);
        if (strtolower(trim((string) $teacher->user_type)) !== 'teacher') {
            return back()->withInput()->with('flash_danger', 'Selected user is not a teacher.');
        }

        $payload = [
            'course_id' => $data['course_id'],
            'teacher_id' => $data['teacher_id'],
            'notes' => $data['notes'] ?? null,
            'assigned_by' => Auth::id(),
        ];

        if ($this->classAssignmentEnabled()) {
            $payload['my_class_id'] = $data['my_class_id'] ?? null;
        }

        $assign_teacher->update($payload);

        return redirect()->route('admin.assign-teachers.index')->with('flash_success', 'Teacher assignment updated successfully.');
    }

    public function destroy(TeacherAssignment $assign_teacher)
    {
        $assign_teacher->delete();

        return redirect()->route('admin.assign-teachers.index')->with('flash_success', 'Teacher assignment deleted successfully.');
    }

    protected function teachers()
    {
        return User::query()
            ->where(function ($q) {
                $q->where('user_type', 'teacher')
                    ->orWhere('user_type', 'Teacher')
                    ->orWhereRaw('LOWER(TRIM(user_type)) = ?', ['teacher']);
            })
            ->orderBy('name')
            ->get();
    }

    protected function classAssignmentEnabled()
    {
        return Schema::hasColumn('teacher_assignments', 'my_class_id');
    }
}
