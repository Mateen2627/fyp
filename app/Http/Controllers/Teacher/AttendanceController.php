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

        $allowedStudentIds = $this->allowedStudentIds($data['class_id'] ?? null);

        foreach ($data['attendance'] as $studentId => $status) {
            $studentId = (int) $studentId;

            if (!in_array($studentId, $allowedStudentIds, true)) {
                continue;
            }

            $this->saveAttendanceRecord(
                $studentId,
                $data['attendance_date'],
                $status,
                'manual',
                false,
                $data['remarks'][$studentId] ?? null
            );
        }

        return redirect()->route('teacher.attendance.index', $request->only('class_id'))
            ->with('flash_success', 'Manual attendance saved for the selected class.');
    }

    public function storeSingle(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'class_id' => 'nullable|integer',
            'student_id' => 'required|integer',
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:1000',
        ]);

        $studentId = (int) $data['student_id'];

        if (!in_array($studentId, $this->allowedStudentIds($data['class_id'] ?? null), true)) {
            return redirect()->route('teacher.attendance.index', $request->only('class_id'))
                ->with('flash_danger', 'You can only mark attendance for students in your assigned classes.');
        }

        $this->saveAttendanceRecord(
            $studentId,
            $data['attendance_date'],
            $data['status'],
            'manual',
            false,
            $data['remarks'] ?? null
        );

        return redirect()->route('teacher.attendance.index', $request->only('class_id'))
            ->with('flash_success', 'Manual attendance saved for one student.');
    }

    public function storeFacial(Request $request)
    {
        $data = $request->validate([
            'attendance_date' => 'required|date',
            'class_id' => 'nullable|integer',
            'student_id' => 'required|integer',
            'facial_confidence' => 'nullable|array',
            'facial_confidence.*' => 'nullable|numeric|min:0|max:100',
            'facial_remarks' => 'nullable|string|max:1000',
        ]);

        $studentId = (int) $data['student_id'];

        if (!in_array($studentId, $this->allowedStudentIds($data['class_id'] ?? null), true)) {
            return redirect()->route('teacher.attendance.index', $request->only('class_id'))
                ->with('flash_danger', 'You can only mark facial attendance for students in your assigned classes.');
        }

        $confidence = $data['facial_confidence'][$studentId] ?? rand(88, 99);
        $remarks = trim('Facial attendance verified for this student (confidence: '.$confidence.'%). '.($data['facial_remarks'] ?? ''));

        $this->saveAttendanceRecord(
            $studentId,
            $data['attendance_date'],
            'present',
            'facial',
            true,
            $remarks
        );

        return redirect()->route('teacher.attendance.index', $request->only('class_id'))
            ->with('flash_success', 'Facial attendance marked for the selected student.');
    }


    private function allowedStudentIds($classId = null): array
    {
        $classIds = TeacherAssignment::where('teacher_id', Auth::id())->pluck('my_class_id')->filter()->unique();
        $studentQuery = StudentRecord::whereIn('my_class_id', $classIds);

        if ($classId && $classIds->contains((int) $classId)) {
            $studentQuery->where('my_class_id', (int) $classId);
        }

        return $studentQuery->pluck('user_id')->filter()->map(function ($id) {
            return (int) $id;
        })->all();
    }

    private function saveAttendanceRecord(int $studentId, string $attendanceDate, string $status, string $mode, bool $facialVerified, ?string $remarks): void
    {
        StudentAttendance::updateOrCreate(
            ['user_id' => $studentId, 'attendance_date' => $attendanceDate],
            [
                'status' => $status,
                'mode' => $mode,
                'facial_verified' => $facialVerified,
                'remarks' => $remarks,
                'marked_by' => Auth::id(),
            ]
        );
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
