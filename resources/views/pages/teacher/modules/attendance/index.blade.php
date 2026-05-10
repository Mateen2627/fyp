@extends('layouts.master')
@section('page_title', 'Student Attendance')
@section('content')

<style>
    .attendance-mode-grid { display: grid; grid-template-columns: 1fr; gap: 20px; }
    .table-filter-bar { display: flex; flex-wrap: wrap; gap: 12px; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; background: #f8fafc; }
    .table-filter-bar .form-control { max-width: 260px; }
    .ai-panel { border: 1px solid #dbeafe; background: linear-gradient(135deg, #eff6ff 0%, #f5f3ff 100%); border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; }
    .ai-panel h6 { margin: 0 0 6px; font-weight: 700; color: #1e3a8a; }
    .ai-confidence { font-size: 12px; font-weight: 700; }
    .row-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    @media (min-width: 1200px) { .attendance-mode-grid { grid-template-columns: 1.35fr 1fr; align-items: start; } }
</style>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Student Attendance</h6>
        {!! Qs::getPanelOptions() !!}
    </div>

    <div class="card-body">
        <form method="get" action="{{ route('teacher.attendance.index') }}" class="row align-items-end">
            <div class="col-md-3">
                <label>Class</label>
                <select name="class_id" class="form-control">
                    <option value="">All Assigned Classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ (int) $selectedClassId === (int) $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Filter</label>
                <select name="period" class="form-control">
                    <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>This Month</option>
                    <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>This Week</option>
                    <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Custom Range</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>From</label>
                <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="form-control">
            </div>
            <div class="col-md-2">
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
    <div class="col-md-3"><div class="card card-body text-center"><h3 class="text-danger">{{ $summary['absent'] }}</h3><span>Absent</span></div></div>
    <div class="col-md-3"><div class="card card-body text-center"><h3 class="text-primary">{{ $summary['percentage'] }}%</h3><span>Attendance Percentage</span></div></div>
</div>

<div class="attendance-mode-grid">
    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">Manual Attendance</h6>
            <span class="badge badge-info">Bulk or one student at a time</span>
        </div>
        <div class="card-body">
            <div class="alert alert-info mb-3">
                Use <strong>Save All Manual Attendance</strong> for the full list, or use <strong>Save This Student</strong> to update one student only.
            </div>
            <form method="post" action="{{ route('teacher.attendance.store') }}" id="bulk-manual-form">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <div class="form-group row">
                    <label class="col-lg-2 col-form-label">Attendance Date</label>
                    <div class="col-lg-4">
                        <input type="date" name="attendance_date" id="manual_attendance_date" value="{{ $markDate }}" class="form-control" required>
                    </div>
                    <div class="col-lg-6 text-right">
                        <button type="submit" class="btn btn-success">Save All Manual Attendance</button>
                    </div>
                </div>

                <div class="table-filter-bar">
                    <input type="search" class="form-control table-search" data-table-target="mark-attendance-table" placeholder="Search student, ADM no, or class">
                    <select class="form-control table-status-filter" data-table-target="mark-attendance-table">
                        <option value="">All Statuses</option>
                        <option value="present">Present</option>
                        <option value="absent">Absent</option>
                        <option value="late">Late</option>
                        <option value="excused">Excused</option>
                    </select>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="mark-attendance-table">
                        <thead>
                        <tr>
                            <th>Student</th>
                            <th>ADM No</th>
                            <th>Class</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Single Save</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($students as $student)
                            @php($existingAttendance = $dailyAttendance->get($student->user_id))
                            <tr data-student-id="{{ $student->user_id }}" data-search="{{ strtolower(optional($student->user)->name.' '.$student->adm_no.' '.optional($student->my_class)->name.' '.optional($student->section)->name) }}" data-status="{{ optional($existingAttendance)->status ?: 'present' }}">
                                <td>{{ optional($student->user)->name }}</td>
                                <td>{{ $student->adm_no }}</td>
                                <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                                <td>
                                    <select name="attendance[{{ $student->user_id }}]" class="form-control manual-status" required>
                                        <option value="present" {{ optional($existingAttendance)->status === 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ optional($existingAttendance)->status === 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ optional($existingAttendance)->status === 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="excused" {{ optional($existingAttendance)->status === 'excused' ? 'selected' : '' }}>Excused</option>
                                    </select>
                                </td>
                                <td><input type="text" name="remarks[{{ $student->user_id }}]" value="{{ optional($existingAttendance)->remarks }}" class="form-control manual-remarks" placeholder="Optional remarks"></td>
                                <td><button type="button" class="btn btn-outline-primary btn-sm save-single-manual">Save This Student</button></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center">No students found for your assigned classes.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
            <form method="post" action="{{ route('teacher.attendance.store_single') }}" id="single-manual-form" style="display: none;">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <input type="hidden" name="attendance_date" id="single_attendance_date">
                <input type="hidden" name="student_id" id="single_student_id">
                <input type="hidden" name="status" id="single_status">
                <input type="hidden" name="remarks" id="single_remarks">
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header header-elements-inline">
            <h6 class="card-title">AI Facial Attendance</h6>
            <span class="badge badge-primary">Fake AI now, model later</span>
        </div>
        <div class="card-body">
            <div class="ai-panel">
                <h6><i class="fas fa-robot"></i> Optional AI Mode</h6>
                <div>For now this is a fake scanner. Click <strong>Run Fake AI Scan</strong>, review recognized students, then save. Later you can replace this with your real AI model output.</div>
            </div>

            <form method="post" action="{{ route('teacher.attendance.store_facial') }}" id="facial-attendance-form">
                @csrf
                <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
                <div class="form-group">
                    <label>Attendance Date</label>
                    <input type="date" name="attendance_date" value="{{ $markDate }}" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>AI Remarks</label>
                    <input type="text" name="facial_remarks" class="form-control" placeholder="Optional note for fake AI facial attendance">
                </div>
                <div class="row-actions mb-3">
                    <button type="button" class="btn btn-info" id="fake-ai-scan">Run Fake AI Scan</button>
                    <button type="button" class="btn btn-light" id="clear-ai-scan">Clear Scan</button>
                    <button type="submit" class="btn btn-success">Save Facial Attendance</button>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="facial-attendance-table">
                        <thead>
                        <tr>
                            <th width="70">Seen</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Fake AI Confidence</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($students as $student)
                            <tr>
                                <td><input type="checkbox" name="facial_student_ids[]" value="{{ $student->user_id }}" class="facial-check"></td>
                                <td>{{ optional($student->user)->name }}<br><small class="text-muted">{{ $student->adm_no }}</small></td>
                                <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                                <td>
                                    <input type="hidden" name="facial_confidence[{{ $student->user_id }}]" class="facial-confidence-input" value="">
                                    <span class="badge badge-light ai-confidence">Not scanned</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center">No students found for your assigned classes.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Percentage by Student</h6>
    </div>
    <div class="table-filter-bar">
        <input type="search" class="form-control table-search" data-table-target="student-percentage-table" placeholder="Search student or class">
    </div>
    <div class="table-responsive">
        <table class="table table-striped" id="student-percentage-table">
            <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Total</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Excused</th>
                <th>Percentage</th>
            </tr>
            </thead>
            <tbody>
            @forelse($studentPercentages as $row)
                <tr data-search="{{ strtolower(optional($row['student']->user)->name.' '.optional($row['student']->my_class)->name.' '.optional($row['student']->section)->name) }}">
                    <td>{{ optional($row['student']->user)->name }}</td>
                    <td>{{ optional($row['student']->my_class)->name }} {{ optional($row['student']->section)->name }}</td>
                    <td>{{ $row['total'] }}</td>
                    <td>{{ $row['present'] }}</td>
                    <td>{{ $row['absent'] }}</td>
                    <td>{{ $row['late'] }}</td>
                    <td>{{ $row['excused'] }}</td>
                    <td><strong>{{ $row['percentage'] }}%</strong></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No students found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Attendance Records from {{ $from->format('d M Y') }} to {{ $to->format('d M Y') }}</h6>
    </div>
    <div class="table-filter-bar">
        <input type="search" class="form-control table-search" data-table-target="attendance-records-table" placeholder="Search student, class, teacher, method, or remarks">
        <select class="form-control table-status-filter" data-table-target="attendance-records-table">
            <option value="">All Statuses</option>
            <option value="present">Present</option>
            <option value="absent">Absent</option>
            <option value="late">Late</option>
            <option value="excused">Excused</option>
        </select>
    </div>
    <div class="table-responsive">
        <table class="table table-striped mb-0" id="attendance-records-table">
            <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Date</th>
                <th>Status</th>
                <th>Method</th>
                <th>AI Verified</th>
                <th>Marked By</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($records as $row)
                @php($record = optional($row->user)->student_record)
                <tr data-status="{{ $row->status }}" data-search="{{ strtolower((optional($row->user)->name ?: $row->user_id).' '.optional(optional($record)->my_class)->name.' '.optional(optional($record)->section)->name.' '.optional($row->markedBy)->name.' '.($row->mode ?? 'manual').' '.$row->remarks) }}">
                    <td>{{ optional($row->user)->name ?: $row->user_id }}</td>
                    <td>{{ optional(optional($record)->my_class)->name }} {{ optional(optional($record)->section)->name }}</td>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'present' ? 'success' : ($row->status === 'absent' ? 'danger' : 'warning') }}">{{ ucfirst($row->status) }}</span></td>
                    <td><span class="badge badge-{{ ($row->mode ?? 'manual') === 'facial' ? 'primary' : 'secondary' }}">{{ ucfirst($row->mode ?? 'manual') }}</span></td>
                    <td>{{ $row->facial_verified ? 'Yes' : 'No' }}</td>
                    <td>{{ optional($row->markedBy)->name ?: '-' }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">No attendance records found for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        function filterTable(tableId) {
            const table = document.getElementById(tableId);
            if (!table) return;

            const search = (document.querySelector(`.table-search[data-table-target="${tableId}"]`)?.value || '').toLowerCase();
            const status = document.querySelector(`.table-status-filter[data-table-target="${tableId}"]`)?.value || '';

            table.querySelectorAll('tbody tr').forEach(function (row) {
                if (!row.dataset.search && !row.dataset.status) return;

                const matchesSearch = !search || (row.dataset.search || row.textContent.toLowerCase()).includes(search);
                const matchesStatus = !status || row.dataset.status === status;
                row.style.display = matchesSearch && matchesStatus ? '' : 'none';
            });
        }

        document.querySelectorAll('.table-search, .table-status-filter').forEach(function (control) {
            control.addEventListener('input', function () { filterTable(this.dataset.tableTarget); });
            control.addEventListener('change', function () { filterTable(this.dataset.tableTarget); });
        });

        document.querySelectorAll('#mark-attendance-table select[name^="attendance"]').forEach(function (select) {
            select.addEventListener('change', function () {
                this.closest('tr').dataset.status = this.value;
                filterTable('mark-attendance-table');
            });
        });

        document.querySelectorAll('.save-single-manual').forEach(function (button) {
            button.addEventListener('click', function () {
                const row = this.closest('tr');
                const form = document.getElementById('single-manual-form');

                document.getElementById('single_attendance_date').value = document.getElementById('manual_attendance_date').value;
                document.getElementById('single_student_id').value = row.dataset.studentId;
                document.getElementById('single_status').value = row.querySelector('.manual-status').value;
                document.getElementById('single_remarks').value = row.querySelector('.manual-remarks').value;
                form.submit();
            });
        });

        const fakeScanButton = document.getElementById('fake-ai-scan');
        const clearScanButton = document.getElementById('clear-ai-scan');

        if (fakeScanButton) {
            fakeScanButton.addEventListener('click', function () {
                document.querySelectorAll('#facial-attendance-table tbody tr').forEach(function (row) {
                    const checkbox = row.querySelector('.facial-check');
                    const confidenceInput = row.querySelector('.facial-confidence-input');
                    const badge = row.querySelector('.ai-confidence');
                    if (!checkbox || !confidenceInput || !badge) return;

                    const recognized = Math.random() > 0.25;
                    const confidence = Math.floor(Math.random() * 16) + 84;
                    checkbox.checked = recognized;
                    confidenceInput.value = recognized ? confidence : '';
                    badge.textContent = recognized ? confidence + '% match' : 'Not recognized';
                    badge.className = 'badge ai-confidence ' + (recognized ? 'badge-success' : 'badge-warning');
                });
            });
        }

        if (clearScanButton) {
            clearScanButton.addEventListener('click', function () {
                document.querySelectorAll('#facial-attendance-table tbody tr').forEach(function (row) {
                    const checkbox = row.querySelector('.facial-check');
                    const confidenceInput = row.querySelector('.facial-confidence-input');
                    const badge = row.querySelector('.ai-confidence');
                    if (!checkbox || !confidenceInput || !badge) return;

                    checkbox.checked = false;
                    confidenceInput.value = '';
                    badge.textContent = 'Not scanned';
                    badge.className = 'badge badge-light ai-confidence';
                });
            });
        }
    });
</script>

@endsection
