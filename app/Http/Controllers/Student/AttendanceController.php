<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\AttendanceCheckIn;
use App\Models\Course;
use App\Models\StudentAttendance;
use App\Models\TimeTable;
use App\Services\FaceVerificationService;
use App\Services\LmsAccess;
use App\Services\TimetableService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $checkIns = AttendanceCheckIn::where('user_id', Auth::id())->whereDate('attendance_date', Carbon::today())->get()->keyBy('time_table_id');
        return view('pages.student.modules.attendance.index', compact('records', 'total', 'present', 'lessons', 'checkIns'));
    }

    public function checkIn(Request $request, FaceVerificationService $verifier)
    {
        $data = $request->validate(['time_table_id' => 'required|integer|exists:time_tables,id', 'capture' => 'required|image|mimes:jpeg,jpg,png|max:5120']);
        $student = LmsAccess::studentRecord();
        $lesson = TimeTable::with(['tt_record', 'time_slot'])->findOrFail($data['time_table_id']);
        $record = $lesson->tt_record;
        abort_unless($record && $record->my_class_id == $student->my_class_id && (!$record->section_id || $record->section_id == $student->section_id), 403);
        abort_unless(!$record->exam_id && $record->year === Qs::getCurrentSession(), 422);
        $today = Carbon::today();
        $start = Carbon::parse($today->toDateString().' '.$lesson->time_slot->time_from);
        $end = Carbon::parse($today->toDateString().' '.$lesson->time_slot->time_to);
        if ($lesson->day !== $today->format('l') || !Carbon::now()->between($start->copy()->subMinutes(15), $end)) {
            throw ValidationException::withMessages(['time_table_id' => 'Check-in is available from 15 minutes before the lesson until it ends.']);
        }
        $course = Course::where('subject_id', $lesson->subject_id)->where('my_class_id', $record->my_class_id)->firstOrFail();
        abort_if(app(TimetableService::class)->teachers($record, $course->id)->isEmpty(), 422, 'No teacher is assigned to this lesson.');
        $key = ['user_id' => Auth::id(), 'time_table_id' => $lesson->id, 'attendance_date' => $today->toDateString()];
        if (AttendanceCheckIn::where('user_id', Auth::id())->where('time_table_id', $lesson->id)->whereDate('attendance_date', $today)->exists()) {
            throw ValidationException::withMessages(['check_in' => 'You have already checked in for this lesson today.']);
        }
        $result = $verifier->verify(Auth::user(), $request->file('capture'), $lesson);
        $verified = ($result['verified'] ?? false) === true;
        $status = $verified ? 'verified' : (($result['status'] ?? '') === 'rejected' ? 'rejected' : 'pending');
        $path = $request->file('capture')->store('attendance_captures', 'local');
        try {
        DB::transaction(function () use ($key, $course, $student, $result, $verified, $status, $path) {
            \App\Models\StudentRecord::whereKey($student->id)->lockForUpdate()->first();
            if (AttendanceCheckIn::where('user_id', $key['user_id'])->where('time_table_id', $key['time_table_id'])->whereDate('attendance_date', $key['attendance_date'])->exists()) {
                throw ValidationException::withMessages(['check_in' => 'You have already checked in for this lesson today.']);
            }
            AttendanceCheckIn::create(array_merge($key, ['course_id' => $course->id, 'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id, 'status' => $status, 'image_path' => $path, 'message' => $result['message'] ?? null]));
            if ($verified) {
                StudentAttendance::updateOrCreate($key, ['course_id' => $course->id, 'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id, 'status' => 'present', 'mode' => 'facial', 'facial_verified' => true, 'marked_by' => Auth::id(), 'remarks' => 'Identity and liveness verified by the server face verifier.']);
            }
        });
        } catch (\Throwable $error) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($path);
            throw $error;
        }
        $message = $result['message'] ?? ($verified ? 'Attendance verified.' : 'Check-in pending verification.');
        if ($request->expectsJson()) { return response()->json(['status' => $status, 'message' => $message], $verified ? 200 : 202); }
        return back()->with('flash_success', $message);
    }
}
