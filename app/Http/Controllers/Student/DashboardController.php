<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentAttendance;
use App\Models\StudentHelpSupport;
use App\Models\StudentQuizAttempt;
use App\Models\StudentRecord;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->first();

        $assignmentQuery = Assignment::query()->whereNotNull('teacher_id');
        $quizQuery = Quiz::query()->whereNotNull('teacher_id');

        if ($record) {
            $assignmentQuery->where('my_class_id', $record->my_class_id);
            $quizQuery->where('my_class_id', $record->my_class_id);
        } else {
            $assignmentQuery->whereRaw('1 = 0');
            $quizQuery->whereRaw('1 = 0');
        }

        $totalAssignments = (clone $assignmentQuery)->count();
        $submittedAssignments = StudentAssignmentSubmission::where('student_id', Auth::id())->count();
        $pendingAssignments = max($totalAssignments - $submittedAssignments, 0);

        $totalQuizzes = (clone $quizQuery)->count();
        $attemptedQuizzes = StudentQuizAttempt::where('student_id', Auth::id())->count();
        $pendingQuizzes = max($totalQuizzes - $attemptedQuizzes, 0);

        $attendancePresent = StudentAttendance::where('user_id', Auth::id())->where('status', 'present')->count();
        $supportTickets = StudentHelpSupport::where('user_id', Auth::id())->count();

        return view('pages.student.dashboard', compact(
            'totalAssignments',
            'submittedAssignments',
            'pendingAssignments',
            'totalQuizzes',
            'attemptedQuizzes',
            'pendingQuizzes',
            'attendancePresent',
            'supportTickets'
        ));
    }
}
