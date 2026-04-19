<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\TeacherAssignment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        ]);
    }

    public function create()
    {
        return view('pages.admin.modules.assignments.create', [
            'courses' => Course::orderBy('title')->get(),
            'classes' => MyClass::orderBy('name')->get(),
            'teachers' => $this->teachers(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $teacher = User::findOrFail($data['teacher_id']);
        if (strtolower(trim((string) $teacher->user_type)) !== 'teacher') {
            return back()->withInput()->with('flash_danger', 'Selected user is not a teacher.');
        }

        $data['assigned_by'] = Auth::id();

        TeacherAssignment::updateOrCreate(
            [
                'course_id' => $data['course_id'],
                'teacher_id' => $data['teacher_id'],
                'my_class_id' => $data['my_class_id'] ?? null,
            ],
            ['notes' => $data['notes'], 'assigned_by' => $data['assigned_by']]
        );

        return redirect()->route('admin.assign-teachers.index')->with('flash_success', 'Teacher assigned to course successfully.');
    }

    public function edit(TeacherAssignment $assign_teacher)
    {
        return view('pages.admin.modules.assignments.edit', [
            'record' => $assign_teacher,
            'courses' => Course::orderBy('title')->get(),
            'classes' => MyClass::orderBy('name')->get(),
            'teachers' => $this->teachers(),
        ]);
    }

    public function update(Request $request, TeacherAssignment $assign_teacher)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'my_class_id' => 'nullable|exists:my_classes,id',
            'teacher_id' => 'required|exists:users,id',
            'notes' => 'nullable|string',
        ]);

        $teacher = User::findOrFail($data['teacher_id']);
        if (strtolower(trim((string) $teacher->user_type)) !== 'teacher') {
            return back()->withInput()->with('flash_danger', 'Selected user is not a teacher.');
        }

        $data['assigned_by'] = Auth::id();
        $assign_teacher->update($data);

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
}
