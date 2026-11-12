<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
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
            abort_unless($records->contains('id', (int) $selected), 403);
            $record = $records->firstWhere('id', (int) $selected);
            $timeTables = TimeTable::with(['subject', 'time_slot'])->where('ttr_id', $selected)->orderBy('day_num')->get();

            $courseIds = Course::where('my_class_id', $record->my_class_id)
                ->whereIn('subject_id', $timeTables->pluck('subject_id')->unique())
                ->pluck('id', 'subject_id');
            $assignmentsByCourse = TeacherAssignment::with('teacher')
                ->whereIn('course_id', $courseIds->values())
                ->where('my_class_id', $record->my_class_id)
                ->where(function ($query) use ($record) {
                    $query->whereNull('section_id');
                    if ($record->section_id) {
                        $query->orWhere('section_id', $record->section_id);
                    }
                })
                ->whereHas('teacher')
                ->get()
                ->groupBy('course_id');

            foreach ($timeTables as $timeTable) {
                $courseId = $courseIds->get($timeTable->subject_id);
                $assignments = $courseId ? $assignmentsByCourse->get($courseId, collect()) : collect();
                $timeTable->setRelation('assigned_teachers', $assignments->pluck('teacher')->filter()->unique('id')->values());
            }
        }

        return view('pages.teacher.modules.classes.index', compact('records', 'timeTables', 'selected'));
    }
}
