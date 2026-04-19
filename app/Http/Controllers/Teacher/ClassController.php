<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAssignment;
use App\Models\TimeTable;
use App\Models\TimeTableRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index(Request $request)
    {
        $classIds = TeacherAssignment::where('teacher_id', Auth::id())->pluck('my_class_id')->filter()->unique();
        $records = TimeTableRecord::whereIn('my_class_id', $classIds)->with('my_class')->latest()->get();

        $selected = $request->get('ttr_id');
        $timeTables = collect();
        if ($selected) {
            $timeTables = TimeTable::with(['subject', 'time_slot'])->where('ttr_id', $selected)->orderBy('day_num')->get();
        }

        return view('pages.teacher.modules.classes.index', compact('records', 'timeTables', 'selected'));
    }
}
