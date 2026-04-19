<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\AdminReport;
use App\Models\Course;
use App\Models\MyClass;
use App\Models\Quiz;
use App\Models\StudentAttendance;
use App\Models\StudentHelpSupport;
use App\Models\Setting;
use App\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    public function index()
    {
        return view('pages.admin.modules.dashboard', [
            'stats' => [
                'users' => User::count(),
                'teachers' => User::where('user_type', 'teacher')->count(),
                'students' => User::where('user_type', 'student')->count(),
                'classes' => MyClass::count(),
                'courses' => Course::count(),
                'assignments' => Assignment::count(),
                'quizzes' => Quiz::count(),
                'attendance_records' => StudentAttendance::count(),
                'open_support_tickets' => StudentHelpSupport::where('status', 'open')->count(),
                'reports' => AdminReport::count(),
                'settings' => Setting::count(),
            ],
        ]);
    }
}
