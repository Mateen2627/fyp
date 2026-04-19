<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\StudentQuizAttempt;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $quizzes = Quiz::with('course')->where('teacher_id', Auth::id())->latest()->paginate(20);

        return view('pages.teacher.modules.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = TeacherAssignment::with('course')->where('teacher_id', Auth::id())->get()->pluck('course')->filter()->unique('id');

        return view('pages.teacher.modules.quizzes.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:0',
            'scheduled_at' => 'nullable|date',
        ]);
        $data['teacher_id'] = Auth::id();

        Quiz::create($data);

        return redirect()->route('teacher.quizzes.index')->with('flash_success', 'Quiz created.');
    }

    public function show(Quiz $quiz)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);
        $attempts = StudentQuizAttempt::where('quiz_id', $quiz->id)->latest()->get();

        return view('pages.teacher.modules.quizzes.show', compact('quiz', 'attempts'));
    }

    public function mark(Request $request, Quiz $quiz, StudentQuizAttempt $attempt)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);

        $data = $request->validate([
            'marks' => 'nullable|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
        ]);
        $attempt->update($data);

        return back()->with('flash_success', 'Quiz attempt evaluated.');
    }
}
