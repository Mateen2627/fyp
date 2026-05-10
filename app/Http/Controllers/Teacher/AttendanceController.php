<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use App\Models\TeacherAssignment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('teacher');
    }

    public function index(Request $request)
    {
        $teacherId = Auth::id();
        $classIds = TeacherAssignment::where('teacher_id', $teacherId)->pluck('my_class_id')->filter()->unique()->values();
        $selectedClassId = $request->filled('class_id') && $classIds->contains((int) $request->class_id)
            ? (int) $request->class_id
            : null;

        $studentQuery = StudentRecord::with(['user', 'my_class', 'section'])
            ->whereIn('my_class_id', $classIds);

        if ($selectedClassId) {
            $studentQuery->where('my_class_id', $selectedClassId);
        }

        $students = $studentQuery->orderBy('my_class_id')->orderBy('section_id')->orderBy('adm_no')->get();
        $studentIds = $students->pluck('user_id')->filter()->unique()->values();
        [$from, $to, $period] = $this->resolveDateRange($request);

        $recordsQuery = StudentAttendance::with(['user.student_record.my_class', 'user.student_record.section', 'markedBy'])
            ->whereIn('user_id', $studentIds)
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()]);

        $records = (clone $recordsQuery)->latest('attendance_date')->paginate(50)->appends($request->query());
        $allRecords = (clone $recordsQuery)->get();
        $markDate = $request->get('attendance_date', Carbon::today()->toDateString());
        $dailyAttendance = StudentAttendance::whereIn('user_id', $studentIds)
            ->where('attendance_date', $markDate)
            ->get()
            ->keyBy('user_id');

        $summary = [
            'total' => $allRecords->count(),
            'present' => $allRecords->where('status', 'present')->count(),
            'absent' => $allRecords->where('status', 'absent')->count(),
            'late' => $allRecords->where('status', 'late')->count(),
            'excused' => $allRecords->where('status', 'excused')->count(),
        ];
        $summary['percentage'] = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) : 0;

        $studentPercentages = $students->map(function ($student) use ($allRecords) {
            $studentRecords = $allRecords->where('user_id', $student->user_id);
            $total = $studentRecords->count();
            $present = $studentRecords->where('status', 'present')->count();

            return [
                'student' => $student,
                'total' => $total,
                'present' => $present,
                'absent' => $studentRecords->where('status', 'absent')->count(),
                'late' => $studentRecords->where('status', 'late')->count(),
                'excused' => $studentRecords->where('status', 'excused')->count(),
                'percentage' => $total > 0 ? round(($present / $total) * 100, 1) : 0,
            ];
        });

        $classes = TeacherAssignment::with('my_class')
            ->where('teacher_id', $teacherId)
            ->whereIn('my_class_id', $classIds)
            ->get()
            ->pluck('my_class')
            ->filter()
            ->unique('id')
            ->values();

        return view('pages.teacher.modules.attendance.index', compact(
            'records',
            'students',
            'classes',
            'selectedClassId',
            'from',
            'to',
            'period',
            'summary',
            'studentPercentages',
            'markDate',
            'dailyAttendance'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'class_id' => 'nullable|integer',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:1000',
        ]);

        $classIds = TeacherAssignment::where('teacher_id', Auth::id())->pluck('my_class_id')->filter()->unique();
        $studentQuery = StudentRecord::whereIn('my_class_id', $classIds);

        if (!empty($data['class_id']) && $classIds->contains((int) $data['class_id'])) {
            $studentQuery->where('my_class_id', (int) $data['class_id']);
        }

        $allowedStudentIds = $studentQuery->pluck('user_id')->filter()->map(function ($id) {
            return (int) $id;
        })->all();

        foreach ($data['attendance'] as $studentId => $status) {
            $studentId = (int) $studentId;

            if (!in_array($studentId, $allowedStudentIds, true)) {
                continue;
            }

            StudentAttendance::updateOrCreate(
                ['user_id' => $studentId, 'attendance_date' => $data['attendance_date']],
                [
                    'status' => $status,
                    'mode' => 'manual',
                    'facial_verified' => 0,
                    'remarks' => $data['remarks'][$studentId] ?? null,
                    'marked_by' => Auth::id(),
                ]
            );
        }

        return redirect()->route('teacher.attendance.index', $request->only('class_id'))
            ->with('flash_success', 'Attendance saved successfully.');
    }

    private function resolveDateRange(Request $request): array
    {
        $period = $request->get('period', 'monthly');
        $today = Carbon::today();

        if ($period === 'weekly') {
            return [$today->copy()->startOfWeek(), $today->copy()->endOfWeek(), $period];
        }

        if ($period === 'custom' && $request->filled('from') && $request->filled('to')) {
            return [Carbon::parse($request->from)->startOfDay(), Carbon::parse($request->to)->endOfDay(), $period];
        }

        return [$today->copy()->startOfMonth(), $today->copy()->endOfMonth(), 'monthly'];
    }
}
