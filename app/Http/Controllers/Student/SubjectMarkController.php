<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\SubjectAssessmentMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index(Request $request)
    {
        $academicYear = $request->get('academic_year', Qs::getSetting('current_session'));

        $marks = SubjectAssessmentMark::with(['subject', 'teacher', 'my_class', 'section'])
            ->where('student_id', Auth::id())
            ->where('academic_year', $academicYear)
            ->orderBy('subject_id')
            ->paginate(30)
            ->appends($request->query());

        $summary = [
            'subjects' => $marks->total(),
            'mid_total' => SubjectAssessmentMark::where('student_id', Auth::id())->where('academic_year', $academicYear)->sum('mid_marks'),
            'final_total' => SubjectAssessmentMark::where('student_id', Auth::id())->where('academic_year', $academicYear)->sum('final_marks'),
            'overall_total' => SubjectAssessmentMark::where('student_id', Auth::id())->where('academic_year', $academicYear)->sum('total_marks'),
        ];

        return view('pages.student.modules.subject_marks.index', compact('marks', 'summary', 'academicYear'));
    }
}
