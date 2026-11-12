@extends('layouts.master')
@section('page_title', 'My Attendance')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Mark Attendance</h5></div>
    <div class="card-body">
        <p>Select your class and capture a live webcam photo. Attendance opens 15 minutes before the lesson and closes when it ends.</p>
        @if(!$referenceReady)
            <div class="alert alert-warning">Your official reference photo is missing. Ask your department coordinator or an administrator to upload it before marking attendance. Changing your profile picture does not change your official photo.</div>
        @endif
        <div id="camera-message" class="alert alert-info" role="status" aria-live="polite">Select a lesson, enable your camera, then capture your face.</div>
        <video id="attendance-camera" autoplay playsinline muted aria-label="Live webcam preview" style="width:100%;max-width:480px;border-radius:8px" hidden></video>
        <canvas id="attendance-canvas" hidden></canvas>
        <form id="check-in-form" method="post" enctype="multipart/form-data" action="{{ route('attendance.mark-face') }}" data-reference-ready="{{ $referenceReady ? '1' : '0' }}">
            @csrf
            <div class="form-group">
                <label for="lesson">Today's lesson</label>
                <select id="lesson" class="form-control" name="time_table_id" required>
                    <option value="">Select a lesson</option>
                    @foreach($lessons as $lesson)
                        <option value="{{ $lesson->id }}" {{ $markedLessonIds->contains($lesson->id) ? 'disabled' : '' }}>{{ optional($lesson->subject)->name }} - {{ optional($lesson->time_slot)->full }}{{ $markedLessonIds->contains($lesson->id) ? ' (Attendance marked)' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <button type="button" class="btn btn-light" id="enable-camera" {{ !$referenceReady || $lessons->isEmpty() ? 'disabled' : '' }}>Enable Camera</button>
            <button type="button" class="btn btn-primary" id="capture-attendance" disabled>Capture and Mark Attendance</button>
            <button type="button" class="btn btn-link" id="stop-camera" hidden>Stop Camera</button>
        </form>
        @if($lessons->isEmpty())<p class="text-muted mt-3">No lessons are scheduled for your section today. Your coordinator manages the timetable.</p>@endif
        @if($checkIns->isNotEmpty())
            <h6 class="mt-3">Today's Check-ins</h6>
            <ul>@foreach($checkIns as $entry)<li>{{ optional(optional($entry->lesson)->subject)->name }}: {{ ucfirst($entry->status) }}. {{ $entry->status === 'pending' ? 'Please capture again to complete face verification.' : $entry->message }}</li>@endforeach</ul>
        @endif
    </div>
</div>
<div class="card">
    <div class="card-header"><h5>My Recorded Attendance</h5></div>
    <div class="card-body">
        <p>{{ $present }} present / {{ $total }} recorded entries.</p>
        <form method="get" class="form-row align-items-end">
            <div class="col-md-4 form-group"><label for="attendance-from">From</label><input id="attendance-from" name="from" type="date" class="form-control" value="{{ request('from') }}"></div>
            <div class="col-md-4 form-group"><label for="attendance-to">To</label><input id="attendance-to" name="to" type="date" class="form-control" value="{{ request('to') }}"></div>
            <div class="col-md-4 form-group"><button class="btn btn-primary">Filter</button></div>
        </form>
    </div>
    <div class="table-responsive"><table class="table"><thead><tr><th>Date</th><th>Subject</th><th>Status</th></tr></thead><tbody>
        @forelse($records as $row)<tr><td>{{ $row->attendance_date->format('Y-m-d') }}</td><td>{{ optional($row->course)->title ?: 'Legacy daily attendance' }}</td><td>{{ ucfirst($row->status) }}</td></tr>
        @empty<tr><td colspan="3">No recorded attendance.</td></tr>@endforelse
    </tbody></table></div>
    <div class="card-footer">{{ $records->links() }}</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('check-in-form');
    const video = document.getElementById('attendance-camera');
    const message = document.getElementById('camera-message');
    const enable = document.getElementById('enable-camera');
    const capture = document.getElementById('capture-attendance');
    const stopButton = document.getElementById('stop-camera');
    const lesson = document.getElementById('lesson');
    const ready = form.dataset.referenceReady === '1';
    let stream = null, busy = false;

    function show(text, state = 'info') {
        message.className = 'alert alert-' + state;
        message.textContent = text;
    }
    function updateButtons() {
        capture.disabled = busy || !ready || !stream || !video.videoWidth || !lesson.value;
        enable.disabled = busy || !ready || lesson.options.length < 2;
        stopButton.hidden = !stream;
        stopButton.disabled = busy;
    }
    function stop() {
        if (stream) stream.getTracks().forEach(track => track.stop());
        stream = null; video.srcObject = null; video.hidden = true;
        updateButtons();
    }
    lesson.addEventListener('change', updateButtons);
    video.addEventListener('loadeddata', updateButtons);
    stopButton.addEventListener('click', function () { stop(); show('Camera stopped.'); });
    enable.addEventListener('click', async function () {
        stop();
        try {
            if (!navigator.mediaDevices?.getUserMedia) throw new Error('Camera access requires HTTPS or localhost and a supported browser.');
            stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            video.srcObject = stream; video.hidden = false; await video.play();
            show('Camera ready. Face the camera clearly, then capture your photo.');
            updateButtons();
        } catch (error) {
            stop();
            const messages = {
                NotAllowedError: 'Camera permission was denied. Allow camera access in your browser, then try again.',
                NotFoundError: 'No webcam was found. Connect a camera and try again.',
                NotReadableError: 'The webcam is busy. Close other apps using it and try again.'
            };
            show(messages[error.name] || error.message || 'Unable to open the camera.', 'danger');
        }
    });
    capture.addEventListener('click', function () {
        if (busy || !stream || !video.videoWidth || !form.reportValidity()) return;
        busy = true; capture.textContent = 'Verifying your face...'; updateButtons();
        show('Checking your face and verifying attendance. Please wait.');
        const canvas = document.getElementById('attendance-canvas');
        const scale = Math.min(1, 1280 / video.videoWidth, 1280 / video.videoHeight);
        canvas.width = Math.round(video.videoWidth * scale);
        canvas.height = Math.round(video.videoHeight * scale);
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(async function (blob) {
            try {
                if (!blob) throw new Error('The photo could not be captured. Please try again.');
                const payload = new FormData(form);
                payload.append('live_image', blob, 'live.jpg');
                const response = await fetch(form.action, {
                    method: 'POST', body: payload, credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value }
                });
                if (response.status === 401 || response.status === 419 || response.redirected) throw new Error('Your session expired. Refresh the page and sign in again.');
                if (response.status === 429) throw new Error('Too many attempts. Wait a minute before trying again.');
                const body = await response.json().catch(() => null);
                if (!response.ok || body?.verified !== true) {
                    if (body?.code === 'attendance_already_marked') {
                        lesson.selectedOptions[0].disabled = true;
                        lesson.value = ''; stop();
                    }
                    const reason = body?.code ? ' (' + body.code + ')' : '';
                    throw new Error((body?.message || 'Face verification could not be completed. Attendance was not marked.') + reason);
                }
                show(body.message || 'Attendance marked successfully.', 'success');
                stop();
                window.setTimeout(() => window.location.reload(), 1400);
            } catch (error) {
                show(error.message || 'Unable to reach the server. Please try again.', 'danger');
            } finally {
                busy = false; capture.textContent = 'Capture and Mark Attendance'; updateButtons();
            }
        }, 'image/jpeg', 0.9);
    });
    window.addEventListener('pagehide', stop);
    updateButtons();
});
</script>
@endsection
