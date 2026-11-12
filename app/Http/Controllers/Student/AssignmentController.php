<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\LmsAccess;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

        $assignments = LmsAccess::scopeStudentActivities(Assignment::with(['course', 'teacher']), $record)
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
        abort_unless($assignment->attachment_path && Storage::disk('local')->exists($assignment->attachment_path), 404);

        return $this->inlinePdf($assignment->attachment_path);
    }

    public function viewSubmission(Assignment $assignment)
    {
        $this->assertBelongsToStudentClass($assignment);
        $submission = StudentAssignmentSubmission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();
        abort_unless($submission->attachment_path && Storage::disk('local')->exists($submission->attachment_path), 404);

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

        if ($assignment->due_date && $assignment->due_date->copy()->endOfDay()->isPast()) { throw ValidationException::withMessages(['submission' => 'The assignment deadline has passed.']); }
        $existing = StudentAssignmentSubmission::where('assignment_id', $assignment->id)->where('student_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('student.assignments.show', $assignment->id)->with('flash_danger', 'You have already submitted this assignment. Resubmission is not allowed.');
        }

        $data = $request->validate([
            'submission_text' => 'required_without:attachment|nullable|string|max:30000',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        StudentAssignmentSubmission::create([
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'submission_text' => $data['submission_text'] ?? null,
            'attachment_path' => $request->hasFile('attachment') ? $request->file('attachment')->store('student_submissions', 'local') : null,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.assignments.show', $assignment->id)->with('flash_success', 'Assignment submitted successfully.');
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    private function assertBelongsToStudentClass(Assignment $assignment): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        LmsAccess::assertStudentActivity($assignment);
    }
}
