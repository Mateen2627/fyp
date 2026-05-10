@php($authStudent = Auth::user())

<style>
    .student-photo-reminder-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.62);
        z-index: 2000;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 18px;
    }

    .student-photo-reminder-backdrop.is-visible {
        display: flex;
    }

    .student-photo-reminder-card {
        width: min(520px, 100%);
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 30px 80px rgba(15, 23, 42, 0.28);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .student-photo-reminder-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        color: #fff;
        padding: 22px 24px;
        position: relative;
    }

    .student-photo-reminder-header h5 {
        color: #fff;
        font-weight: 800;
        margin: 0 36px 6px 0;
    }

    .student-photo-reminder-close {
        position: absolute;
        top: 16px;
        right: 16px;
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.18);
        color: #fff;
        cursor: pointer;
    }

    .student-photo-reminder-body {
        padding: 24px;
    }

    .student-photo-reminder-preview {
        display: flex;
        gap: 16px;
        align-items: center;
        padding: 14px;
        border: 1px dashed #c7d2fe;
        border-radius: 16px;
        background: #f8fafc;
        margin-bottom: 18px;
    }

    .student-photo-reminder-preview img {
        width: 82px;
        height: 82px;
        border-radius: 18px;
        object-fit: cover;
        border: 3px solid #fff;
        box-shadow: 0 10px 20px rgba(15, 23, 42, 0.14);
    }
</style>

<div class="student-photo-reminder-backdrop" id="student-photo-reminder" data-repeat-ms="10000">
    <div class="student-photo-reminder-card">
        <div class="student-photo-reminder-header">
            <button type="button" class="student-photo-reminder-close" id="student-photo-reminder-close" aria-label="Close reminder">&times;</button>
            <h5><i class="fas fa-camera"></i> Student photo required</h5>
            <div>Please upload your clear face picture. It will be used later to train the facial attendance model.</div>
        </div>
        <div class="student-photo-reminder-body">
            <div class="student-photo-reminder-preview">
                <img src="{{ Qs::getUserPhoto($authStudent) }}" id="student-photo-reminder-preview" alt="Student photo preview" onerror="this.src='{{ Qs::getDefaultUserImage() }}'">
                <div>
                    <strong>{{ $authStudent->name }}</strong>
                    <div class="text-muted small">Use a front-facing, well-lit picture for best face recognition accuracy.</div>
                </div>
            </div>

            <form method="post" action="{{ route('my_account.photo') }}" enctype="multipart/form-data" id="student-photo-reminder-form">
                @csrf
                <div class="form-group">
                    <label class="font-weight-semibold">Upload picture <span class="text-danger">*</span></label>
                    <input type="file" name="photo" id="student-photo-reminder-input" class="form-control" accept="image/*" required>
                    <small class="form-text text-muted">JPEG/PNG/JPG/GIF only. Maximum size: 2MB.</small>
                </div>
                <button type="submit" class="btn btn-primary btn-block">
                    <i class="fas fa-upload"></i> Upload Required Picture
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reminder = document.getElementById('student-photo-reminder');
        const closeButton = document.getElementById('student-photo-reminder-close');
        const input = document.getElementById('student-photo-reminder-input');
        const preview = document.getElementById('student-photo-reminder-preview');
        const repeatMs = parseInt(reminder.dataset.repeatMs, 10) || 10000;
        let reminderTimer = null;

        function showReminder() {
            reminder.classList.add('is-visible');
        }

        function scheduleReminder() {
            clearTimeout(reminderTimer);
            reminderTimer = setTimeout(showReminder, repeatMs);
        }

        closeButton.addEventListener('click', function () {
            reminder.classList.remove('is-visible');
            scheduleReminder();
        });

        input.addEventListener('change', function () {
            if (!this.files || !this.files[0]) return;

            const reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        });

        showReminder();
    });
</script>
