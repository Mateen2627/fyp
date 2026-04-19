<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        $studentId = Auth::id();

        $assignments = Assignment::with(['course', 'teacher'])
            ->whereNotNull('teacher_id')
            ->where('my_class_id', $record->my_class_id)
            ->latest()
            ->paginate(20);

        $submissionMap = StudentAssignmentSubmission::where('student_id', $studentId)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        return view('pages.student.modules.assignments.index', compact('assignments', 'submissionMap'));
    }

    public function show(Assignment $assignment)
    {
        $this->assertBelongsToStudentClass($assignment);

        $submission = StudentAssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->first();

        return view('pages.student.modules.assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        $this->assertBelongsToStudentClass($assignment);

        $data = $request->validate([
            'submission_text' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:5120',
        ]);

        $payload = [
            'submission_text' => $data['submission_text'] ?? null,
            'submitted_at' => now(),
        ];

        $existing = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', Auth::id())->first();

        if ($request->hasFile('attachment')) {
            $payload['attachment_path'] = $request->file('attachment')->store('student_submissions', 'public');
        } elseif ($existing && $existing->attachment_path) {
            $payload['attachment_path'] = $existing->attachment_path;
        }

        StudentAssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => Auth::id()],
            $payload
        );

        return redirect()->route('student.assignments.show', $assignment->id)->with('flash_success', 'Assignment submitted successfully.');
    }

    private function assertBelongsToStudentClass(Assignment $assignment): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        abort_unless((int) $assignment->my_class_id === (int) $record->my_class_id, 403);
    }
}
