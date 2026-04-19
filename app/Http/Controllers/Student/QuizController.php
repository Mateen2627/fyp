<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\StudentQuizAttempt;
use App\Models\StudentRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $quizzes = Quiz::with(['course', 'teacher'])
            ->whereNotNull('teacher_id')
            ->where('my_class_id', $record->my_class_id)
            ->latest()
            ->paginate(20);

        $attemptMap = StudentQuizAttempt::where('student_id', $studentId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        return view('pages.student.modules.quizzes.index', compact('quizzes', 'attemptMap'));
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

        $data = $request->validate([
            'answers' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:5120',
        ]);

        $existing = StudentQuizAttempt::where('quiz_id', $quiz->id)->where('student_id', Auth::id())->first();

        $payload = [
            'answers' => $data['answers'] ?? null,
            'submitted_at' => now(),
        ];

        if ($request->hasFile('attachment')) {
            $payload['attachment_path'] = $request->file('attachment')->store('quiz_attempts', 'public');
        } elseif ($existing && $existing->attachment_path) {
            $payload['attachment_path'] = $existing->attachment_path;
        }

        StudentQuizAttempt::updateOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => Auth::id()],
            $payload
        );

        return redirect()->route('student.quizzes.show', $quiz->id)->with('flash_success', 'Quiz submitted successfully.');
    }

    private function assertBelongsToStudentClass(Quiz $quiz): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        abort_unless((int) $quiz->my_class_id === (int) $record->my_class_id, 403);
    }
}
