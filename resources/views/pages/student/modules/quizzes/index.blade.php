@extends('layouts.master')
@section('page_title', 'Quizzes')
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
        --secondary: #64748b;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
        --border: #e2e8f0;
    }
    
    /* Main Card */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    
    /* Card Header */
    .card-header-modern {
        background: white;
        padding: 20px 24px;
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
    
    .card-header-modern span {
        margin-left: auto;
        font-size: 12px;
        color: var(--gray);
        background: var(--light);
        padding: 4px 12px;
        border-radius: 20px;
    }
    
    /* Table */
    .table-modern {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table-modern thead th {
        padding: 14px 16px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray);
        background: var(--light);
        border-bottom: 1px solid var(--border);
    }
    
    .table-modern tbody td {
        padding: 14px 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Quiz Title */
    .quiz-title {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Course Badge */
    .course-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: var(--gray);
    }
    
    .course-badge i {
        font-size: 11px;
        color: #4f46e5;
    }
    
    /* Teacher Badge */
    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eef2ff;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: #4f46e5;
    }
    
    .teacher-badge i {
        font-size: 11px;
    }
    
    /* Scheduled Date Badge */
    .scheduled-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .scheduled-date.upcoming {
        background: #ecfdf5;
        color: #059669;
    }
    
    .scheduled-date.past {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .scheduled-date.today {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .scheduled-date i {
        font-size: 11px;
    }
    
    /* Marks Badge */
    .marks-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 13px;
    }
    
    .marks-badge.pending {
        color: var(--warning);
    }
    
    .marks-badge.passed {
        color: var(--success);
    }
    
    .marks-badge.failed {
        color: var(--danger);
    }
    
    /* Total Marks Badge */
    .total-marks {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: var(--gray);
    }
    
    .total-marks i {
        font-size: 10px;
    }
    
    /* Action Button */
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 6px 16px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        color: white;
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
    
    /* Pagination */
    .pagination-modern {
        padding: 16px 20px;
        border-top: 1px solid var(--border);
        background: white;
    }
    
    .pagination-modern nav {
        display: flex;
        justify-content: center;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .course-badge, .teacher-badge, .scheduled-date {
            font-size: 10px;
            padding: 3px 8px;
        }
        
        .btn-primary-modern {
            padding: 4px 12px;
            font-size: 11px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-question-circle"></i>
        <h6 class="mb-0">Quizzes For Your Class</h6>
        <span><i class="fas fa-file-alt"></i> {{ $quizzes->total() }} Quizzes</span>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Scheduled</th>
                    <th>Total Marks</th>
                    <th>Your Marks</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    @php
                        $attempt = $attemptMap->get($quiz->id);
                        $scheduledAt = optional($quiz->scheduled_at);
                        $today = now();
                        $scheduledClass = 'upcoming';
                        if ($scheduledAt && $scheduledAt->isPast()) {
                            $scheduledClass = 'past';
                        } elseif ($scheduledAt && $scheduledAt->format('Y-m-d') == $today->format('Y-m-d')) {
                            $scheduledClass = 'today';
                        }
                        
                        $marksClass = 'pending';
                        if ($attempt && !is_null($attempt->marks)) {
                            $percentage = ($attempt->marks / max($quiz->total_marks, 1)) * 100;
                            $marksClass = $percentage >= 50 ? 'passed' : 'failed';
                        }
                    @endphp
                    <tr>
                        <td class="quiz-title">{{ $quiz->title }}</td>
                        <td>
                            <span class="course-badge">
                                <i class="fas fa-book-open"></i>
                                {{ optional($quiz->course)->title ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="teacher-badge">
                                <i class="fas fa-chalkboard-user"></i>
                                {{ optional($quiz->teacher)->name ?: '—' }}
                            </span>
                        </td>
                        <td>
                            @if($scheduledAt)
                                <span class="scheduled-date {{ $scheduledClass }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $scheduledAt->format('M d, Y H:i') }}
                                </span>
                            @else
                                <span class="course-badge">—</span>
                            @endif
                        </td>
                        <td>
                            @if($quiz->total_marks)
                                <span class="total-marks">
                                    <i class="fas fa-star"></i>
                                    {{ $quiz->total_marks }}
                                </span>
                            @else
                                <span class="course-badge">—</span>
                            @endif
                        </td>
                        <td>
                            @if($attempt && !is_null($attempt->marks))
                                <span class="marks-badge {{ $marksClass }}">
                                    <i class="fas fa-chart-line"></i>
                                    {{ $attempt->marks }}/{{ $quiz->total_marks ?: '—' }}
                                </span>
                            @else
                                <span class="marks-badge pending">
                                    <i class="fas fa-hourglass-half"></i>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.quizzes.show', $quiz->id) }}" class="btn-primary-modern">
                                <i class="fas fa-eye"></i> View / Submit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-question-circle"></i>
                                <p>No quizzes assigned to your class.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($quizzes->hasPages())
        <div class="pagination-modern">
            {{ $quizzes->links() }}
        </div>
    @endif
</div>

@endsection