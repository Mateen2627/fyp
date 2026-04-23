@extends('layouts.master')
@section('page_title', 'My Courses')
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
    
    /* Course Badge */
    .course-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .course-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .course-icon i {
        font-size: 16px;
        color: #4f46e5;
    }
    
    .course-name {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Class Badge */
    .class-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 5px 12px;
        border-radius: 20px;
    }
    
    .class-badge i {
        font-size: 12px;
        color: #4f46e5;
    }
    
    .class-name {
        font-size: 12px;
        font-weight: 500;
        color: var(--dark);
    }
    
    /* Notes Badge */
    .notes-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        background: #fefce8;
        color: #854d0e;
        max-width: 250px;
    }
    
    .notes-badge i {
        font-size: 10px;
        color: #eab308;
    }
    
    .notes-badge span {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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
        .card-header-modern {
            padding: 16px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .course-badge {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        
        .course-icon {
            width: 28px;
            height: 28px;
        }
        
        .course-icon i {
            font-size: 12px;
        }
        
        .notes-badge {
            max-width: 150px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <i class="fas fa-book-open"></i>
        <h6 class="mb-0">Courses Assigned to Me</h6>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Class</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $item)
                    <tr>
                        <td>
                            <div class="course-badge">
                                <div class="course-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <span class="course-name">{{ optional($item->course)->title ?: '—' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="class-badge">
                                <i class="fas fa-chalkboard"></i>
                                <span class="class-name">{{ optional($item->my_class)->name ?: '—' }}</span>
                            </div>
                        </td>
                        <td>
                            @if($item->notes)
                                <div class="notes-badge">
                                    <i class="fas fa-pen"></i>
                                    <span>{{ \Illuminate\Support\Str::limit($item->notes, 40) }}</span>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <div class="empty-state">
                                <i class="fas fa-book-open"></i>
                                <p>No courses assigned to you yet.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .text-muted {
        color: #94a3b8;
        font-size: 12px;
    }
</style>

@endsection