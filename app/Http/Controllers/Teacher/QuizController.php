<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\StudentQuizAttempt;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $quizzes = Quiz::with(['course', 'my_class'])->where('teacher_id', Auth::id())->latest()->paginate(20);

        return view('pages.teacher.modules.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $pairs = TeacherAssignment::with(['course', 'my_class'])->where('teacher_id', Auth::id())->get();

        return view('pages.teacher.modules.quizzes.create', compact('pairs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'my_class_id' => 'required|integer',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:0',
            'scheduled_at' => 'nullable|date',
        ]);

        $isAllowedPair = TeacherAssignment::where('teacher_id', Auth::id())
            ->where('course_id', $data['course_id'])
            ->where('my_class_id', $data['my_class_id'])
            ->exists();
        abort_unless($isAllowedPair, 403);

        $data['teacher_id'] = Auth::id();

        Quiz::create($data);

        return redirect()->route('teacher.quizzes.index')->with('flash_success', 'Quiz created.');
    }

    public function edit(Quiz $quiz)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);

        return view('pages.teacher.modules.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'total_marks' => 'nullable|integer|min:0',
            'scheduled_at' => 'nullable|date',
        ]);

        $quiz->update($data);

        return redirect()->route('teacher.quizzes.index')->with('flash_success', 'Quiz updated.');
    }

    public function show(Quiz $quiz)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);
        $attempts = StudentQuizAttempt::where('quiz_id', $quiz->id)->latest()->get();

        return view('pages.teacher.modules.quizzes.show', compact('quiz', 'attempts'));
    }

    public function downloadAttempt(Quiz $quiz, StudentQuizAttempt $attempt)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);
        abort_unless($attempt->attachment_path && Storage::disk('public')->exists($attempt->attachment_path), 404);

        return Storage::disk('public')->download($attempt->attachment_path);
    }

    public function mark(Request $request, Quiz $quiz, StudentQuizAttempt $attempt)
    {
        abort_unless((int) $quiz->teacher_id === (int) Auth::id(), 403);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);

        if (!is_null($attempt->marks)) {
            return back()->with('flash_danger', 'Marks already assigned and cannot be overridden.');
        }

        $data = $request->validate([
            'marks' => 'required|numeric|min:0|max:100',
            'teacher_feedback' => 'nullable|string',
        ]);
        $attempt->update($data);

        return back()->with('flash_success', 'Quiz attempt evaluated.');
    }
}
