@extends('layouts.master')
@section('page_title', 'Student Dashboard')
@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    
    .welcome-content {
        display: flex;
        align-items: center;
        gap: 18px;
        position: relative;
        z-index: 1;
    }

    .welcome-photo {
        width: 82px;
        height: 82px;
        border-radius: 24px;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.45);
        box-shadow: 0 16px 35px rgba(15, 23, 42, 0.22);
        background: rgba(255, 255, 255, 0.2);
    }

    .welcome-title {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .welcome-title i {
        margin-right: 10px;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }
    
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid var(--border);
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
    .stat-icon.purple { background: #f3e8ff; color: #9333ea; }
    .stat-icon.orange { background: #fff7ed; color: #ea580c; }
    
    .stat-value {
        font-size: 32px;
        font-weight: 800;
        color: var(--dark);
        line-height: 1.2;
    }
    
    .stat-label {
        font-size: 13px;
        color: var(--gray);
        font-weight: 500;
        margin-top: 4px;
    }
    
    .stat-sub {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 4px;
        display: block;
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
        color: var(--dark);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-title i {
        color: var(--primary);
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
        border: 1px solid var(--border);
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
        color: var(--dark);
        margin-bottom: 4px;
    }
    
    .action-desc {
        font-size: 12px;
        color: var(--gray);
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

<div class="student-dashboard">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-content">
            <img src="{{ Qs::getUserPhoto(Auth::user()) }}" class="welcome-photo" alt="{{ Auth::user()->name }}" onerror="this.src='{{ Qs::getDefaultUserImage() }}'">
            <div>
                <div class="welcome-title">
                    <i class="fas fa-user-graduate"></i> Welcome back, {{ Auth::user()->name }}!
                </div>
                <div class="welcome-subtitle">
                    Track your assignments, quizzes, attendance and support requests in one place.
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <!-- Assignments Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="stat-value">{{ $submittedAssignments }}/{{ $totalAssignments }}</div>
            <div class="stat-label">Assignments</div>
            <small class="stat-sub">Submitted / Total</small>
        </div>
        
        <!-- Pending Assignments Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingAssignments }}</div>
            <div class="stat-label">Pending Assignments</div>
            <small class="stat-sub">Need submission</small>
        </div>
        
        <!-- Quizzes Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
            <div class="stat-value">{{ $attemptedQuizzes }}/{{ $totalQuizzes }}</div>
            <div class="stat-label">Quizzes</div>
            <small class="stat-sub">Attempted / Total</small>
        </div>
        
        <!-- Pending Quizzes Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <i class="fas fa-hourglass-half"></i>
                </div>
            </div>
            <div class="stat-value">{{ $pendingQuizzes }}</div>
            <div class="stat-label">Pending Quizzes</div>
            <small class="stat-sub">Need attempt</small>
        </div>
        
        <!-- Attendance Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $attendancePresent }}</div>
            <div class="stat-label">Present Days</div>
            <small class="stat-sub">Attendance status: present</small>
        </div>
        
        <!-- Support Tickets Card -->
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="fas fa-ticket-alt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $supportTickets }}</div>
            <div class="stat-label">Support Tickets</div>
            <small class="stat-sub">Total raised</small>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i> Quick Actions
        </h3>
    </div>

    <div class="actions-grid">
        <a href="{{ route('student.courses.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Courses</div>
                <div class="action-desc">View your enrolled courses</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.assignments.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Assignments</div>
                <div class="action-desc">Submit and track your assignments</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.quizzes.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Quizzes</div>
                <div class="action-desc">Take and review your quizzes</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.attendance.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Attendance</div>
                <div class="action-desc">View your attendance records</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.reports.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Reports</div>
                <div class="action-desc">View academic performance reports</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.extra-details.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-info-circle"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Extra Details</div>
                <div class="action-desc">Update your personal information</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('student.help-support.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-life-ring"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Help & Support</div>
                <div class="action-desc">Raise support tickets and get help</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

@endsection