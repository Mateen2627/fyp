<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        return view('pages.student.dashboard');
    }
}
