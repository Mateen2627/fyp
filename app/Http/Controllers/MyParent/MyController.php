<?php

namespace App\Http\Controllers\MyParent;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentQuizAttempt;
use App\Models\StudentRecord;
use App\Models\SubjectAssessmentMark;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
    public function __construct()
    {
        $this->middleware('my_parent');
    }

    public function children()
    {
        $data['students'] = Qs::findMyChildren(Auth::user()->id);

        return view('pages.parent.children', $data);
    }

    public function attendance(Request $request, StudentRecord $student)
    {
        if ((int) $student->my_parent_id !== (int) Auth::id()) {
            return redirect()->route('dashboard')->with('pop_error', __('msg.denied'));
        }

        [$from, $to, $period] = $this->resolveDateRange($request);

        $query = StudentAttendance::with('markedBy')
            ->where('user_id', $student->user_id)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()]);

        $allRecords = (clone $query)->get();
        $records = (clone $query)->latest('attendance_date')->paginate(30)->appends($request->query());

        $summary = [
            'total' => $allRecords->count(),
            'present' => $allRecords->where('status', 'present')->count(),
            'absent' => $allRecords->where('status', 'absent')->count(),
            'late' => $allRecords->where('status', 'late')->count(),
            'excused' => $allRecords->where('status', 'excused')->count(),
        ];
        $summary['percentage'] = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) : 0;

        $student->load(['user', 'my_class', 'section']);

        return view('pages.parent.attendance', compact('student', 'records', 'summary', 'from', 'to', 'period'));
    }

    public function academics(Request $request, StudentRecord $student)
    {
        if ((int) $student->my_parent_id !== (int) Auth::id()) {
            return redirect()->route('dashboard')->with('pop_error', __('msg.denied'));
        }

        $student->load(['user', 'my_class', 'section']);
        $studentUserId = (int) $student->user_id;
        $classId = (int) $student->my_class_id;
        $academicYear = $request->get('academic_year', Qs::getSetting('current_session'));

        $marks = SubjectAssessmentMark::with(['subject', 'teacher'])
            ->where('student_id', $studentUserId)
            ->where('academic_year', $academicYear)
            ->orderBy('subject_id')
            ->get();

        $assignments = Assignment::with(['course', 'teacher'])
            ->whereNotNull('teacher_id')
            ->where('my_class_id', $classId)
            ->latest()
            ->take(50)
            ->get();

        $assignmentSubmissionMap = StudentAssignmentSubmission::where('student_id', $studentUserId)
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->get()
            ->keyBy('assignment_id');

        $quizzes = Quiz::with(['course', 'teacher'])
            ->whereNotNull('teacher_id')
            ->where('my_class_id', $classId)
            ->latest()
            ->take(50)
            ->get();

        $quizAttemptMap = StudentQuizAttempt::where('student_id', $studentUserId)
            ->whereIn('quiz_id', $quizzes->pluck('id'))
            ->get()
            ->keyBy('quiz_id');

        $summary = [
            'subjects' => $marks->count(),
            'mid_total' => $marks->sum('mid_marks'),
            'final_total' => $marks->sum('final_marks'),
            'overall_total' => $marks->sum('total_marks'),
            'assignments_total' => $assignments->count(),
            'assignments_submitted' => $assignmentSubmissionMap->count(),
            'quizzes_total' => $quizzes->count(),
            'quizzes_submitted' => $quizAttemptMap->count(),
        ];

        return view('pages.parent.academics', compact(
            'student',
            'academicYear',
            'marks',
            'assignments',
            'assignmentSubmissionMap',
            'quizzes',
            'quizAttemptMap',
            'summary'
        ));
    }

    private function resolveDateRange(Request $request): array
    {
        $period = $request->get('period', 'monthly');
        $today = Carbon::today();

        if ($period === 'weekly') {
            return [$today->copy()->startOfWeek(), $today->copy()->endOfWeek(), $period];
        }

        if ($period === 'custom' && $request->filled('from') && $request->filled('to')) {
            return [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay(), $period];
        }

        return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), 'monthly'];
    }
}
