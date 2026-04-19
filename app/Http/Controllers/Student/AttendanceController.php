<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $records = StudentAttendance::where('user_id', Auth::id())->latest('attendance_date')->paginate(30);

        return view('pages.student.modules.attendance.index', compact('records'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string',
        ]);

        StudentAttendance::updateOrCreate(
            ['user_id' => Auth::id(), 'attendance_date' => $data['attendance_date']],
            ['status' => $data['status'], 'remarks' => $data['remarks'] ?? null]
        );

        return redirect()->route('student.attendance.index')->with('flash_success', 'Attendance marked successfully.');
    }
}
