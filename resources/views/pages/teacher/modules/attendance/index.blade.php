@extends('layouts.master')
@section('page_title', 'Student Attendance')
@section('content')
<div class="card"><div class="table-responsive"><table class="table table-striped mb-0"><thead><tr><th>Student ID</th><th>Date</th><th>Status</th><th>Mode</th><th>Facial Verified</th><th>Remarks</th></tr></thead><tbody>
@forelse($records as $row)
<tr><td>{{ $row->user_id }}</td><td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td><td>{{ ucfirst($row->status) }}</td><td>{{ ucfirst($row->mode ?? 'manual') }}</td><td>{{ ($row->facial_verified ?? 0) ? 'Yes' : 'No' }}</td><td>{{ $row->remarks ?: '-' }}</td></tr>
@empty<tr><td colspan="6" class="text-center">No attendance records yet.</td></tr>@endforelse
</tbody></table></div><div class="card-footer">{{ $records->links() }}</div></div>
@endsection
