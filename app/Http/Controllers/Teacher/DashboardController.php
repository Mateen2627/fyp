<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Services\LmsAccess;
use App\Models\Assignment;
use App\Models\Quiz;
use App\Models\StudentAssignmentSubmission;
use App\Models\StudentAttendance;
use App\Models\StudentQuizAttempt;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $teacherId = Auth::id();

        $assignmentIds = LmsAccess::scopeTeacherActivities(Assignment::query())->pluck('id');
        $quizIds = LmsAccess::scopeTeacherActivities(Quiz::query())->pluck('id');
        $classIds = TeacherAssignment::where('teacher_id', $teacherId)->pluck('my_class_id')->filter()->unique();

        $stats = [
            'courses_assigned' => TeacherAssignment::where('teacher_id', $teacherId)->count(),
            'classes_managed' => $classIds->count(),
            'assignments_created' => $assignmentIds->count(),
            'quizzes_created' => $quizIds->count(),
            'assignment_submissions' => StudentAssignmentSubmission::whereIn('assignment_id', $assignmentIds)->count(),
            'quiz_attempts' => StudentQuizAttempt::whereIn('quiz_id', $quizIds)->count(),
            'attendance_records' => LmsAccess::scopeTeachingRecords(StudentAttendance::query())->count(),
        ];

        return view('pages.teacher.dashboard', compact('stats'));
    }
}
