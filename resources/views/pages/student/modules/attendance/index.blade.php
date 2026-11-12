@extends('layouts.master')
@section('page_title', 'My Attendance')
@section('content')
<div class="card">
    <div class="card-header"><h5 class="mb-0">Mark Attendance</h5></div>
    <div class="card-body">
        <p>Select your class and capture a live webcam photo while sharing your device location. Attendance is available within {{ round(config('services.attendance_geofence.radius_meters', 100)) }} meters of campus, from 15 minutes before the lesson until it ends.</p>
        @if(!$referenceReady)
            <div class="alert alert-warning">Your official reference photo is missing. Ask your department coordinator or an administrator to upload it before marking attendance. Changing your profile picture does not change your official photo.</div>
        @endif
        <div id="camera-message" class="alert alert-info" role="status" aria-live="polite">Select a lesson, enable your camera, and allow precise location when you capture your face.</div>
        <div id="camera-frame" style="position:relative;width:100%;max-width:480px;line-height:0">
            <video id="attendance-camera" autoplay playsinline muted aria-label="Live webcam preview" style="width:100%;border-radius:8px" hidden></video>
            <canvas id="attendance-canvas" aria-label="Captured attendance photo" style="width:100%;border-radius:8px" hidden></canvas>
            <canvas id="face-box-overlay" aria-hidden="true" style="position:absolute;inset:0;width:100%;height:100%;pointer-events:none" hidden></canvas>
        </div>
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
    const faceOverlay = document.getElementById('face-box-overlay');
    const captureCanvas = document.getElementById('attendance-canvas');
    const message = document.getElementById('camera-message');
    const enable = document.getElementById('enable-camera');
    const capture = document.getElementById('capture-attendance');
    const stopButton = document.getElementById('stop-camera');
    const lesson = document.getElementById('lesson');
    const ready = form.dataset.referenceReady === '1';
    let stream = null, busy = false;
    let lastCaptureSize = { width: 0, height: 0 };

    function show(text, state = 'info') {
        message.className = 'alert alert-' + state;
        message.textContent = text;
    }
    function getCurrentPosition() {
        return new Promise(function (resolve, reject) {
            if (!navigator.geolocation) {
                reject(new Error('Location access is not supported by this browser.'));
                return;
            }
            navigator.geolocation.getCurrentPosition(resolve, function (error) {
                const messages = {
                    1: 'Location permission was denied. Allow precise location in your browser, then try again.',
                    2: 'Your device could not determine its location. Turn on location services and try again.',
                    3: 'Getting your location took too long. Move to an open area and try again.'
                };
                reject(new Error(messages[error.code] || 'Unable to get your device location.'));
            }, { enableHighAccuracy: true, maximumAge: 0, timeout: 20000 });
        });
    }
    function updateButtons() {
        capture.disabled = busy || !ready || !stream || !video.videoWidth || !lesson.value;
        enable.disabled = busy || !ready || lesson.options.length < 2;
        stopButton.hidden = !stream;
        stopButton.disabled = busy;
    }
    function closeLoadingAlert() {
        if (window.Swal && typeof window.Swal.close === 'function') window.Swal.close();
    }
    function showLoadingAlert() {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            window.Swal.fire({
                title: 'Verifying your face',
                text: 'The face model may take a few minutes the first time it runs. Please keep this page open.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                onOpen: function () { window.Swal.showLoading(); }
            });
        }
    }
    function showFaceResultAlert(title, text, type) {
        if (window.Swal && typeof window.Swal.fire === 'function') {
            return window.Swal.fire({ title: title, text: text, type: type, confirmButtonText: 'OK' });
        }
        return null;
    }
    function drawFaceBox(box, verified) {
        const values = box && ['x', 'y', 'w', 'h'].map(key => Number(box[key]));
        if (!values || values.some(value => !Number.isFinite(value)) || !lastCaptureSize.width) return;
        faceOverlay.width = captureCanvas.width;
        faceOverlay.height = captureCanvas.height;
        const context = faceOverlay.getContext('2d');
        context.clearRect(0, 0, faceOverlay.width, faceOverlay.height);
        context.strokeStyle = verified ? '#16a34a' : '#f59e0b';
        context.lineWidth = Math.max(3, Math.round(captureCanvas.width / 160));
        context.strokeRect(values[0], values[1], values[2], values[3]);
        faceOverlay.hidden = false;
    }
    function freezeCapturePreview(box, verified) {
        if (video.pause) video.pause();
        video.hidden = true;
        captureCanvas.hidden = false;
        if (box) drawFaceBox(box, verified);
        else faceOverlay.hidden = true;
        stop(true);
    }
    function resultSummary(body) {
        const parts = [];
        if (body.match_confidence !== null && body.match_confidence !== undefined && Number.isFinite(Number(body.match_confidence))) parts.push('Match confidence: ' + Number(body.match_confidence).toFixed(1) + '% (not model accuracy)');
        if (Number.isFinite(Number(body.distance)) && Number.isFinite(Number(body.threshold))) parts.push('distance ' + Number(body.distance).toFixed(4) + ' / threshold ' + Number(body.threshold).toFixed(2));
        if (body.spoof_check === 'passed') parts.push('anti-spoof check passed');
        return parts.join(' | ');
    }
    function stop(preservePreview = false) {
        if (stream) stream.getTracks().forEach(track => track.stop());
        stream = null; video.srcObject = null; video.hidden = true;
        if (!preservePreview) {
            captureCanvas.hidden = true;
            faceOverlay.hidden = true;
            faceOverlay.getContext('2d').clearRect(0, 0, faceOverlay.width, faceOverlay.height);
        }
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
            captureCanvas.hidden = true;
            video.srcObject = stream; video.hidden = false; await video.play();
            faceOverlay.hidden = true;
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
        show('Getting your location and checking your face. Please allow precise location and keep this page open.');
        showLoadingAlert();
        const locationPromise = getCurrentPosition().then(
            function (position) { return { position: position }; },
            function (error) { return { error: error }; }
        );
        const scale = Math.min(1, 1280 / video.videoWidth, 1280 / video.videoHeight);
        captureCanvas.width = Math.round(video.videoWidth * scale);
        captureCanvas.height = Math.round(video.videoHeight * scale);
        lastCaptureSize = { width: captureCanvas.width, height: captureCanvas.height };
        captureCanvas.getContext('2d').drawImage(video, 0, 0, captureCanvas.width, captureCanvas.height);
        captureCanvas.toBlob(async function (blob) {
            let body = null;
            try {
                if (!blob) throw new Error('The photo could not be captured. Please try again.');
                const locationResult = await locationPromise;
                if (locationResult.error) throw locationResult.error;
                const position = locationResult.position;
                const payload = new FormData(form);
                payload.append('live_image', blob, 'live.jpg');
                payload.append('latitude', String(position.coords.latitude));
                payload.append('longitude', String(position.coords.longitude));
                payload.append('location_accuracy', String(position.coords.accuracy));
                const controller = new AbortController();
                const requestTimeout = window.setTimeout(() => controller.abort(), 330000);
                let response;
                try {
                    response = await fetch(form.action, {
                    method: 'POST', body: payload, credentials: 'same-origin',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name="_token"]').value },
                    signal: controller.signal
                    });
                } finally {
                    window.clearTimeout(requestTimeout);
                }
                if (response.status === 401 || response.status === 419 || response.redirected) throw new Error('Your session expired. Refresh the page and sign in again.');
                if (response.status === 429) throw new Error('Too many attempts. Wait a minute before trying again.');
                body = await response.json().catch(() => null);
                if (body && (body.verified !== undefined || body.face_box)) freezeCapturePreview(body.face_box, body.verified === true);
                if (!response.ok || body?.verified !== true) {
                    if (body?.code === 'attendance_already_marked') {
                        lesson.selectedOptions[0].disabled = true;
                        lesson.value = ''; stop();
                    }
                    const reason = body?.code ? ' (' + body.code + ')' : '';
                    const details = body ? resultSummary(body) : '';
                    throw new Error((body?.message || 'Face verification could not be completed. Attendance was not marked.') + reason + (details ? '\n' + details : ''));
                }
                const summary = resultSummary(body);
                show((body.message || 'Attendance marked successfully.') + (summary ? ' ' + summary : ''), 'success');
                if (typeof window.flash === 'function') {
                    window.flash({ msg: body.message || 'Attendance marked successfully.', type: 'success' });
                }
                closeLoadingAlert();
                stop(true);
                const resultAlert = showFaceResultAlert('Attendance marked', (body.message || 'Face verified and attendance recorded.') + (summary ? '\n' + summary : ''), 'success');
                if (resultAlert && typeof resultAlert.then === 'function') resultAlert.then(() => window.location.reload());
                else window.setTimeout(() => window.location.reload(), 5000);
            } catch (error) {
                closeLoadingAlert();
                const errorMessage = error.name === 'AbortError'
                    ? 'Face verification took too long. The model may still be warming up; restart FastAPI and try again.'
                    : (error.message || 'Unable to reach the server. Please try again.');
                show(errorMessage, 'danger');
                showFaceResultAlert('Attendance not marked', errorMessage, 'error');
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
