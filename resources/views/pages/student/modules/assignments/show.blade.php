@extends('layouts.master')
@section('page_title', 'Assignment Details')
@section('content')

<style>
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }

    .info-card, .submission-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }

    .card-header-modern {
        background: white;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header-modern i {
        font-size: 20px;
        color: var(--primary);
    }

    .card-header-modern h6 {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }

    .card-body-modern {
        padding: 24px;
    }

    /* Assignment Title Section */
    .title-section {
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--border);
    }

    .assignment-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .assignment-title i {
        font-size: 24px;
        color: var(--primary);
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 8px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
    }

    .info-label i {
        margin-right: 6px;
        color: var(--primary);
    }

    .info-value {
        font-size: 14px;
        font-weight: 500;
        color: var(--dark);
    }

    .description-box {
        background: var(--light);
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 16px;
    }

    .description-box p {
        margin: 0;
        font-size: 13px;
        color: var(--dark);
        line-height: 1.6;
    }

    .form-group-modern {
        margin-bottom: 20px;
    }

    .form-label-modern {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: var(--dark);
        margin-bottom: 6px;
    }

    .form-label-modern i {
        margin-right: 6px;
        color: var(--primary);
    }

    .form-control-modern {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control-modern:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    textarea.form-control-modern {
        resize: vertical;
        min-height: 100px;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .btn-secondary-modern {
        background: #f1f5f9;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: var(--gray);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: var(--dark);
    }

    .alert-info-modern {
        background: #eef2ff;
        border-radius: 12px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--primary-dark);
        font-size: 13px;
    }

    .alert-info-modern i {
        font-size: 18px;
    }

    .submission-details {
        background: var(--light);
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 20px;
    }

    .submission-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid var(--border);
    }

    .submission-row:last-child {
        border-bottom: none;
    }

    .submission-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray);
    }

    .submission-label i {
        margin-right: 6px;
    }

    .submission-value {
        font-size: 13px;
        font-weight: 500;
        color: var(--dark);
    }

    .marks-badge {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .pending-badge {
        background: #fef3c7;
        color: #d97706;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .card-header-modern {
            padding: 14px 20px;
        }
        .card-body-modern {
            padding: 20px;
        }
        .info-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .action-buttons {
            flex-direction: column;
        }
        .btn-primary-modern, .btn-secondary-modern {
            width: 100%;
            justify-content: center;
        }
        .submission-row {
            flex-direction: column;
            gap: 6px;
        }
        .assignment-title {
            font-size: 18px;
        }
    }
</style>

<div>
    <!-- Assignment Info Card -->
    <div class="info-card">
        <div class="card-header-modern">
            <i class="fas fa-file-alt"></i>
            <h6 class="mb-0">Assignment Information</h6>
        </div>
        <div class="card-body-modern">
            <!-- Assignment Title -->
            <div class="title-section">
                <div class="assignment-title">
                    <i class="fas fa-heading"></i>
                    {{ $assignment->title }}
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-book-open"></i> Course</div>
                    <div class="info-value">{{ optional($assignment->course)->title ?: '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-chalkboard-user"></i> Teacher</div>
                    <div class="info-value">{{ optional($assignment->teacher)->name ?: '-' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label"><i class="fas fa-calendar-alt"></i> Due Date</div>
                    <div class="info-value">{{ optional($assignment->due_date)->format('Y-m-d') ?: '-' }}</div>
                </div>
            </div>
            <div class="description-box">
                <div class="info-label mb-2"><i class="fas fa-align-left"></i> Description</div>
                <p>{{ $assignment->description ?: 'No description provided' }}</p>
            </div>
        </div>
    </div>

    <!-- Submission Card -->
    <div class="submission-card">
        <div class="card-header-modern">
            <i class="fas fa-paper-plane"></i>
            <h6 class="mb-0">Your Submission</h6>
        </div>
        <div class="card-body-modern">
            @if(!$submission)
                <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group-modern">
                        <label class="form-label-modern"><i class="fas fa-align-left"></i> Submission Text</label>
                        <textarea name="submission_text" class="form-control-modern" rows="6" placeholder="Write your submission here...">{{ old('submission_text', optional($submission)->submission_text) }}</textarea>
                    </div>
                    <div class="form-group-modern">
                        <label class="form-label-modern"><i class="fas fa-paperclip"></i> Attachment</label>
                        <input type="file" name="attachment" class="form-control-modern">
                        @if($submission && $submission->attachment_path)
                            <small class="text-muted d-block mt-1">Uploaded: {{ $submission->attachment_path }}</small>
                        @endif
                    </div>
                    <div class="action-buttons">
                        <button type="submit" class="btn-primary-modern"><i class="fas fa-save"></i> Submit Assignment</button>
                        <a href="{{ route('student.assignments.index') }}" class="btn-secondary-modern"><i class="fas fa-arrow-left"></i> Back</a>
                    </div>
                </form>
            @else
                <div class="alert-info-modern">
                    <i class="fas fa-check-circle"></i>
                    <span>You have already submitted this assignment. Resubmission is disabled.</span>
                </div>

                <div class="submission-details">
                    <div class="submission-row">
                        <span class="submission-label"><i class="fas fa-clock"></i> Submitted At</span>
                        <span class="submission-value">{{ optional($submission->submitted_at)->format('Y-m-d H:i') ?: '-' }}</span>
                    </div>
                    <div class="submission-row">
                        <span class="submission-label"><i class="fas fa-star"></i> Marks</span>
                        <span class="submission-value">
                            @if(is_null($submission->marks))
                                <span class="pending-badge"><i class="fas fa-hourglass-half"></i> Pending evaluation</span>
                            @else
                                <span class="marks-badge">{{ $submission->marks }} / 100</span>
                            @endif
                        </span>
                    </div>
                    <div class="submission-row">
                        <span class="submission-label"><i class="fas fa-comment"></i> Teacher Feedback</span>
                        <span class="submission-value">{{ $submission->teacher_feedback ?: '-' }}</span>
                    </div>
                </div>

                <div class="action-buttons mt-3">
                    <a href="{{ route('student.assignments.index') }}" class="btn-secondary-modern"><i class="fas fa-arrow-left"></i> Back to Assignments</a>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection