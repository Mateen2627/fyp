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

        $quizzes = Quiz::with('course')
            ->whereHas('course', function ($q) use ($record) {
                $q->where('my_class_id', $record->my_class_id);
            })
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
            'answers' => 'required|string',
        ]);

        StudentQuizAttempt::updateOrCreate(
            ['quiz_id' => $quiz->id, 'student_id' => Auth::id()],
            ['answers' => $data['answers'], 'submitted_at' => now()]
        );

        return redirect()->route('student.quizzes.show', $quiz->id)->with('flash_success', 'Quiz submitted successfully.');
    }

    private function assertBelongsToStudentClass(Quiz $quiz): void
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();
        abort_unless(optional($quiz->course)->my_class_id == $record->my_class_id, 403);
    }
}
