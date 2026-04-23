@extends('layouts.master')
@section('page_title', 'Assignments')
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
    
    /* Assignment Title */
    .assignment-title {
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
    
    /* Due Date Badge */
    .due-date {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .due-date.soon {
        background: #fef3c7;
        color: #d97706;
    }
    
    .due-date.past {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .due-date.future {
        background: #ecfdf5;
        color: #059669;
    }
    
    .due-date i {
        font-size: 11px;
    }
    
    /* Marks Badge */
    .marks-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 600;
        font-size: 14px;
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
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .status-submitted {
        background: #ecfdf5;
        color: #059669;
    }
    
    .status-pending {
        background: #fef3c7;
        color: #d97706;
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
        
        .course-badge, .teacher-badge, .due-date {
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
        <i class="fas fa-tasks"></i>
        <h6 class="mb-0">Assignments For Your Class</h6>
        <span><i class="fas fa-file-alt"></i> {{ $assignments->total() }} Assignments</span>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Due Date</th>
                    <th>Your Marks</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    @php
                        $submission = $submissionMap->get($assignment->id);
                        $dueDate = optional($assignment->due_date);
                        $today = now();
                        $dueClass = 'future';
                        if ($dueDate && $dueDate->isPast()) {
                            $dueClass = 'past';
                        } elseif ($dueDate && $dueDate->diffInDays($today) <= 3 && $dueDate->isFuture()) {
                            $dueClass = 'soon';
                        }
                        
                        $marksClass = 'pending';
                        if ($submission && !is_null($submission->marks)) {
                            $marksClass = $submission->marks >= 50 ? 'passed' : 'failed';
                        }
                    @endphp
                    <tr>
                        <td class="assignment-title">{{ $assignment->title }}</td>
                        <td>
                            <span class="course-badge">
                                <i class="fas fa-book-open"></i>
                                {{ optional($assignment->course)->title ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="teacher-badge">
                                <i class="fas fa-chalkboard-user"></i>
                                {{ optional($assignment->teacher)->name ?: '—' }}
                            </span>
                        </td>
                        <td>
                            @if($dueDate)
                                <span class="due-date {{ $dueClass }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $dueDate->format('M d, Y') }}
                                </span>
                            @else
                                <span class="course-badge">—</span>
                            @endif
                        </td>
                        <td>
                            @if($submission && !is_null($submission->marks))
                                <span class="marks-badge {{ $marksClass }}">
                                    <i class="fas fa-star"></i>
                                    {{ $submission->marks }}/100
                                </span>
                            @else
                                <span class="marks-badge pending">
                                    <i class="fas fa-hourglass-half"></i>
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($submission)
                                <span class="status-badge status-submitted">
                                    <i class="fas fa-check-circle"></i>
                                    Submitted
                                </span>
                            @else
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i>
                                    Not Submitted
                                </span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('student.assignments.show', $assignment->id) }}" class="btn-primary-modern">
                                <i class="fas fa-eye"></i> View / Submit
                            </a>
                         </td>
                     </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="fas fa-tasks"></i>
                                <p>No assignments assigned to your class.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($assignments->hasPages())
        <div class="pagination-modern">
            {{ $assignments->links() }}
        </div>
    @endif
</div>

@endsection