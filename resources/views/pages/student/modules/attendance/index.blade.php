@extends('layouts.master')
@section('page_title', 'Attendance History')
@section('content')

<style>
    .table-filter-bar { display: flex; flex-wrap: wrap; gap: 12px; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .table-filter-bar .form-control { max-width: 280px; }
</style>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">My Attendance History</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="get" action="{{ route('student.attendance.index') }}" class="row align-items-end">
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
    <div class="table-filter-bar">
        <input type="search" class="form-control table-search" data-table-target="student-attendance-table" placeholder="Search date, teacher, status, or remarks">
        <select class="form-control table-status-filter" data-table-target="student-attendance-table">
            <option value="">All Statuses</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
            <option value="excused">Excused</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0" id="student-attendance-table">
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
                <tr data-status="{{ $row->status }}" data-search="{{ strtolower(optional($row->attendance_date)->format('Y-m-d').' '.$row->status.' '.optional($row->markedBy)->name.' '.$row->remarks) }}">
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


<script>
    document.addEventListener('DOMContentLoaded', function () {
        function filterTable() {
            const table = document.getElementById('student-attendance-table');
            if (!table) return;

            const search = (document.querySelector('.table-search[data-table-target="student-attendance-table"]')?.value || '').toLowerCase();
            const status = document.querySelector('.table-status-filter[data-table-target="student-attendance-table"]')?.value || '';

            table.querySelectorAll('tbody tr').forEach(function (row) {
                if (!row.dataset.search && !row.dataset.status) return;

                const matchesSearch = !search || (row.dataset.search || row.textContent.toLowerCase()).includes(search);
                const matchesStatus = !status || row.dataset.status === status;
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        document.querySelectorAll('[data-table-target="student-attendance-table"]').forEach(function (control) {
            control.addEventListener('input', filterTable);
            control.addEventListener('change', filterTable);
        });
    });
</script>

@endsection
