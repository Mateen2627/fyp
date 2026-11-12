<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\LmsAccess;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $quizzes = LmsAccess::scopeTeacherActivities(Quiz::with(['course', 'my_class']))->latest()->paginate(20);

        return view('pages.teacher.modules.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $pairs = LmsAccess::teachingQuery()->with(['course', 'my_class', 'section'])->get();

        return view('pages.teacher.modules.quizzes.create', compact('pairs'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'course_id' => 'required|integer',
            'my_class_id' => 'required|integer|exists:my_classes,id',
            'section_id' => ['nullable', 'integer', Rule::exists('sections', 'id')->where('my_class_id', $request->my_class_id)],
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'total_marks' => 'required|integer|min:1|max:999',
            'scheduled_at' => 'nullable|date',
        ]);

        $isAllowedPair = TeacherAssignment::where('teacher_id', Auth::id())
            ->where('course_id', $data['course_id'])
            ->where('my_class_id', $data['my_class_id'])
            ->exists();
        abort_unless($isAllowedPair, 403);

        LmsAccess::assertTeaching($data['course_id'], $data['my_class_id'], $data['section_id'] ?? null);
        $data['teacher_id'] = Auth::id();
        $data['attachment_path'] = $request->hasFile('attachment') ? $request->file('attachment')->store('quiz_materials', 'local') : null;

        Quiz::create($data);

        return redirect()->route('teacher.quizzes.index')->with('flash_success', 'Quiz created.');
    }

    public function edit(Quiz $quiz)
    {
        LmsAccess::assertActivityOwner($quiz);

        return view('pages.teacher.modules.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        LmsAccess::assertActivityOwner($quiz);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf|max:5120',
            'total_marks' => 'required|integer|min:1|max:999',
            'scheduled_at' => 'nullable|date',
        ]);

        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('quiz_materials', 'local');
        }

        if (isset($data['total_marks']) && $quiz->attempts()->where('marks', '>', $data['total_marks'])->exists()) {
            throw ValidationException::withMessages(['total_marks' => 'Total marks cannot be lower than an existing awarded mark.']);
        }
        $quiz->update($data);

        return redirect()->route('teacher.quizzes.index')->with('flash_success', 'Quiz updated.');
    }


    public function viewMaterial(Quiz $quiz)
    {
        LmsAccess::assertActivityOwner($quiz);
        abort_unless($quiz->attachment_path && Storage::disk('local')->exists($quiz->attachment_path), 404);

        return $this->inlinePdf($quiz->attachment_path);
    }

    public function downloadMaterial(Quiz $quiz)
    {
        LmsAccess::assertActivityOwner($quiz);
        abort_unless($quiz->attachment_path && Storage::disk('local')->exists($quiz->attachment_path), 404);

        return Storage::disk('local')->download($quiz->attachment_path);
    }

    public function show(Quiz $quiz)
    {
        LmsAccess::assertActivityOwner($quiz);
        $attempts = StudentQuizAttempt::where('quiz_id', $quiz->id)->latest()->get();

        return view('pages.teacher.modules.quizzes.show', compact('quiz', 'attempts'));
    }


    public function viewAttempt(Quiz $quiz, StudentQuizAttempt $attempt)
    {
        LmsAccess::assertActivityOwner($quiz);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);
        abort_unless($attempt->attachment_path && Storage::disk('local')->exists($attempt->attachment_path), 404);

        return $this->inlinePdf($attempt->attachment_path);
    }

    public function downloadAttempt(Quiz $quiz, StudentQuizAttempt $attempt)
    {
        LmsAccess::assertActivityOwner($quiz);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);
        abort_unless($attempt->attachment_path && Storage::disk('local')->exists($attempt->attachment_path), 404);

        return Storage::disk('local')->download($attempt->attachment_path);
    }


    private function inlinePdf(string $path)
    {
        return response()->file(Storage::disk('local')->path($path), [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }

    public function mark(Request $request, Quiz $quiz, StudentQuizAttempt $attempt)
    {
        LmsAccess::assertActivityOwner($quiz);
        abort_unless((int) $attempt->quiz_id === (int) $quiz->id, 404);

        $data = $request->validate([
            'marks' => 'required|numeric|min:0|max:'.($quiz->total_marks ?: 100),
            'teacher_feedback' => 'nullable|string|max:3000',
        ]);
        $attempt->update($data);

        return back()->with('flash_success', 'Quiz attempt evaluated.');
    }
}
