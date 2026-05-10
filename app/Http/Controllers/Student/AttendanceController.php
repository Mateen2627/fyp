<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('student');
    }

    public function index(Request $request)
    {
        [$from, $to, $period] = $this->resolveDateRange($request);

        $query = StudentAttendance::with('markedBy')
            ->where('user_id', Auth::id())
            ->whereBetween('attendance_date', [$from->toDateString(), $to->toDateString()]);

        $allRecords = (clone $query)->get();
        $records = (clone $query)->latest('attendance_date')->paginate(30)->appends($request->query());

        $summary = [
            'total' => $allRecords->count(),
            'present' => $allRecords->where('status', 'present')->count(),
            'absent' => $allRecords->where('status', 'absent')->count(),
            'late' => $allRecords->where('status', 'late')->count(),
            'excused' => $allRecords->where('status', 'excused')->count(),
        ];
        $summary['percentage'] = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) : 0;

        return view('pages.student.modules.attendance.index', compact('records', 'summary', 'from', 'to', 'period'));
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
