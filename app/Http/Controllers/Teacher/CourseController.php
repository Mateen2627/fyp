<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index()
    {
        $courses = TeacherAssignment::with(['course', 'my_class'])
            ->where('teacher_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.teacher.modules.courses.index', compact('courses'));
    }
}
