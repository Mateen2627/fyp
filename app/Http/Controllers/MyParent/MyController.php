<?php

namespace App\Http\Controllers\MyParent;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyController extends Controller
{
    public function __construct()
    {
        $this->middleware('my_parent');
    }

    public function children()
    {
        $data['students'] = Qs::findMyChildren(Auth::user()->id);

        return view('pages.parent.children', $data);
    }

    public function attendance(Request $request, StudentRecord $student)
    {
        if ((int) $student->my_parent_id !== (int) Auth::id()) {
            return redirect()->route('dashboard')->with('pop_error', __('msg.denied'));
        }

        [$from, $to, $period] = $this->resolveDateRange($request);

        $query = StudentAttendance::with('markedBy')
            ->where('user_id', $student->user_id)
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

        $student->load(['user', 'my_class', 'section']);

        return view('pages.parent.attendance', compact('student', 'records', 'summary', 'from', 'to', 'period'));
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
