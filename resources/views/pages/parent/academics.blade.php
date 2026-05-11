@extends('layouts.master')
@section('page_title', 'Child Academic Records')
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
    
    /* Header Card */
    .header-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .header-card-body {
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
    }
    
    .student-info h5 {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 6px;
    }
    
    .student-info .text-muted {
        font-size: 13px;
        color: var(--gray);
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        text-align: center;
        border: 1px solid var(--border);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.08);
    }
    
    .stat-number {
        font-size: 28px;
        font-weight: 800;
        margin-bottom: 6px;
        color: var(--primary);
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--gray);
        font-weight: 500;
    }
    
    /* Data Cards */
    .data-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        margin-bottom: 24px;
    }
    
    .card-header {
        background: white;
        padding: 16px 24px;
        border-bottom: 1px solid var(--border);
    }
    
    .card-header h6 {
        font-size: 15px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-header h6 i {
        font-size: 18px;
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
        padding: 12px 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: #fafbff;
    }
    
    /* Status Badges */
    .badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-success {
        background: #ecfdf5;
        color: #059669;
    }
    
    .badge-warning {
        background: #fffbeb;
        color: #d97706;
    }
    
    /* Marks Pill */
    .marks-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #4f46e5;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        min-width: 65px;
    }
    
    /* Back Button */
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
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 48px 20px;
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
    @media (max-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
    }
    
    @media (max-width: 768px) {
        .header-card-body {
            padding: 16px 20px;
            flex-direction: column;
            text-align: center;
        }
        
        .student-info h5 {
            font-size: 16px;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .stat-number {
            font-size: 24px;
        }
        
        .card-header {
            padding: 14px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div>
    <!-- Header Card -->
    <div class="header-card">
        <div class="header-card-body">
            <div class="student-info">
                <h5 class="mb-1">{{ $student->user->name }} - Academic Overview</h5>
                <div class="text-muted">
                    <i class="fas fa-school me-1"></i> Class: {{ optional($student->my_class)->name }} {{ optional($student->section)->name }} | 
                    <i class="fas fa-calendar-alt me-1"></i> Year: {{ $academicYear }}
                </div>
            </div>
            <div>
                <a href="{{ route('my_children') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Back to My Children
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ number_format($summary['mid_total'], 2) }}</div>
            <div class="stat-label"><i class="fas fa-chart-line me-1"></i> Total Mid Marks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ number_format($summary['final_total'], 2) }}</div>
            <div class="stat-label"><i class="fas fa-chart-bar me-1"></i> Total Final Marks</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['assignments_submitted'] }}/{{ $summary['assignments_total'] }}</div>
            <div class="stat-label"><i class="fas fa-tasks me-1"></i> Assignments Submitted</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $summary['quizzes_submitted'] }}/{{ $summary['quizzes_total'] }}</div>
            <div class="stat-label"><i class="fas fa-question-circle me-1"></i> Quizzes Submitted</div>
        </div>
    </div>

    <!-- Subject Marks Card -->
    <div class="data-card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-book-open"></i> Subject Marks (Mid / Final)
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Teacher</th>
                        <th>Mid</th>
                        <th>Final</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($marks as $mark)
                        <tr>
                            <td><strong>{{ optional($mark->subject)->name ?: '—' }}</strong></td>
                            <td>{{ optional($mark->teacher)->name ?: '—' }}</td>
                            <td>
                                @if($mark->mid_marks !== null)
                                    <span class="marks-pill">{{ number_format($mark->mid_marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($mark->final_marks !== null)
                                    <span class="marks-pill">{{ number_format($mark->final_marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($mark->total_marks !== null)
                                    <span class="marks-pill" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); color: white;">
                                        {{ number_format($mark->total_marks, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-chart-line"></i>
                                    <p>No marks found for this student.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Assignments Card -->
    <div class="data-card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-tasks"></i> Assignments
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($assignments as $assignment)
                        @php($submission = $assignmentSubmissionMap->get($assignment->id))
                        <tr>
                            <td><strong>{{ $assignment->title }}</strong></td>
                            <td>{{ optional($assignment->course)->title ?: '—' }}</td>
                            <td>{{ optional($assignment->teacher)->name ?: '—' }}</td>
                            <td>{{ optional($assignment->due_date)->format('Y-m-d') ?: '—' }}</td>
                            <td>
                                @if($submission)
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i> Submitted
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($submission && $submission->marks !== null)
                                    <span class="marks-pill">{{ number_format($submission->marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-tasks"></i>
                                    <p>No assignments found for this student.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quizzes Card -->
    <div class="data-card">
        <div class="card-header">
            <h6 class="mb-0">
                <i class="fas fa-question-circle"></i> Quizzes
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Teacher</th>
                        <th>Scheduled</th>
                        <th>Status</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        @php($attempt = $quizAttemptMap->get($quiz->id))
                        <tr>
                            <td><strong>{{ $quiz->title }}</strong></td>
                            <td>{{ optional($quiz->course)->title ?: '—' }}</td>
                            <td>{{ optional($quiz->teacher)->name ?: '—' }}</td>
                            <td>{{ optional($quiz->scheduled_at)->format('Y-m-d H:i') ?: '—' }}</td>
                            <td>
                                @if($attempt)
                                    <span class="badge-modern badge-success">
                                        <i class="fas fa-check-circle"></i> Submitted
                                    </span>
                                @else
                                    <span class="badge-modern badge-warning">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($attempt && $attempt->marks !== null)
                                    <span class="marks-pill">{{ number_format($attempt->marks, 2) }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-question-circle"></i>
                                    <p>No quizzes found for this student.</p>
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