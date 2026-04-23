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
    
    /* Add Button */
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(79, 70, 229, 0.3);
        color: white;
    }
    
    .btn-info-modern {
        background: #eef2ff;
        color: #4f46e5;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    
    .btn-info-modern:hover {
        background: #4f46e5;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-secondary-modern {
        background: #f1f5f9;
        color: #475569;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }
    
    .btn-secondary-modern:hover {
        background: #475569;
        color: white;
        transform: translateY(-1px);
    }
    
    /* Main Card */
    .dashboard-card {
        background: white;
        border-radius: 24px;
        border: 1px solid var(--border);
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-top: 20px;
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
    
    /* Assignment Title - No box/icon */
    .assignment-title {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Class Badge */
    .class-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f1f5f9;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        color: var(--gray);
    }
    
    .class-badge i {
        font-size: 11px;
        color: #4f46e5;
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
    
    /* Action Buttons Group */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
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
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .btn-info-modern, .btn-secondary-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<!-- Add Button -->
<div class="mb-3">
    <a href="{{ route('teacher.assignments.create') }}" class="btn-primary-modern">
        <i class="fas fa-plus-circle"></i> Add Assignment
    </a>
</div>

<!-- Main Card -->
<div class="dashboard-card">
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Class</th>
                    <th>Course</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assignments as $assignment)
                    @php
                        $dueDate = optional($assignment->due_date);
                        $today = now();
                        $dueClass = 'future';
                        if ($dueDate && $dueDate->isPast()) {
                            $dueClass = 'past';
                        } elseif ($dueDate && $dueDate->diffInDays($today) <= 3 && $dueDate->isFuture()) {
                            $dueClass = 'soon';
                        }
                    @endphp
                    <tr>
                        <td class="assignment-title">{{ $assignment->title }}</td>
                        <td>
                            <span class="class-badge">
                                <i class="fas fa-chalkboard"></i>
                                {{ optional($assignment->my_class)->name ?: '—' }}
                            </span>
                        </td>
                        <td>
                            <span class="class-badge">
                                <i class="fas fa-book-open"></i>
                                {{ optional($assignment->course)->title ?: '—' }}
                            </span>
                        </td>
                        <td>
                            @if($dueDate)
                                <span class="due-date {{ $dueClass }}">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $dueDate->format('M d, Y') }}
                                </span>
                            @else
                                <span class="class-badge">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('teacher.assignments.show', $assignment->id) }}" class="btn-info-modern">
                                    <i class="fas fa-eye"></i> Review Submissions
                                </a>
                                <a href="{{ route('teacher.assignments.edit', $assignment->id) }}" class="btn-secondary-modern">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fas fa-tasks"></i>
                                <p>No assignments created yet.</p>
                                <a href="{{ route('teacher.assignments.create') }}" class="btn-primary-modern" style="margin-top: 16px;">
                                    <i class="fas fa-plus-circle"></i> Create Your First Assignment
                                </a>
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