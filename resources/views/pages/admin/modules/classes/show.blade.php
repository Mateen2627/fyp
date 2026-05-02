@extends('layouts.master')
@section('page_title', 'Class Details')
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
    
    /* Info Card */
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
    
    .class-name {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }
    
    .class-name i {
        font-size: 28px;
        color: var(--primary);
    }
    
    .class-name h6 {
        font-size: 20px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    .class-type {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #eef2ff;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        color: var(--primary);
        margin-left: 40px;
    }
    
    .class-type i {
        font-size: 12px;
    }
    
    /* Stats Row */
    .stats-row {
        display: flex;
        gap: 16px;
        margin-top: 20px;
        margin-left: 40px;
    }
    
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        color: var(--gray);
        border: 1px solid var(--border);
    }
    
    .stat-badge i {
        font-size: 12px;
        color: var(--primary);
    }
    
    /* Students Card */
    .students-card {
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
    
    .student-count {
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
        padding: 12px 16px;
        font-size: 13px;
        color: var(--dark);
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }
    
    .table-modern tbody tr:hover {
        background: var(--light);
    }
    
    /* Student Avatar */
    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
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
    
    .student-name {
        font-weight: 500;
        color: var(--dark);
    }
    
    /* ID Badge */
    .id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--light);
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray);
        font-family: monospace;
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
    
    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        justify-content: flex-end;
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
        cursor: pointer;
        text-decoration: none;
    }
    
    .btn-secondary-modern:hover {
        background: #e2e8f0;
        color: var(--dark);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .info-card-body {
            padding: 16px 20px;
        }
        
        .class-name h6 {
            font-size: 18px;
        }
        
        .class-name i {
            font-size: 22px;
        }
        
        .class-type, .stats-row {
            margin-left: 34px;
        }
        
        .card-header-modern {
            padding: 14px 20px;
        }
        
        .table-modern thead th,
        .table-modern tbody td {
            padding: 10px 12px;
        }
        
        .student-info {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn-secondary-modern {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div>
    <!-- Class Info Card -->
    <div class="info-card">
        <div class="info-card-body">
            <div class="class-name">
                <i class="fas fa-chalkboard"></i>
                <h6 class="mb-0">{{ $record->name }}</h6>
            </div>
            <div class="class-type">
                <i class="fas fa-tag"></i>
                Type: {{ optional($record->class_type)->name ?: 'Not specified' }}
            </div>
            <div class="stats-row">
                <div class="stat-badge">
                    <i class="fas fa-users"></i>
                    Total Students: {{ $record->student_record->count() }}
                </div>
                <div class="stat-badge">
                    <i class="fas fa-calendar-alt"></i>
                    Created: {{ $record->created_at ? $record->created_at->format('M d, Y') : 'N/A' }}
                </div>
            </div>
        </div>
    </div>

    <!-- Students List Card -->
    <div class="students-card">
        <div class="card-header-modern">
            <h6>
                <i class="fas fa-user-graduate"></i>
                Students Enrolled
            </h6>
            <span class="student-count">
                <i class="fas fa-users me-1"></i> {{ $record->student_record->count() }} Student(s)
            </span>
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th width="80">Record ID</th>
                        <th>Student Name</th>
                        <th width="120">User ID</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($record->student_record as $sr)
                        <tr>
                            <td>
                                <span class="id-badge">#{{ $sr->id }}</span>
                            </td>
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="student-name">{{ optional($sr->user)->name ?: '—' }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="id-badge">ID: {{ $sr->user_id }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>No students assigned to this class yet.</p>
                                    <small class="text-muted">Assign students from the class edit page.</small>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="action-buttons">
            <a href="{{ route('admin.classes.edit', $record) }}" class="btn-secondary-modern">
                <i class="fas fa-edit"></i> Edit Class
            </a>
            <a href="{{ route('admin.classes.index') }}" class="btn-secondary-modern">
                <i class="fas fa-arrow-left"></i> Back to Classes
            </a>
        </div>
    </div>
</div>

@endsection