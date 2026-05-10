@extends('layouts.master')
@section('page_title', 'Review Quiz Attempts')
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
    
    /* Quiz Info Card */
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
    
    .quiz-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .quiz-title i {
        font-size: 24px;
        color: var(--primary);
    }
    
    .quiz-title h6 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .quiz-description {
        color: var(--gray);
        font-size: 14px;
        margin-bottom: 16px;
        padding-left: 36px;
    }
    
    /* Attempts Card */
    .attempts-card {
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
    
    .attempt-count {
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
    
    /* Student ID - Clean without box */
    .student-id {
        font-family: monospace;
        font-weight: 600;
        color: var(--dark);
        display: inline-block;
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
    
    /* Answers Text */
    .answers-text {
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
    
    /* Back Button */
    .back-button {
        margin-bottom: 20px;
    }
    
    .btn-back {
        background: #f1f5f9;
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 500;
        font-size: 13px;
        color: var(--gray);
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-back:hover {
        background: #e2e8f0;
        color: var(--dark);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .info-card-body {
            padding: 16px 20px;
        }
        
        .quiz-title h6 {
            font-size: 16px;
        }
        
        .quiz-description {
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
        
        .answers-text {
            max-width: 150px;
        }
        
        .marks-input {
            width: 70px;
        }
    }
</style>

<div>
    <!-- Back Button -->
    <div class="back-button">
        <a href="{{ route('teacher.quizzes.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Quizzes
        </a>
    </div>

    <!-- Quiz Info Card -->
    <div class="info-card">
        <div class="info-card-body">
            <div class="quiz-title">
                <i class="fas fa-question-circle"></i>
                <h6 class="mb-0">{{ $quiz->title }}</h6>
            </div>
            <div class="quiz-description">
                <i class="fas fa-align-left me-2 text-muted"></i>
                {{ $quiz->description ?: 'No description provided' }}
            </div>
            @if($quiz->attachment_path)
                <div style="margin-left: 36px;">
                    <a href="{{ route('teacher.quizzes.view', $quiz->id) }}" target="_blank" class="btn-download-sm">
                        <i class="fas fa-eye"></i> View Quiz File
                    </a>
                    <a href="{{ route('teacher.quizzes.download', $quiz->id) }}" class="btn-download-sm">
                        <i class="fas fa-download"></i> Download Quiz File
                    </a>
                </div>
                <div style="margin-left: 36px; margin-top: 12px;">
                    <iframe src="{{ route('teacher.quizzes.view', $quiz->id) }}" style="width: 100%; min-height: 480px; border: 1px solid #e2e8f0; border-radius: 12px;"></iframe>
                </div>
            @endif
        </div>
    </div>

    <!-- Attempts Card -->
    <div class="attempts-card">
        <div class="card-header-modern">
            <h6>
                <i class="fas fa-users"></i>
                Student Attempts
            </h6>
            <span class="attempt-count">
                <i class="fas fa-file-upload me-1"></i> {{ $attempts->count() }} Attempts
            </span>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Submitted</th>
                        <th>Answers</th>
                        <th>Attachment</th>
                        <th>Marks / Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($attempts as $attempt)
                        <tr>
                            <td>
                                <span class="student-id">#{{ $attempt->student_id }}</span>
                            </td>
                            <td>
                                @if($attempt->submitted_at)
                                    <span class="date-badge">
                                        <i class="fas fa-check-circle"></i>
                                        {{ optional($attempt->submitted_at)->format('Y-m-d H:i') }}
                                    </span>
                                @else
                                    <span class="no-date">—</span>
                                @endif
                            </td>
                            <td>
                                <div class="answers-text">
                                    {{ $attempt->answers ?: '—' }}
                                </div>
                            </td>
                            <td>
                                @if($attempt->attachment_path)
                                    <a href="{{ route('teacher.quizzes.attempts.view', [$quiz->id, $attempt->id]) }}" target="_blank" class="btn-download-sm">
                                        <i class="fas fa-eye"></i> View PDF
                                    </a>
                                    <a href="{{ route('teacher.quizzes.attempts.download', [$quiz->id, $attempt->id]) }}" class="btn-download-sm">
                                        <i class="fas fa-file-pdf"></i> Download PDF
                                    </a>
                                @else
                                    <span class="no-date">—</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('teacher.quizzes.mark', [$quiz->id, $attempt->id]) }}" method="post">
                                    @csrf
                                    <input type="number" step="0.01" min="0" max="100" name="marks" 
                                           value="{{ $attempt->marks }}" 
                                           class="marks-input mb-2" 
                                           placeholder="Marks" 
                                           {{ !is_null($attempt->marks) ? 'disabled' : '' }}>
                                    <textarea name="teacher_feedback" 
                                              class="feedback-textarea mb-2" 
                                              placeholder="Write feedback here..." 
                                              {{ !is_null($attempt->marks) ? 'disabled' : '' }}>{{ $attempt->teacher_feedback }}</textarea>
                                    @if(is_null($attempt->marks))
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
                                    <p>No attempts yet for this quiz.</p>
                                    <small class="text-muted">Students will appear here once they attempt the quiz.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection