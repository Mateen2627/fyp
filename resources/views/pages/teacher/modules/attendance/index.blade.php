@extends('layouts.master')
@section('page_title', 'Subject Attendance')
@section('content')
<div class="card"><div class="card-header"><h5>Subject Attendance</h5></div><div class="card-body"><p>Attendance for your assigned subjects and sections. Students check in; the server verifies face captures.</p>
<form method="get" class="form-row align-items-end">
<div class="col-md-5 form-group"><label>Subject</label><select name="course_id" class="form-control"><option value="">All assigned subjects</option>@foreach($pairs->unique('course_id') as $pair)<option value="{{ $pair->course_id }}" {{ request('course_id') == $pair->course_id ? 'selected' : '' }}>{{ optional($pair->my_class)->name }} — {{ optional($pair->course)->title }}</option>@endforeach</select></div>
<div class="col-md-2 form-group"><label>From</label><input type="date" name="from" class="form-control" value="{{ request('from') }}"></div>
<div class="col-md-2 form-group"><label>To</label><input type="date" name="to" class="form-control" value="{{ request('to') }}"></div><div class="col-md-3 form-group"><button class="btn btn-primary">Filter Attendance</button></div></form>
<p class="mb-0">{{ $present }} present / {{ $total }} recorded entries. Pending face check-ins are not counted as present.</p></div>
<div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Student</th><th>Subject</th><th>Status</th><th>Mode</th></tr></thead><tbody>@forelse($records as $row)<tr><td>{{ $row->attendance_date->format('Y-m-d') }}</td><td>{{ optional($row->user)->name ?: 'Archived student' }}</td><td>{{ optional($row->course)->title }}</td><td>{{ ucfirst($row->status) }}</td><td>{{ $row->mode }}</td></tr>@empty<tr><td colspan="5">No verified attendance for your subjects in this period.</td></tr>@endforelse</tbody></table></div><div class="card-footer">{{ $records->links() }}</div></div>
@endsection
