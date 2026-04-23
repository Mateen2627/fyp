@extends('layouts.master')
@section('page_title', 'Assign Teachers')
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
        flex-direction: column;
        gap: 16px;
    }
    
    @media (min-width: 768px) {
        .card-header-modern {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    
    .card-title-modern {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .card-title-modern i {
        color: var(--primary);
        font-size: 20px;
    }
    
    /* Buttons */
    .btn-primary-modern {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border: none;
        padding: 8px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    
    .btn-primary-modern:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        color: white;
    }
    
    .btn-edit {
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
    
    .btn-edit:hover {
        background: #4f46e5;
        color: white;
        transform: translateY(-1px);
    }
    
    .btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border: none;
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 500;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }
    
    .btn-delete:hover {
        background: #dc2626;
        color: white;
        transform: translateY(-1px);
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
    
    /* ID Badge */
    .id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: var(--light);
        border-radius: 10px;
        font-weight: 700;
        font-size: 13px;
        color: var(--gray);
    }
    
    /* Entity Badges */
    .entity-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .entity-icon {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .entity-icon i {
        font-size: 14px;
        color: #4f46e5;
    }
    
    .entity-name {
        font-weight: 500;
        color: var(--dark);
    }
    
    /* Teacher Badge */
    .teacher-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #f1f5f9;
        padding: 4px 12px 4px 8px;
        border-radius: 20px;
    }
    
    .teacher-badge i {
        font-size: 12px;
        color: #4f46e5;
    }
    
    .teacher-name {
        font-size: 12px;
        font-weight: 500;
        color: var(--dark);
    }
    
    /* Notes Badge */
    .notes-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        background: #fefce8;
        color: #854d0e;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .notes-badge i {
        font-size: 10px;
        color: #eab308;
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
        margin-bottom: 20px;
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
        
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
        
        .entity-badge {
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        
        .notes-badge {
            max-width: 150px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <h5 class="card-title-modern">
            <i class="fas fa-chalkboard-user"></i>
            Assign Teachers to Courses
        </h5>
        <a href="{{ route('admin.assign-teachers.create') }}" class="btn-primary-modern">
            <i class="fas fa-plus-circle"></i> New Assignment
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="60">#</th>
                    @if($class_assignment_enabled)
                        <th>Class</th>
                    @endif
                    <th>Course</th>
                    <th>Teacher</th>
                    <th>Notes</th>
                    <th width="140">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $row)
                    <tr>
                        <td>
                            <span class="id-badge">{{ $row->id }}</span>
                        </td>
                        
                        @if($class_assignment_enabled)
                            <td>
                                <div class="entity-badge">
                                    <div class="entity-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <span class="entity-name">{{ optional($row->my_class)->name ?? '—' }}</span>
                                </div>
                            </td>
                        @endif
                        
                        <td>
                            <div class="entity-badge">
                                <div class="entity-icon">
                                    <i class="fas fa-book-open"></i>
                                </div>
                                <span class="entity-name">{{ optional($row->course)->title ?? '—' }}</span>
                            </div>
                        </td>
                        
                        <td>
                            <div class="teacher-badge">
                                <i class="fas fa-user-graduate"></i>
                                <span class="teacher-name">{{ optional($row->teacher)->name ?? '—' }}</span>
                            </div>
                        </td>
                        
                        <td>
                            @if($row->notes)
                                <span class="notes-badge">
                                    <i class="fas fa-pen"></i>
                                    {{ \Illuminate\Support\Str::limit($row->notes, 30) }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 11px;">—</span>
                            @endif
                        </td>
                        
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.assign-teachers.edit', $row) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.assign-teachers.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this teacher assignment? This action cannot be undone.')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $class_assignment_enabled ? 6 : 5 }}">
                            <div class="empty-state">
                                <i class="fas fa-chalkboard-user"></i>
                                <p>No teacher assignments found</p>
                                <a href="{{ route('admin.assign-teachers.create') }}" class="btn-primary-modern">
                                    <i class="fas fa-plus-circle"></i> Create First Assignment
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($records->hasPages())
        <div class="pagination-modern">
            {{ $records->links() }}
        </div>
    @endif
</div>

<style>
    /* Additional utilities */
    .text-muted {
        color: #94a3b8;
    }
</style>

@endsection