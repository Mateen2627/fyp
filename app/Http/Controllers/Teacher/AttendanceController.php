<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceCheckIn;
use App\Models\StudentAttendance;
use App\Models\TeacherAssignment;
use App\Services\LmsAccess;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct() { $this->middleware('teacher'); }

    public function index(Request $request)
    {
        $request->validate(['course_id' => 'nullable|integer|exists:courses,id', 'from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
        $pairs = LmsAccess::teachingQuery()->with(['course', 'my_class', 'section'])->get();
        if ($request->filled('course_id')) { abort_unless($pairs->contains('course_id', (int) $request->course_id), 403); }
        $query = StudentAttendance::with(['user', 'course'])->where(function ($q) use ($pairs) {
            $q->whereRaw('1 = 0');
            foreach ($pairs as $pair) {
                $q->orWhere(function ($entry) use ($pair) { $entry->where('course_id', $pair->course_id)->where('my_class_id', $pair->my_class_id); if ($pair->section_id) { $entry->where('section_id', $pair->section_id); } });
            }
        });
        if ($request->filled('course_id')) { $query->where('course_id', $request->course_id); }
        if ($request->filled('from')) { $query->whereDate('attendance_date', '>=', $request->from); }
        if ($request->filled('to')) { $query->whereDate('attendance_date', '<=', $request->to); }
        $total = (clone $query)->count(); $present = (clone $query)->where('status', 'present')->count();
        $records = $query->latest('attendance_date')->paginate(50)->appends($request->query());
        return view('pages.teacher.modules.attendance.index', compact('pairs', 'records', 'total', 'present'));
    }

    // Self check-in and the server verifier own attendance writes.
    public function store() { abort(403); }
    public function storeSingle() { abort(403); }
    public function storeFacial() { abort(403, 'Facial attendance requires the server verification service.'); }
}
