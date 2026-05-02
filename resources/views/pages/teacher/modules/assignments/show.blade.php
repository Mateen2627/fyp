@extends('layouts.master')
@section('page_title', 'Review Assignment Submissions')
@section('content')

<style>
    /* Modern Professional Styling */
    :root {
        --primary: #4f46e5;
        --primary-dark: #4338ca;
        --primary-light: #818cf8;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #3b82f6;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }
    
    /* Assignment Info Card */
    .info-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 24px;
    }
    
    .info-card-body {
        padding: 24px 28px;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }
    
    .assignment-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .assignment-title i {
        font-size: 24px;
        color: var(--primary);
    }
    
    .assignment-title h6 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .assignment-description {
        color: var(--gray);
        font-size: 14px;
        margin-bottom: 16px;
        padding-left: 36px;
    }
    
    /* Submissions Card */
    .submissions-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    .card-header-modern {
        background: white;
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
    }
    
    .card-header-modern h6 {
        font-size: 15px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header-modern h6 i {
        font-size: 18px;
        color: var(--primary);
    }
    
    .submission-count {
        background: var(--light);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--primary);
    }
    
    /* Table */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead th {
        padding: 12px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern tbody td {
        padding: 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid var(--border);
        vertical-align: top;
    }
    
    .table-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Student ID Badge */
    .student-badge {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .student-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .student-avatar i {
        font-size: 14px;
        color: var(--primary);
    }
    
    .student-id {
        font-family: monospace;
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Submitted Date Badge */
    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
    }
    
    .date-badge i {
        font-size: 10px;
    }
    
    .no-date {
        color: var(--gray);
        font-size: 12px;
    }
    
    /* Submission Text */
    .submission-text {
        max-width: 250px;
        word-wrap: break-word;
        font-size: 12px;
        color: var(--gray);
        line-height: 1.4;
    }
    
    /* Download Button */
    .btn-download-sm {
        background: #eef2ff;
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 500;
        color: var(--primary);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    
    .btn-download-sm:hover {
        background: #e0e7ff;
        transform: translateY(-1px);
    }
    
    /* Form Elements inside Table */
    .marks-input {
        width: 80px;
        padding: 6px 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 13px;
        transition: all 0.2s;
    }
    
    .marks-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }
    
    .feedback-textarea {
        width: 100%;
        padding: 8px 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 12px;
        resize: vertical;
        min-height: 60px;
        font-family: inherit;
    }
    
    .feedback-textarea:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1);
    }
    
    .btn-save-sm {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: all 0.2s;
        margin-top: 8px;
    }
    
    .btn-save-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    }
    
    .locked-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ecfdf5;
        color: #059669;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .locked-badge i {
        font-size: 11px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }
    
    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }
    
    .empty-state p {
        font-size: 14px;
        color: #94a3b8;
        margin-bottom: 0;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .info-card-body {
            padding: 16px 20px;
        }
        
        .assignment-title h6 {
            font-size: 16px;
        }
        
        .assignment-description {
            font-size: 12px;
            padding-left: 32px;
        }
        
        .card-header-modern {
            padding: 14px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .submission-text {
            max-width: 150px;
        }
        
        .marks-input {
            width: 70px;
        }
    }
</style>

<!-- Assignment Info Card -->
<div class="info-card">
    <div class="info-card-body">
        <div class="assignment-title">
            <i class="fas fa-file-alt"></i>
            <h6 class="mb-0">{{ $assignment->title }}</h6>
        </div>
        <div class="assignment-description">
            <i class="fas fa-align-left me-2 text-muted"></i>
            {{ $assignment->description ?: 'No description provided' }}
        </div>
        @if($assignment->attachment_path)
            <div style="margin-left: 36px;">
                <a href="{{ route('teacher.assignments.download', $assignment->id) }}" class="btn-download-sm">
                    <i class="fas fa-download"></i> Download Assignment File
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Submissions Card -->
<div class="submissions-card">
    <div class="card-header-modern">
        <h6>
            <i class="fas fa-users"></i>
            Student Submissions
        </h6>
        <span class="submission-count">
            <i class="fas fa-file-upload me-1"></i> {{ $submissions->count() }} Submissions
        </span>
    </div>
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Submitted</th>
                    <th>Submission Text</th>
                    <th>Attachment</th>
                    <th>Marks / Feedback</th>
                </tr>
            </thead>
            <tbody>
                @forelse($submissions as $submission)
                    <tr>
                        <td>
                            <div class="student-badge">
                                <div class="student-avatar">
                                    <i class="fas fa-user-graduate"></i>
                                </div>
                                <span class="student-id">#{{ $submission->student_id }}</span>
                            </div>
                        </td>
                        <td>
                            @if($submission->submitted_at)
                                <span class="date-badge">
                                    <i class="fas fa-check-circle"></i>
                                    {{ optional($submission->submitted_at)->format('Y-m-d H:i') }}
                                </span>
                            @else
                                <span class="no-date">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="submission-text">
                                {{ $submission->submission_text ?: '—' }}
                            </div>
                        </td>
                        <td>
                            @if($submission->attachment_path)
                                <a href="{{ route('teacher.assignments.submissions.download', [$assignment->id, $submission->id]) }}" class="btn-download-sm">
                                    <i class="fas fa-file-pdf"></i> Download PDF
                                </a>
                            @else
                                <span class="no-date">—</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('teacher.assignments.mark', [$assignment->id, $submission->id]) }}" method="post">
                                @csrf
                                <input type="number" step="0.01" min="0" max="100" name="marks" 
                                       value="{{ $submission->marks }}" 
                                       class="marks-input mb-2" 
                                       placeholder="Marks" 
                                       {{ !is_null($submission->marks) ? 'disabled' : '' }}>
                                <textarea name="teacher_feedback" 
                                          class="feedback-textarea mb-2" 
                                          placeholder="Write feedback here..." 
                                          {{ !is_null($submission->marks) ? 'disabled' : '' }}>{{ $submission->teacher_feedback }}</textarea>
                                @if(is_null($submission->marks))
                                    <button type="submit" class="btn-save-sm">
                                        <i class="fas fa-save"></i> Save Feedback
                                    </button>
                                @else
                                    <span class="locked-badge">
                                        <i class="fas fa-lock"></i> Locked
                                    </span>
                                @endif
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-inbox"></i>
                                <p>No submissions yet for this assignment.</p>
                                <small class="text-muted">Students will appear here once they submit their work.</small>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection