<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
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

        $assignmentIds = Assignment::where('teacher_id', $teacherId)->pluck('id');
        $quizIds = Quiz::where('teacher_id', $teacherId)->pluck('id');
        $classIds = TeacherAssignment::where('teacher_id', $teacherId)->pluck('my_class_id')->filter()->unique();

        $stats = [
            'courses_assigned' => TeacherAssignment::where('teacher_id', $teacherId)->count(),
            'classes_managed' => $classIds->count(),
            'assignments_created' => $assignmentIds->count(),
            'quizzes_created' => $quizIds->count(),
            'assignment_submissions' => StudentAssignmentSubmission::whereIn('assignment_id', $assignmentIds)->count(),
            'quiz_attempts' => StudentQuizAttempt::whereIn('quiz_id', $quizIds)->count(),
            'attendance_records' => StudentAttendance::whereIn('user_id', function ($query) use ($classIds) {
                $query->select('user_id')->from('student_records')->whereIn('my_class_id', $classIds);
            })->count(),
        ];

        return view('pages.teacher.dashboard', compact('stats'));
    }
}
