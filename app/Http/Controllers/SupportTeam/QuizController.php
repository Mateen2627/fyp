<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(){ $this->middleware('teamSAT'); }
    public function index(){ $records = Quiz::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'quizzes', 'title' => 'Quizzes']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'quizzes', 'title' => 'Quiz']); }
    public function store(Request $request){ $data = $request->validate(['title' => 'required|string|max:200','course_id' => 'nullable|integer','description' => 'nullable|string','total_marks' => 'nullable|integer|min:0','scheduled_at' => 'nullable|date']); Quiz::create($data); return redirect()->route('quizzes.index')->with('flash_success', 'Quiz created successfully.'); }
    public function show(Quiz $quiz){ return view('pages.support_team.modules.show', ['record' => $quiz, 'module' => 'quizzes', 'title' => 'Quiz']); }
    public function edit(Quiz $quiz){ return view('pages.support_team.modules.edit', ['record' => $quiz, 'module' => 'quizzes', 'title' => 'Quiz']); }
    public function update(Request $request, Quiz $quiz){ $data = $request->validate(['title' => 'required|string|max:200','course_id' => 'nullable|integer','description' => 'nullable|string','total_marks' => 'nullable|integer|min:0','scheduled_at' => 'nullable|date']); $quiz->update($data); return redirect()->route('quizzes.index')->with('flash_success', 'Quiz updated successfully.'); }
    public function destroy(Quiz $quiz){ $quiz->delete(); return redirect()->route('quizzes.index')->with('flash_success', 'Quiz deleted successfully.'); }
}
