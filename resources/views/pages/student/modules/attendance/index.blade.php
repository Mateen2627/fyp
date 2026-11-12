@extends('layouts.master')
@section('page_title', 'My Attendance')
@section('content')
<div class="card"><div class="card-header"><h5>Today's Classes and Face Check-in</h5></div><div class="card-body">
<p>Check in during your scheduled lesson, starting 15 minutes before class. Captures remain pending until the face verification service is connected. Pending captures do not mark you present.</p>
<div id="camera-message" class="alert alert-info">Select a class, enable your camera, then capture and submit. Your browser needs camera permission.</div>
<video id="attendance-camera" autoplay playsinline muted style="width:100%;max-width:420px;border-radius:8px" hidden></video>
<canvas id="attendance-canvas" hidden></canvas>
<form id="check-in-form" method="post" enctype="multipart/form-data" action="{{ route('student.attendance.check_in') }}">@csrf
<div class="form-group"><label for="lesson">Lesson</label><select id="lesson" class="form-control" name="time_table_id" required><option value="">Select today's lesson</option>
@foreach($lessons as $lesson)<option value="{{ $lesson->id }}" {{ $checkIns->has($lesson->id) ? 'disabled' : '' }}>{{ optional($lesson->subject)->name }} · {{ optional($lesson->time_slot)->full }} {{ $checkIns->has($lesson->id) ? '('.$checkIns[$lesson->id]->status.')' : '' }}</option>@endforeach
</select></div>
<button type="button" class="btn btn-light" id="enable-camera">Enable Camera</button>
<button type="button" class="btn btn-primary" id="capture-attendance" disabled>Capture and Check In</button>
</form>
@if($lessons->isEmpty())<p class="text-muted mt-3">No lessons scheduled for your section today. Your coordinator manages the class timetable.</p>@endif
@if($checkIns->isNotEmpty())<h6 class="mt-3">Today's check-ins</h6><ul>@foreach($checkIns as $entry)<li>{{ optional(optional($entry->lesson)->subject)->name }}: {{ ucfirst($entry->status) }}. {{ $entry->message }}</li>@endforeach</ul>@endif
</div></div>
<div class="card"><div class="card-header"><h5>My Recorded Attendance</h5></div><div class="card-body"><p>{{ $present }} present / {{ $total }} recorded entries.</p><form method="get" class="form-row align-items-end"><div class="col-md-4 form-group"><label>From</label><input name="from" type="date" class="form-control" value="{{ request('from') }}"></div><div class="col-md-4 form-group"><label>To</label><input name="to" type="date" class="form-control" value="{{ request('to') }}"></div><div class="col-md-4 form-group"><button class="btn btn-primary">Filter</button></div></form></div>
<div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead><tbody>@forelse($records as $row)<tr><td>{{ $row->attendance_date->format('Y-m-d') }}</td><td>{{ optional($row->course)->title ?: 'Legacy daily attendance' }}</td><td>{{ ucfirst($row->status) }}</td></tr>@empty<tr><td colspan="3">No recorded attendance.</td></tr>@endforelse</tbody></table></div><div class="card-footer">{{ $records->links() }}</div></div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const video = document.getElementById('attendance-camera'), message = document.getElementById('camera-message');
    let stream;
    function stop() { if (stream) stream.getTracks().forEach(track => track.stop()); }
    document.getElementById('enable-camera').addEventListener('click', async function () {
        try {
            if (!navigator.mediaDevices?.getUserMedia) throw new Error('Camera access requires HTTPS or localhost and a supported browser.');
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false });
            video.srcObject = stream; video.hidden = false; await video.play();
            document.getElementById('capture-attendance').disabled = false;
            message.textContent = 'Camera ready. Select your lesson and capture your face.';
        } catch (error) { message.textContent = error.message || 'Unable to access the camera.'; }
    });
    document.getElementById('capture-attendance').addEventListener('click', function () {
        const form = document.getElementById('check-in-form');
        if (!form.reportValidity() || !video.videoWidth) return;
        const button = this, canvas = document.getElementById('attendance-canvas');
        canvas.width = video.videoWidth; canvas.height = video.videoHeight; canvas.getContext('2d').drawImage(video, 0, 0);
        button.disabled = true;
        canvas.toBlob(async function (blob) {
            try {
                if (!blob) throw new Error('Capture failed. Please try again.');
                const payload = new FormData(form); payload.append('capture', blob, 'check-in.jpg');
                const response = await fetch(form.action, { method: 'POST', body: payload, headers: { 'Accept': 'application/json' } });
                const body = await response.json();
                if (!response.ok) throw new Error(Object.values(body.errors || {}).flat().join(' ') || body.message || 'Check-in failed.');
                stop(); window.location.reload();
            } catch (error) { message.textContent = error.message; button.disabled = false; }
        }, 'image/jpeg', 0.9);
    });
    window.addEventListener('pagehide', stop);
});
</script>
@endsection
