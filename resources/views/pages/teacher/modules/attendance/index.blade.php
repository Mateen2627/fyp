@extends('layouts.master')
@section('page_title', 'Student Attendance')
@section('content')

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

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Mark Attendance</h6>
    </div>
    <div class="card-body">
        <form method="post" action="{{ route('teacher.attendance.store') }}">
            @csrf
            <input type="hidden" name="class_id" value="{{ $selectedClassId }}">
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">Attendance Date</label>
                <div class="col-lg-4">
                    <input type="date" name="attendance_date" value="{{ $markDate }}" class="form-control" required>
                </div>
                <div class="col-lg-6 text-right">
                    <button type="submit" class="btn btn-success">Save Attendance</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Student</th>
                        <th>ADM No</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Remarks</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $student)
                        <tr>
                            <td>{{ optional($student->user)->name }}</td>
                            <td>{{ $student->adm_no }}</td>
                            <td>{{ optional($student->my_class)->name }} {{ optional($student->section)->name }}</td>
                            <td>
                                <select name="attendance[{{ $student->user_id }}]" class="form-control" required>
                                    @php($existingAttendance = $dailyAttendance->get($student->user_id))
                                    <option value="present" {{ optional($existingAttendance)->status === 'present' ? 'selected' : '' }}>Present</option>
                                    <option value="absent" {{ optional($existingAttendance)->status === 'absent' ? 'selected' : '' }}>Absent</option>
                                    <option value="late" {{ optional($existingAttendance)->status === 'late' ? 'selected' : '' }}>Late</option>
                                    <option value="excused" {{ optional($existingAttendance)->status === 'excused' ? 'selected' : '' }}>Excused</option>
                                </select>
                            </td>
                            <td><input type="text" name="remarks[{{ $student->user_id }}]" value="{{ optional($existingAttendance)->remarks }}" class="form-control" placeholder="Optional remarks"></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No students found for your assigned classes.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header header-elements-inline">
        <h6 class="card-title">Percentage by Student</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
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
                <tr>
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
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Date</th>
                <th>Status</th>
                <th>Marked By</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    @php($record = optional($row->user)->student_record)
                    <td>{{ optional($row->user)->name ?: $row->user_id }}</td>
                    <td>{{ optional(optional($record)->my_class)->name }} {{ optional(optional($record)->section)->name }}</td>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td><span class="badge badge-{{ $row->status === 'present' ? 'success' : ($row->status === 'absent' ? 'danger' : 'warning') }}">{{ ucfirst($row->status) }}</span></td>
                    <td>{{ optional($row->markedBy)->name ?: '-' }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">No attendance records found for this period.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>

@endsection
