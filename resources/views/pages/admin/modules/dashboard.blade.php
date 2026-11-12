@extends('layouts.master')
@section('page_title', 'Admin Module Dashboard')
@section('content')

<style>
    /* Welcome Banner */
    .welcome-banner {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        color: white;
    }
    
    .welcome-banner::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }
    
    .welcome-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #eef2ff;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
    }
    
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
    }
    
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .stat-icon.primary { background: #eef2ff; color: #4f46e5; }
    .stat-icon.success { background: #ecfdf5; color: #10b981; }
    .stat-icon.warning { background: #fffbeb; color: #f59e0b; }
    .stat-icon.info { background: #e0f2fe; color: #0ea5e9; }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        margin-top: 4px;
        text-transform: capitalize;
    }
    
    /* Quick Actions Section */
    .section-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }
    
    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: #4f46e5;
        font-size: 20px;
    }
    
    /* Quick Action Cards */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }
    
    .action-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eef2ff;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
    }
    
    .action-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
        border-color: #c7d2fe;
    }
    
    .action-icon {
        width: 52px;
        height: 52px;
        background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .action-card:hover .action-icon {
        transform: scale(1.05);
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
    }
    
    .action-card:hover .action-icon i {
        color: white;
    }
    
    .action-icon i {
        font-size: 24px;
        color: #4f46e5;
        transition: all 0.3s ease;
    }
    
    .action-content {
        flex: 1;
    }
    
    .action-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }
    
    .action-desc {
        font-size: 12px;
        color: #64748b;
    }
    
    .action-arrow {
        color: #cbd5e1;
        transition: all 0.3s ease;
    }
    
    .action-card:hover .action-arrow {
        color: #4f46e5;
        transform: translateX(4px);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 20px 24px;
        }
        
        .welcome-title {
            font-size: 20px;
        }
        
        .stats-grid {
            gap: 12px;
        }
        
        .stat-value {
            font-size: 24px;
        }
        
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .actions-grid {
            gap: 12px;
        }
        
        .action-card {
            padding: 16px;
        }
        
        .action-icon {
            width: 44px;
            height: 44px;
        }
        
        .action-icon i {
            font-size: 20px;
        }
        
        .action-title {
            font-size: 14px;
        }
    }
    
    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .actions-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-dashboard">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-title">
            <i class="fas fa-chart-line me-2"></i> Welcome back, {{ Auth::user()->name }}!
        </div>
        <div class="welcome-subtitle">
            Here's the latest institutional overview with role-wide operational statistics.
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        @php
            $iconColors = ['primary', 'success', 'warning', 'info'];
            $iconClasses = ['fas fa-users', 'fas fa-book', 'fas fa-chalkboard', 'fas fa-user-graduate'];
            $index = 0;
        @endphp
        @foreach($stats as $label => $count)
            @php
                $iconColor = $iconColors[$index % count($iconColors)];
                $iconClass = $iconClasses[$index % count($iconClasses)];
                $displayLabel = str_replace('_', ' ', $label);
                $index++;
            @endphp
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon {{ $iconColor }}">
                        <i class="{{ $iconClass }}"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($count) }}</div>
                <div class="stat-label">{{ ucfirst($displayLabel) }}</div>
            </div>
        @endforeach
    </div>

    <!-- Quick Actions Section -->
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i> Quick Actions
        </h3>
    </div>

    <div class="actions-grid">
        <a href="{{ route('admin.users.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Manage Users</div>
                <div class="action-desc">Add, edit or remove system users</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.classes.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Manage Classes</div>
                <div class="action-desc">Organize class sections and divisions</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.courses.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Manage Courses</div>
                <div class="action-desc">Create and manage course curriculum</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('tt.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-chalkboard-user"></i>
            </div>
            <div class="action-content">
                <div class="action-title">View Timetables</div>
                <div class="action-desc">Assign teachers to classes and courses</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.reports.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Reports</div>
                <div class="action-desc">View detailed analytics and reports</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('admin.system-settings.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-sliders-h"></i>
            </div>
            <div class="action-content">
                <div class="action-title">System Settings</div>
                <div class="action-desc">Configure system preferences</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

@endsection
