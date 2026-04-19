<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function __construct(){ $this->middleware('teamSAT'); }
    public function index(){ $records = Assignment::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'assignments', 'title' => 'Assignments']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'assignments', 'title' => 'Assignment']); }
    public function store(Request $request){ $data = $request->validate(['title' => 'required|string|max:200','course_id' => 'nullable|integer','description' => 'nullable|string','due_date' => 'nullable|date']); Assignment::create($data); return redirect()->route('assignments.index')->with('flash_success', 'Assignment created successfully.'); }
    public function show(Assignment $assignment){ return view('pages.support_team.modules.show', ['record' => $assignment, 'module' => 'assignments', 'title' => 'Assignment']); }
    public function edit(Assignment $assignment){ return view('pages.support_team.modules.edit', ['record' => $assignment, 'module' => 'assignments', 'title' => 'Assignment']); }
    public function update(Request $request, Assignment $assignment){ $data = $request->validate(['title' => 'required|string|max:200','course_id' => 'nullable|integer','description' => 'nullable|string','due_date' => 'nullable|date']); $assignment->update($data); return redirect()->route('assignments.index')->with('flash_success', 'Assignment updated successfully.'); }
    public function destroy(Assignment $assignment){ $assignment->delete(); return redirect()->route('assignments.index')->with('flash_success', 'Assignment deleted successfully.'); }
}
