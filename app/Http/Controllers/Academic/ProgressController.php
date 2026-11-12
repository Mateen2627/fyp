<?php

namespace App\Http\Controllers\Academic;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AttendanceCheckIn;
use App\Models\MyClass;
use App\Models\Quiz;
use App\Models\Section;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentAttendance;
use App\Models\StudentQuizAttempt;
use App\Models\StudentRecord;
use App\Models\SubjectAssessmentMark;
use App\Services\LmsAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgressController extends Controller
{
    public function __construct() { $this->middleware('departmentRole:leader'); }

    public function index()
    {
        $classes = MyClass::with(['class_type', 'section'])->withCount('student_record')
            ->whereIn('class_type_id', LmsAccess::departmentIds('leader'))->orderBy('name')->get();
        return view('pages.academic.progress.index', compact('classes'));
    }

    public function show(Request $request, $class)
    {
        $class = LmsAccess::assertClass($class, 'leader');
        $sections = Section::where('my_class_id', $class->id)->get();
        $request->validate(['section_id' => 'nullable|integer', 'from' => 'nullable|date', 'to' => 'nullable|date|after_or_equal:from']);
        $sectionId = $request->filled('section_id') ? (int) $request->section_id : null;
        if ($sectionId) { abort_unless($sections->contains('id', $sectionId), 403); }
        $students = StudentRecord::with(['user', 'section'])->where('my_class_id', $class->id)->whereHas('user');
        if ($sectionId) { $students->where('section_id', $sectionId); }
        $students = $students->get();
        $assignments = $this->activities(Assignment::with(['course', 'teacher'])->withCount('submissions'), $class->id, $sectionId)->get();
        $quizzes = $this->activities(Quiz::with(['course', 'teacher'])->withCount('attempts'), $class->id, $sectionId)->get();
        $attendance = StudentAttendance::with(['user', 'course'])->where('my_class_id', $class->id);
        $pending = AttendanceCheckIn::with(['user', 'lesson.subject'])->where('my_class_id', $class->id)->where('status', 'pending');
        foreach ([$attendance, $pending] as $query) {
            if ($sectionId) { $query->where('section_id', $sectionId); }
            if ($request->filled('from')) { $query->whereDate('attendance_date', '>=', $request->from); }
            if ($request->filled('to')) { $query->whereDate('attendance_date', '<=', $request->to); }
        }
        $attendanceTotal = (clone $attendance)->count();
        $presentTotal = (clone $attendance)->where('status', 'present')->count();
        $attendance = $attendance->latest('attendance_date')->paginate(30, ['*'], 'attendance_page')->appends($request->query());
        $pending = $pending->latest()->paginate(20, ['*'], 'pending_page')->appends($request->query());
        $marks = SubjectAssessmentMark::with(['student', 'subject'])->where('my_class_id', $class->id)->whereIn('student_id', $students->pluck('user_id'))->get();
        return view('pages.academic.progress.show', compact('class', 'sections', 'sectionId', 'students', 'assignments', 'quizzes', 'attendance', 'attendanceTotal', 'presentTotal', 'pending', 'marks'));
    }

    public function activity($kind, $id)
    {
        $activity = $this->findActivity($kind, $id);
        $responses = $kind === 'assignments'
            ? StudentAssignmentSubmission::with('student')->where('assignment_id', $activity->id)->get()
            : StudentQuizAttempt::with('student')->where('quiz_id', $activity->id)->get();
        return view('pages.academic.progress.activity', compact('kind', 'activity', 'responses'));
    }

    public function material($kind, $id)
    {
        $activity = $this->findActivity($kind, $id);
        return $this->file($activity->attachment_path);
    }

    public function responseFile($kind, $id, $response)
    {
        $activity = $this->findActivity($kind, $id);
        $submission = $kind === 'assignments'
            ? StudentAssignmentSubmission::where('assignment_id', $activity->id)->findOrFail($response)
            : StudentQuizAttempt::where('quiz_id', $activity->id)->findOrFail($response);
        return $this->file($submission->attachment_path);
    }

    private function activities($query, $classId, $sectionId)
    {
        $query->where('my_class_id', $classId);
        if ($sectionId) { $query->where(function ($q) use ($sectionId) { $q->whereNull('section_id')->orWhere('section_id', $sectionId); }); }
        return $query->latest();
    }

    private function findActivity($kind, $id)
    {
        abort_unless(in_array($kind, ['assignments', 'quizzes'], true), 404);
        $activity = $kind === 'assignments' ? Assignment::findOrFail($id) : Quiz::findOrFail($id);
        LmsAccess::assertClass($activity->my_class_id, 'leader');
        return $activity;
    }

    private function file($path)
    {
        abort_unless($path && Storage::disk('local')->exists($path), 404);
        return response()->file(Storage::disk('local')->path($path), ['Content-Type' => 'application/pdf']);
    }
}
