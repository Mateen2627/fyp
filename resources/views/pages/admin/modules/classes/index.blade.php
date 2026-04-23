@extends('layouts.master')
@section('page_title', 'Manage Classes')
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
    
    .btn-view {
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
    
    .btn-view:hover {
        background: #475569;
        color: white;
        transform: translateY(-1px);
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
    
    /* Class Name Badge */
    .class-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }
    
    .class-icon {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .class-icon i {
        font-size: 16px;
        color: #4f46e5;
    }
    
    .class-name {
        font-weight: 600;
        color: var(--dark);
    }
    
    /* Students Count Badge */
    .students-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #ecfdf5;
        color: #059669;
    }
    
    .students-badge i {
        font-size: 12px;
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
        
        .btn-view, .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
        
        .class-badge {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }
        
        .class-icon {
            width: 28px;
            height: 28px;
        }
        
        .class-icon i {
            font-size: 12px;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <h5 class="card-title-modern">
            <i class="fas fa-chalkboard"></i>
            Manage Classes
        </h5>
        <a href="{{ route('admin.classes.create') }}" class="btn-primary-modern">
            <i class="fas fa-plus-circle"></i> Add Class
        </a>
    </div>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="60">ID</th>
                    <th>Class Name</th>
                    <th>Students</th>
                    <th width="200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $row)
                    <tr>
                        <td>
                            <span class="id-badge">#{{ $row->id }}</span>
                        </td>
                        <td>
                            <div class="class-badge">
                                <div class="class-icon">
                                    <i class="fas fa-building"></i>
                                </div>
                                <span class="class-name">{{ $row->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="students-badge">
                                <i class="fas fa-user-graduate"></i>
                                {{ $row->student_record_count }} Student{{ $row->student_record_count != 1 ? 's' : '' }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.classes.show', $row) }}" class="btn-view">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.classes.edit', $row) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.classes.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this class? All students and related data will be affected. This action cannot be undone.')">
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
                        <td colspan="4">
                            <div class="empty-state">
                                <i class="fas fa-chalkboard"></i>
                                <p>No classes found in the system</p>
                                <a href="{{ route('admin.classes.create') }}" class="btn-primary-modern">
                                    <i class="fas fa-plus-circle"></i> Add Your First Class
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
    .fw-semibold {
        font-weight: 600;
    }
    
    .text-center {
        text-align: center;
    }
</style>

@endsection