<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminReport;
use App\Models\Course;
use App\Models\TeacherAssignment;
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
                'courses' => Course::count(),
                'assignments' => TeacherAssignment::count(),
                'reports' => AdminReport::count(),
                'settings' => Setting::count(),
            ],
        ]);
    }
}
