@extends('layouts.master')
@section('page_title', 'Class Progress')
@section('content')
<div class="card"><div class="card-header"><h5 class="mb-0">{{ $class->name }} — Class Progress <span class="badge badge-light">Read only</span></h5></div><div class="card-body">
<form method="get" class="form-row align-items-end">
    <div class="col-md-3 form-group"><label>Section</label><select name="section_id" class="form-control"><option value="">All sections</option>@foreach($sections as $section)<option value="{{ $section->id }}" {{ $sectionId == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>@endforeach</select></div>
    <div class="col-md-3 form-group"><label>Attendance from</label><input type="date" name="from" value="{{ request('from') }}" class="form-control"></div>
    <div class="col-md-3 form-group"><label>Attendance to</label><input type="date" name="to" value="{{ request('to') }}" class="form-control"></div>
    <div class="col-md-3 form-group"><button class="btn btn-primary">Apply Filter</button></div>
</form>
<p>{{ $students->count() }} students · {{ $assignments->count() }} assignments · {{ $quizzes->count() }} quizzes · {{ $presentTotal }} present / {{ $attendanceTotal }} recorded attendance entries. Pending face captures are excluded.</p>
</div></div>
@foreach(['assignments' => $assignments, 'quizzes' => $quizzes] as $kind => $activities)
<div class="card"><div class="card-header"><h6>{{ ucfirst($kind) }}</h6></div><div class="table-responsive"><table class="table"><thead><tr><th>Title</th><th>Subject</th><th>Teacher</th><th>Responses</th><th>Details</th></tr></thead><tbody>
@forelse($activities as $activity)<tr><td>{{ $activity->title }}</td><td>{{ optional($activity->course)->title }}</td><td>{{ optional($activity->teacher)->name }}</td><td>{{ $kind === 'assignments' ? $activity->submissions_count : $activity->attempts_count }}</td><td><a href="{{ route('academic.progress.activity', [$kind, $activity->id]) }}">View Submissions and Marks</a></td></tr>@empty<tr><td colspan="5">No {{ $kind }} yet.</td></tr>@endforelse
</tbody></table></div></div>@endforeach
<div class="card"><div class="card-header"><h6>Attendance</h6></div><div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Student</th><th>Subject</th><th>Status</th></tr></thead><tbody>
@forelse($attendance as $row)<tr><td>{{ $row->attendance_date->format('Y-m-d') }}</td><td>{{ optional($row->user)->name ?: 'Archived student' }}</td><td>{{ optional($row->course)->title ?: 'Legacy daily attendance' }}</td><td>{{ ucfirst($row->status) }}</td></tr>@empty<tr><td colspan="4">No recorded attendance.</td></tr>@endforelse</tbody></table></div><div class="card-footer">{{ $attendance->links() }}</div></div>
<div class="card"><div class="card-header"><h6>Pending Face Verification</h6></div><div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Student</th><th>Subject</th><th>Status</th></tr></thead><tbody>
@forelse($pending as $row)<tr><td>{{ $row->attendance_date->format('Y-m-d') }}</td><td>{{ optional($row->user)->name ?: 'Archived student' }}</td><td>{{ optional(optional($row->lesson)->subject)->name }}</td><td>Pending — not counted as present</td></tr>@empty<tr><td colspan="4">No pending check-ins.</td></tr>@endforelse</tbody></table></div><div class="card-footer">{{ $pending->links() }}</div></div>
<div class="card"><div class="card-header"><h6>Mid and Final Marks</h6></div><div class="table-responsive"><table class="table"><thead><tr><th>Student</th><th>Subject</th><th>Session</th><th>Mid</th><th>Final</th><th>Total</th></tr></thead><tbody>
@forelse($marks as $mark)<tr><td>{{ optional($mark->student)->name }}</td><td>{{ optional($mark->subject)->name }}</td><td>{{ $mark->academic_year }}</td><td>{{ $mark->mid_marks }}</td><td>{{ $mark->final_marks }}</td><td>{{ $mark->total_marks }}</td></tr>@empty<tr><td colspan="6">No marks entered.</td></tr>@endforelse</tbody></table></div></div>
<div class="card"><div class="card-header"><h6>Class Roster</h6></div><div class="table-responsive"><table class="table"><thead><tr><th>Admission No.</th><th>Student</th><th>Section</th></tr></thead><tbody>@foreach($students as $student)<tr><td>{{ $student->adm_no }}</td><td>{{ optional($student->user)->name }}</td><td>{{ optional($student->section)->name }}</td></tr>@endforeach</tbody></table></div></div>
@endsection
