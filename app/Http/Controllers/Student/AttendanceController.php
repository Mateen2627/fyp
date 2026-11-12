<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Controllers\AttendanceFaceController;
use App\Models\AttendanceCheckIn;
use App\Models\StudentAttendance;
use App\Models\TimeTable;
use App\Helpers\Qs;
use App\Services\FaceAttendanceService;
use App\Services\FaceVerificationService;
use App\Services\LmsAccess;
use App\Exceptions\FaceVerificationException;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct() { $this->middleware('student'); }

    public function index(Request $request)
    {
        $student = LmsAccess::studentRecord();
        $request->validate(['from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
        $query = StudentAttendance::with('course')->where('user_id', Auth::id());
        if ($request->filled('from')) { $query->whereDate('attendance_date', '>=', $request->from); }
        if ($request->filled('to')) { $query->whereDate('attendance_date', '<=', $request->to); }
        $total = (clone $query)->count(); $present = (clone $query)->where('status', 'present')->count();
        $records = $query->latest('attendance_date')->paginate(30)->appends($request->query());
        $lessons = TimeTable::with(['subject', 'time_slot', 'tt_record'])->where('day', Carbon::today()->format('l'))->whereHas('tt_record', function ($q) use ($student) {
            $q->where('my_class_id', $student->my_class_id)->where('year', Qs::getCurrentSession())->whereNull('exam_id')
                ->where(function ($sections) use ($student) { $sections->whereNull('section_id')->orWhere('section_id', $student->section_id); });
        })->get();
        $checkIns = AttendanceCheckIn::with('lesson.subject')->where('user_id', Auth::id())->whereDate('attendance_date', Carbon::today())->get()->keyBy('time_table_id');
        $markedLessonIds = StudentAttendance::where('user_id', Auth::id())->whereDate('attendance_date', Carbon::today())->whereNotNull('time_table_id')->pluck('time_table_id')
            ->merge($checkIns->where('status', 'verified')->pluck('time_table_id'))->unique();
        try { app(FaceVerificationService::class)->referencePath(Auth::user()); $referenceReady = true; }
        catch (FaceVerificationException $error) { $referenceReady = false; }
        return view('pages.student.modules.attendance.index', compact('records', 'total', 'present', 'lessons', 'checkIns', 'markedLessonIds', 'referenceReady'));
    }

    public function checkIn(Request $request, FaceAttendanceService $attendance, FaceVerificationService $verifier)
    {
        return app(AttendanceFaceController::class)->markAttendance($request, $attendance, $verifier);
    }
}
