<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\StudentReport;
use Illuminate\Http\Request;

class StudentReportController extends Controller
{
    public function __construct(){ $this->middleware('teamSAT'); }
    public function index(){ $records = StudentReport::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'student_reports', 'title' => 'Student Reports']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'student_reports', 'title' => 'Student Report']); }
    public function store(Request $request){ $data = $request->validate(['student_record_id' => 'required|integer','title' => 'required|string|max:200','report' => 'required|string','reported_at' => 'nullable|date']); StudentReport::create($data); return redirect()->route('student_reports.index')->with('flash_success', 'Student report created successfully.'); }
    public function show(StudentReport $student_report){ return view('pages.support_team.modules.show', ['record' => $student_report, 'module' => 'student_reports', 'title' => 'Student Report']); }
    public function edit(StudentReport $student_report){ return view('pages.support_team.modules.edit', ['record' => $student_report, 'module' => 'student_reports', 'title' => 'Student Report']); }
    public function update(Request $request, StudentReport $student_report){ $data = $request->validate(['student_record_id' => 'required|integer','title' => 'required|string|max:200','report' => 'required|string','reported_at' => 'nullable|date']); $student_report->update($data); return redirect()->route('student_reports.index')->with('flash_success', 'Student report updated successfully.'); }
    public function destroy(StudentReport $student_report){ $student_report->delete(); return redirect()->route('student_reports.index')->with('flash_success', 'Student report deleted successfully.'); }
}
