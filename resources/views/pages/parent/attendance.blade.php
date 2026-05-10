@extends('layouts.master')
@section('page_title', 'Child Attendance')
@section('content')

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Attendance for {{ optional($student->user)->name }}</h6>
        {!! Qs::getPanelOptions() !!}
    </div>
    <div class="card-body">
        <p class="mb-3">
            <strong>Class:</strong> {{ optional($student->my_class)->name }} {{ optional($student->section)->name }}
            <span class="ml-3"><strong>ADM No:</strong> {{ $student->adm_no }}</span>
        </p>
        <form method="get" action="{{ route('parent.attendance.show', $student->id) }}" class="row align-items-end">
            <div class="col-md-3">
                <label>Filter</label>
                <select name="period" class="form-control">
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div class="col-md-3">
                <label>From</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label>To</label>
                <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-block">Apply Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-3"><div class="card card-body text-center"><h3>{{ $summary['total'] }}</h3><span>Total Records</span></div></div>
    <div class="col-md-3"><div class="card card-body text-center"><h3 class="text-success">{{ $summary['present'] }}</h3><span>Present</span></div></div>
    <div class="col-md-3"><div class="card card-body text-center"><h3 class="text-warning">{{ $summary['late'] }}</h3><span>Late</span></div></div>
    <div class="col-md-3"><div class="card card-body text-center"><h3 class="text-primary">{{ $summary['percentage'] }}%</h3><span>Attendance Percentage</span></div></div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Records from {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Date</th>
                <th>Status</th>
                <th>Marked By</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'present' ? 'success' : ($row->status === 'absent' ? 'danger' : 'warning') }}">{{ ucfirst($row->status) }}</span></td>
                    <td>{{ optional($row->markedBy)->name ?: '-' }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">No attendance records found for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>

@endsection
