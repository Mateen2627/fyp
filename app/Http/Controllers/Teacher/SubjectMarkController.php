<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\SubjectAssessmentMark;
use App\Services\LmsAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectMarkController extends Controller
{
    public function __construct() { $this->middleware('teacher'); }

    public function index(Request $request)
    {
        $courseIds = LmsAccess::teachingQuery()->pluck('course_id');
        $subjects = Subject::with('my_class')->whereIn('id', Course::whereIn('id', $courseIds)->pluck('subject_id'))->orderBy('my_class_id')->get();
        $selectedSubject = $request->filled('subject_id') ? $subjects->firstWhere('id', (int) $request->subject_id) : $subjects->first();
        if ($request->filled('subject_id')) { abort_unless($selectedSubject, 403); }
        $sections = collect(); $students = collect(); $marks = collect();
        $selectedSectionId = $request->filled('section_id') ? (int) $request->section_id : null;
        $academicYear = $request->get('academic_year', Qs::getCurrentSession());
        if ($selectedSubject) {
            $students = $this->roster($selectedSubject);
            $sections = Section::whereIn('id', $students->pluck('section_id')->unique())->orderBy('name')->get();
            if ($selectedSectionId) { abort_unless($sections->contains('id', $selectedSectionId), 403); $students = $students->where('section_id', $selectedSectionId); }
            $marks = SubjectAssessmentMark::where('subject_id', $selectedSubject->id)->where('academic_year', $academicYear)->whereIn('student_id', $students->pluck('user_id'))->get()->keyBy('student_id');
        }
        return view('pages.teacher.modules.subject_marks.index', compact('subjects', 'selectedSubject', 'sections', 'selectedSectionId', 'students', 'marks', 'academicYear'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|integer|exists:subjects,id', 'section_id' => 'nullable|integer|exists:sections,id',
            'academic_year' => 'required|string|max:20',
            'mid_marks' => 'nullable|array', 'mid_marks.*' => 'nullable|numeric|min:0|max:100',
            'final_marks' => 'nullable|array', 'final_marks.*' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|array', 'remarks.*' => 'nullable|string|max:1000',
        ]);
        $subject = Subject::findOrFail($data['subject_id']);
        $students = $this->roster($subject);
        if (!empty($data['section_id'])) { $students = $students->where('section_id', (int) $data['section_id']); }
        $ids = collect(array_keys($data['mid_marks'] ?? []))->merge(array_keys($data['final_marks'] ?? []))->merge(array_keys($data['remarks'] ?? []))->map(function ($id) { return (int) $id; })->unique();
        abort_unless($ids->diff($students->pluck('user_id'))->isEmpty(), 403);
        foreach ($students->whereIn('user_id', $ids->all()) as $student) {
            $mid = $data['mid_marks'][$student->user_id] ?? null; $final = $data['final_marks'][$student->user_id] ?? null;
            if ($mid === null && $final === null && empty($data['remarks'][$student->user_id])) { continue; }
            SubjectAssessmentMark::updateOrCreate(['student_id' => $student->user_id, 'subject_id' => $subject->id, 'academic_year' => $data['academic_year']], [
                'teacher_id' => Auth::id(), 'my_class_id' => $student->my_class_id, 'section_id' => $student->section_id,
                'mid_marks' => $mid, 'final_marks' => $final, 'total_marks' => ($mid ?? 0) + ($final ?? 0), 'remarks' => $data['remarks'][$student->user_id] ?? null,
            ]);
        }
        return back()->with('flash_success', 'Subject marks saved.');
    }

    private function roster($subject)
    {
        $course = Course::where('subject_id', $subject->id)->where('my_class_id', $subject->my_class_id)->firstOrFail();
        $teaching = LmsAccess::teachingQuery()->where('course_id', $course->id)->where('my_class_id', $subject->my_class_id)->get();
        abort_if($teaching->isEmpty(), 403);
        $query = StudentRecord::with(['user', 'my_class', 'section'])->where('my_class_id', $subject->my_class_id)->whereHas('user');
        if (!$teaching->contains(function ($row) { return $row->section_id === null; })) { $query->whereIn('section_id', $teaching->pluck('section_id')); }
        return $query->orderBy('section_id')->orderBy('adm_no')->get();
    }
}
