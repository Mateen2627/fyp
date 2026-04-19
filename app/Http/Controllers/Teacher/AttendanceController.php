<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $classIds = TeacherAssignment::where('teacher_id', Auth::id())->pluck('my_class_id')->filter()->unique();
        $studentIds = StudentRecord::whereIn('my_class_id', $classIds)->pluck('user_id');

        $records = StudentAttendance::whereIn('user_id', $studentIds)->latest('attendance_date')->paginate(50);

        return view('pages.teacher.modules.attendance.index', compact('records'));
    }
}
