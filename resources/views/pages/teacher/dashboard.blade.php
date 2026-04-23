@extends('layouts.master')
@section('page_title', 'Teacher Dashboard')
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
    
    /* Font Awesome Icon Fix */
    .fas, .far {
        display: inline-block;
        font-style: normal;
        font-variant: normal;
        text-rendering: auto;
        line-height: 1;
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

<div class="teacher-dashboard">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-title">
            <i class="fas fa-chalkboard-user"></i> Welcome back, {{ Auth::user()->name }}!
        </div>
        <div class="welcome-subtitle">
            Here's your teaching overview and quick access to all classroom activities.
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-book-open"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['courses_assigned'] }}</div>
            <div class="stat-label">Assigned Courses</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon success">
                    <i class="fas fa-chalkboard"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['classes_managed'] }}</div>
            <div class="stat-label">Managed Classes</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon warning">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['assignments_created'] }}</div>
            <div class="stat-label">Assignments Created</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <i class="fas fa-question-circle"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['quizzes_created'] }}</div>
            <div class="stat-label">Quizzes Created</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon info">
                    <i class="fas fa-file-upload"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['assignment_submissions'] }}</div>
            <div class="stat-label">Assignment Submissions</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <i class="fas fa-pen-alt"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['quiz_attempts'] }}</div>
            <div class="stat-label">Quiz Attempts</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon primary">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="stat-value">{{ $stats['attendance_records'] }}</div>
            <div class="stat-label">Attendance Records</div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="section-header">
        <h3 class="section-title">
            <i class="fas fa-bolt"></i> Quick Actions
        </h3>
    </div>

    <div class="actions-grid">
        <a href="{{ route('teacher.courses.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-book-open"></i>
            </div>
            <div class="action-content">
                <div class="action-title">My Courses</div>
                <div class="action-desc">View and manage your assigned courses</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('teacher.classes.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-chalkboard"></i>
            </div>
            <div class="action-content">
                <div class="action-title">My Classes & Timetable</div>
                <div class="action-desc">View class schedules and manage students</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('teacher.assignments.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-tasks"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Assignments</div>
                <div class="action-desc">Create, review, and mark student assignments</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('teacher.quizzes.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Quizzes</div>
                <div class="action-desc">Create, review, and mark student quizzes</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>

        <a href="{{ route('teacher.attendance.index') }}" class="action-card">
            <div class="action-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="action-content">
                <div class="action-title">Student Attendance</div>
                <div class="action-desc">Mark and manage student attendance records</div>
            </div>
            <div class="action-arrow">
                <i class="fas fa-chevron-right"></i>
            </div>
        </a>
    </div>
</div>

@endsection