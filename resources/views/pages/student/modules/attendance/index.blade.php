@extends('layouts.master')
@section('page_title', 'Attendance')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Mark Your Attendance</h6></div>
    <div class="card-body">
        <form action="{{ route('student.attendance.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="col-md-4 form-group">
                    <label>Date</label>
                    <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4 form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        @foreach(['present','absent','late','excused'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group">
                    <label>Remarks</label>
                    <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
                </div>
            </div>
            <button class="btn btn-primary">Save Attendance</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Your Attendance History</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Date</th><th>Status</th><th>Remarks</th></tr></thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="text-center">No attendance marked yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>
@endsection
