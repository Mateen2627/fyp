<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignmentSubmission;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $assignments = Assignment::with('course')
            ->where('teacher_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('pages.teacher.modules.assignments.index', compact('assignments'));
    }

    public function create()
    {
        $courses = TeacherAssignment::with('course')->where('teacher_id', Auth::id())->get()->pluck('course')->filter()->unique('id');
        return view('pages.teacher.modules.assignments.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);
        $data['teacher_id'] = Auth::id();

        Assignment::create($data);

        return redirect()->route('teacher.assignments.index')->with('flash_success', 'Assignment created.');
    }

    public function show(Assignment $assignment)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        $submissions = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->latest()->get();

        return view('pages.teacher.modules.assignments.show', compact('assignment', 'submissions'));
    }

    public function mark(Request $request, Assignment $assignment, StudentAssignmentSubmission $submission)
    {
        abort_unless((int) $assignment->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $submission->assignment_id === (int) $assignment->id, 404);

        $data = $request->validate([
            'marks' => 'nullable|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
        ]);
        $submission->update($data);

        return back()->with('flash_success', 'Submission evaluated.');
    }
}
