<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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


    public function viewMaterial(Assignment $assignment)
    {
        $this->assertBelongsToStudentClass($assignment);
        abort_unless($assignment->attachment_path && Storage::disk('public')->exists($assignment->attachment_path), 404);

        return $this->inlinePdf($assignment->attachment_path);
    }

    public function viewSubmission(Assignment $assignment)
    {
        $this->assertBelongsToStudentClass($assignment);
        $submission = StudentAssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();
        abort_unless($submission->attachment_path && Storage::disk('public')->exists($submission->attachment_path), 404);

        return $this->inlinePdf($submission->attachment_path);
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

        $existing = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('student.assignments.show', $assignment->id)->with('flash_danger', 'You have already submitted this assignment. Resubmission is not allowed.');
        }

        $data = $request->validate([
            'submission_text' => 'nullable|string',
            'attachment' => 'required|file|mimes:pdf|max:5120',
        ]);

        StudentAssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'submission_text' => $data['submission_text'] ?? null,
            'attachment_path' => $request->file('attachment')->store('student_submissions', 'public'),
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.assignments.show', $assignment->id)->with('flash_success', 'Assignment submitted successfully.');
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('public')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    private function assertBelongsToStudentClass(Assignment $assignment): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        abort_unless((int) $assignment->my_class_id === (int) $record->my_class_id, 403);
    }
}
