<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Services\LmsAccess;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Models\Quiz;
use App\Models\StudentQuizAttempt;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        $studentId = Auth::id();

        $quizzes = LmsAccess::scopeStudentActivities(Quiz::with(['course', 'teacher']), $record)
            ->latest()
            ->paginate(20);

        $attemptMap = StudentQuizAttempt::where('student_id', $studentId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        return view('pages.student.modules.quizzes.index', compact('quizzes', 'attemptMap'));
    }


    public function viewMaterial(Quiz $quiz)
    {
        $this->assertBelongsToStudentClass($quiz);
        abort_unless($quiz->attachment_path && Storage::disk('local')->exists($quiz->attachment_path), 404);

        return $this->inlinePdf($quiz->attachment_path);
    }

    public function viewAttempt(Quiz $quiz)
    {
        $this->assertBelongsToStudentClass($quiz);
        $attempt = StudentQuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->firstOrFail();
        abort_unless($attempt->attachment_path && Storage::disk('local')->exists($attempt->attachment_path), 404);

        return $this->inlinePdf($attempt->attachment_path);
    }

    public function show(Quiz $quiz)
    {
        $this->assertBelongsToStudentClass($quiz);

        $attempt = StudentQuizAttempt::where('quiz_id', $quiz->id)
            ->where('student_id', Auth::id())
            ->first();

        return view('pages.student.modules.quizzes.show', compact('quiz', 'attempt'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        $this->assertBelongsToStudentClass($quiz);

        if ($quiz->scheduled_at && $quiz->scheduled_at->isFuture()) { throw ValidationException::withMessages(['submission' => 'This quiz is not open yet.']); }
        $existing = StudentQuizAttempt::where('quiz_id', $quiz->id)->where('student_id', Auth::id())->first();
        if ($existing) {
            return redirect()->route('student.quizzes.show', $quiz->id)->with('flash_danger', 'You have already submitted this quiz. Resubmission is not allowed.');
        }

        $data = $request->validate([
            'answers' => 'required_without:attachment|nullable|string|max:30000',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
        ]);

        StudentQuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => Auth::id(),
            'answers' => $data['answers'] ?? null,
            'attachment_path' => $request->hasFile('attachment') ? $request->file('attachment')->store('quiz_attempts', 'local') : null,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.quizzes.show', $quiz->id)->with('flash_success', 'Quiz submitted successfully.');
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    private function assertBelongsToStudentClass(Quiz $quiz): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        LmsAccess::assertStudentActivity($quiz);
    }
}
