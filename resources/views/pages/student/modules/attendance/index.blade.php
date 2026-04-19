@extends('layouts.master')
@section('page_title', 'Attendance')
@section('content')
<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Register Face (Dummy)</h6></div>
    <div class="card-body">
        <form action="{{ route('student.attendance.register_face') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="col-md-5 form-group"><label>Face Token (dummy embedding)</label><input type="text" name="face_token" class="form-control" value="{{ old('face_token', optional($faceProfile)->face_token) }}" required></div>
                <div class="col-md-5 form-group"><label>Face Image</label><input type="file" name="face_image" class="form-control"></div>
                <div class="col-md-2 form-group d-flex align-items-end"><button class="btn btn-secondary w-100">Save Face</button></div>
            </div>
            @if($faceProfile)
                <small class="text-success">Face profile registered{{ $faceProfile->face_image_path ? ' (image uploaded)' : '' }}.</small>
            @endif
        </form>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header"><h6 class="mb-0">Mark Attendance</h6></div>
    <div class="card-body">
        <form action="{{ route('student.attendance.store') }}" method="post">
            @csrf
            <div class="form-row">
                <div class="col-md-3 form-group">
                    <label>Date</label>
                    <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', now()->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3 form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        @foreach(['present','absent','late','excused'] as $status)
                            <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 form-group">
                    <label>Mode</label>
                    <select name="mode" id="attendance_mode" class="form-control" required>
                        <option value="manual">Manual</option>
                        <option value="facial">Facial</option>
                    </select>
                </div>
                <div class="col-md-3 form-group" id="face_token_box" style="display:none;">
                    <label>Face Token (for dummy match)</label>
                    <input type="text" name="face_token" class="form-control" value="{{ old('face_token') }}">
                </div>
            </div>
            <div class="form-group">
                <label>Remarks</label>
                <input type="text" name="remarks" class="form-control" value="{{ old('remarks') }}">
            </div>
            <button class="btn btn-primary">Save Attendance</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h6 class="mb-0">Your Attendance History</h6></div>
    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Date</th><th>Status</th><th>Mode</th><th>Facial Verified</th><th>Remarks</th></tr></thead>
            <tbody>
            @forelse($records as $row)
                <tr>
                    <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($row->status) }}</td>
                    <td>{{ ucfirst($row->mode ?? 'manual') }}</td>
                    <td>{{ ($row->facial_verified ?? 0) ? 'Yes' : 'No' }}</td>
                    <td>{{ $row->remarks ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No attendance marked yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>

<script>
document.getElementById('attendance_mode').addEventListener('change', function(){
    document.getElementById('face_token_box').style.display = this.value === 'facial' ? 'block' : 'none';
});
</script>
@endsection
