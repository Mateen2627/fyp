<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(){ $this->middleware('teamSAT'); }
    public function index(){ $records = Attendance::latest()->paginate(20); return view('pages.support_team.modules.index', ['records' => $records, 'module' => 'attendance', 'title' => 'Attendance']); }
    public function create(){ return view('pages.support_team.modules.create', ['module' => 'attendance', 'title' => 'Attendance']); }
    public function store(Request $request){ $data = $request->validate(['student_record_id' => 'required|integer','attendance_date' => 'required|date','status' => 'required|in:present,absent,late,excused','remarks' => 'nullable|string']); Attendance::create($data); return redirect()->route('attendance.index')->with('flash_success', 'Attendance recorded successfully.'); }
    public function show(Attendance $attendance){ return view('pages.support_team.modules.show', ['record' => $attendance, 'module' => 'attendance', 'title' => 'Attendance']); }
    public function edit(Attendance $attendance){ return view('pages.support_team.modules.edit', ['record' => $attendance, 'module' => 'attendance', 'title' => 'Attendance']); }
    public function update(Request $request, Attendance $attendance){ $data = $request->validate(['student_record_id' => 'required|integer','attendance_date' => 'required|date','status' => 'required|in:present,absent,late,excused','remarks' => 'nullable|string']); $attendance->update($data); return redirect()->route('attendance.index')->with('flash_success', 'Attendance updated successfully.'); }
    public function destroy(Attendance $attendance){ $attendance->delete(); return redirect()->route('attendance.index')->with('flash_success', 'Attendance deleted successfully.'); }
}
