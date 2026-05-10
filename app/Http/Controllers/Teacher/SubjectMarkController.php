<?php

namespace App\Http\Controllers\Teacher;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\StudentRecord;
use App\Models\Subject;
use App\Models\SubjectAssessmentMark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectMarkController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index(Request $request)
    {
        $teacherId = Auth::id();
        $subjects = Subject::with('my_class')
            ->where('teacher_id', $teacherId)
            ->orderBy('my_class_id')
            ->orderBy('name')
            ->get();

        $selectedSubject = $request->filled('subject_id')
            ? $subjects->firstWhere('id', (int) $request->subject_id)
            : $subjects->first();

        $sections = collect();
        $selectedSectionId = $request->filled('section_id') ? (int) $request->section_id : null;
        $students = collect();
        $marks = collect();
        $academicYear = $request->get('academic_year', Qs::getSetting('current_session'));

        if ($selectedSubject) {
            $sections = Section::where('my_class_id', $selectedSubject->my_class_id)
                ->orderBy('name')
                ->get();

            if ($selectedSectionId && !$sections->contains('id', $selectedSectionId)) {
                $selectedSectionId = null;
            }

            $studentQuery = StudentRecord::with(['user', 'my_class', 'section'])
                ->where('my_class_id', $selectedSubject->my_class_id);

            if ($selectedSectionId) {
                $studentQuery->where('section_id', $selectedSectionId);
            }

            $students = $studentQuery->orderBy('section_id')->orderBy('adm_no')->get();
            $marks = SubjectAssessmentMark::where('subject_id', $selectedSubject->id)
                ->where('academic_year', $academicYear)
                ->whereIn('student_id', $students->pluck('user_id')->filter())
                ->get()
                ->keyBy('student_id');
        }

        return view('pages.teacher.modules.subject_marks.index', compact(
            'subjects',
            'selectedSubject',
            'sections',
            'selectedSectionId',
            'students',
            'marks',
            'academicYear'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|integer',
            'section_id' => 'nullable|integer',
            'academic_year' => 'required|string|max:20',
            'mid_marks' => 'nullable|array',
            'mid_marks.*' => 'nullable|numeric|min:0|max:100',
            'final_marks' => 'nullable|array',
            'final_marks.*' => 'nullable|numeric|min:0|max:100',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:1000',
        ]);

        $teacherId = Auth::id();
        $subject = Subject::where('teacher_id', $teacherId)->findOrFail($data['subject_id']);
        $studentIds = collect(array_keys($data['mid_marks'] ?? []))
            ->merge(array_keys($data['final_marks'] ?? []))
            ->merge(array_keys($data['remarks'] ?? []))
            ->map(function ($id) {
                return (int) $id;
            })
            ->unique()
            ->values();

        if ($studentIds->isEmpty()) {
            return back()->with('flash_danger', 'No marks were submitted.');
        }

        $allowedStudents = StudentRecord::where('my_class_id', $subject->my_class_id)
            ->when(!empty($data['section_id']), function ($query) use ($data) {
                $query->where('section_id', (int) $data['section_id']);
            })
            ->whereIn('user_id', $studentIds)
            ->get()
            ->keyBy('user_id');

        $saved = 0;

        foreach ($allowedStudents as $studentId => $record) {
            $mid = $data['mid_marks'][$studentId] ?? null;
            $final = $data['final_marks'][$studentId] ?? null;

            if ($mid === null && $final === null && empty($data['remarks'][$studentId])) {
                continue;
            }

            $mid = $mid === null || $mid === '' ? null : (float) $mid;
            $final = $final === null || $final === '' ? null : (float) $final;
            $total = ($mid ?? 0) + ($final ?? 0);

            SubjectAssessmentMark::updateOrCreate(
                [
                    'student_id' => (int) $studentId,
                    'subject_id' => $subject->id,
                    'academic_year' => $data['academic_year'],
                ],
                [
                    'teacher_id' => $teacherId,
                    'my_class_id' => $subject->my_class_id,
                    'section_id' => $record->section_id,
                    'mid_marks' => $mid,
                    'final_marks' => $final,
                    'total_marks' => $total,
                    'remarks' => $data['remarks'][$studentId] ?? null,
                ]
            );

            $saved++;
        }

        return redirect()->route('teacher.subject_marks.index', $request->only(['subject_id', 'section_id', 'academic_year']))
            ->with($saved ? 'flash_success' : 'flash_danger', $saved ? 'Mid and final marks saved successfully.' : 'No valid student marks were saved.');
    }
}
