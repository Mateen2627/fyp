@extends('layouts.master')
@section('page_title', 'Manage Users')
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
    

    .filter-bar-modern {
        display: grid;
        grid-template-columns: minmax(220px, 1fr) 220px auto auto;
        gap: 12px;
        padding: 16px 20px;
        border-bottom: 1px solid var(--border);
        background: var(--light);
    }

    .filter-control-modern {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 9px 12px;
        font-size: 13px;
        background: white;
    }

    .btn-filter-modern {
        border: none;
        border-radius: 10px;
        padding: 9px 16px;
        font-weight: 600;
        font-size: 13px;
        cursor: pointer;
    }

    .btn-filter-primary { background: var(--primary); color: white; }
    .btn-filter-light { background: white; color: var(--gray); border: 1px solid var(--border); }
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
    
    /* Badge for User Type */
    .badge-user {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    
    .badge-admin {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .badge-teacher {
        background: #ecfdf5;
        color: #059669;
    }
    
    .badge-student {
        background: #fef3c7;
        color: #d97706;
    }
    
    .badge-parent {
        background: #fce7f3;
        color: #db2777;
    }
    
    .badge-default {
        background: #f1f5f9;
        color: #475569;
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
        
        .filter-bar-modern {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="dashboard-card">
    <div class="card-header-modern">
        <h5 class="card-title-modern">
            <i class="fas fa-users"></i>
            Manage Users
        </h5>
        <a href="{{ route('admin.users.create') }}" class="btn-primary-modern">
            <i class="fas fa-user-plus"></i> Add User
        </a>
    </div>
    <form method="get" action="{{ route('admin.users.index') }}" class="filter-bar-modern">
        <input type="search" name="search" value="{{ request('search') }}" class="filter-control-modern" placeholder="Search by name, email, or username">
        <select name="role" class="filter-control-modern">
            <option value="">All Roles</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst(str_replace('_', ' ', $role)) }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter-modern btn-filter-primary"><i class="fas fa-filter"></i> Filter</button>
        <a href="{{ route('admin.users.index') }}" class="btn-filter-modern btn-filter-light text-center"><i class="fas fa-undo"></i> Reset</a>
    </form>
    
    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th width="50">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>User Type</th>
                    <th width="150">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $row)
                    <tr>
                        <td class="fw-semibold">{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-initial">
                                    <span class="initial-circle">{{ substr($row->name, 0, 1) }}</span>
                                </div>
                                <span class="fw-medium">{{ $row->name }}</span>
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-envelope me-1 text-muted" style="font-size: 11px;"></i>
                            {{ $row->email }}
                        </td>
                        <td>
                            @php
                                $typeClass = match(strtolower($row->user_type)) {
                                    'admin', 'administrator' => 'badge-admin',
                                    'teacher' => 'badge-teacher',
                                    'student' => 'badge-student',
                                    'parent' => 'badge-parent',
                                    default => 'badge-default'
                                };
                                $typeIcon = match(strtolower($row->user_type)) {
                                    'admin', 'administrator' => 'fa-shield-alt',
                                    'teacher' => 'fa-chalkboard-user',
                                    'student' => 'fa-user-graduate',
                                    'parent' => 'fa-users',
                                    default => 'fa-user'
                                };
                            @endphp
                            <span class="badge-user {{ $typeClass }}">
                                <i class="fas {{ $typeIcon }}"></i>
                                {{ ucfirst($row->user_type) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a class="btn-edit" href="{{ route('admin.users.edit', $row) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this user? This action cannot be undone.')">
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
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-users-slash"></i>
                                    <p>No users found in the system</p>
                                    <a href="{{ route('admin.users.create') }}" class="btn-primary-modern">
                                        <i class="fas fa-user-plus"></i> Add Your First User
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
    /* User Initial Circle */
    .user-initial {
        display: inline-block;
    }
    
    .initial-circle {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: #4f46e5;
        text-transform: uppercase;
    }
    
    /* Fw utilities */
    .fw-semibold {
        font-weight: 600;
    }
    
    .fw-medium {
        font-weight: 500;
    }
    
    /* D-flex utilities */
    .d-flex {
        display: flex;
    }
    
    .align-items-center {
        align-items: center;
    }
    
    .gap-2 {
        gap: 8px;
    }
    
    .me-1 {
        margin-right: 4px;
    }
    
    /* Text color */
    .text-muted {
        color: #64748b;
    }
</style>

@endsection