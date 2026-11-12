<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index()
    {
        $record = StudentRecord::where('user_id', Auth::id())->latest()->firstOrFail();

        $courses = TeacherAssignment::with(['course', 'teacher'])
            ->whereHas('course', function ($q) use ($record) { $q->where('my_class_id', $record->my_class_id); })
            ->where('my_class_id', $record->my_class_id)
            ->where(function ($q) use ($record) { $q->whereNull('section_id')->orWhere('section_id', $record->section_id); })
            ->latest()
            ->get();

        $ttr = TimeTableRecord::where('my_class_id', $record->my_class_id)->where(function ($q) use ($record) { $q->whereNull('section_id')->orWhere('section_id', $record->section_id); })->latest()->first();
        $timeTables = collect();

        if ($ttr) {
            $timeTables = TimeTable::with(['subject', 'time_slot'])
                ->where('ttr_id', $ttr->id)
                ->orderBy('day_num')
                ->get();
        }

        return view('pages.student.modules.courses.index', compact('courses', 'timeTables', 'ttr'));
    }
}
