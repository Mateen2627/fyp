@extends('layouts.master')
@section('page_title', 'Parent Dashboard')
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
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .welcome-title i {
        font-size: 28px;
    }
    
    .welcome-subtitle {
        font-size: 14px;
        opacity: 0.9;
        position: relative;
        z-index: 1;
    }
    
    .date-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }
    
    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }
    
    /* Module Cards */
    .module-card {
        background: white;
        border-radius: 20px;
        border: 1px solid var(--border);
        overflow: hidden;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
    }
    
    .module-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
        border-color: var(--primary-light);
    }
    
    .module-card-body {
        padding: 24px;
    }
    
    .module-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
    }
    
    .module-icon.primary {
        background: #eef2ff;
        color: #4f46e5;
    }
    
    .module-icon.success {
        background: #ecfdf5;
        color: #10b981;
    }
    
    .module-icon i {
        font-size: 26px;
    }
    
    .module-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 8px;
    }
    
    .module-desc {
        font-size: 13px;
        color: var(--gray);
        line-height: 1.5;
        margin-bottom: 16px;
    }
    
    .module-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        font-weight: 600;
        color: var(--primary);
        transition: all 0.2s ease;
    }
    
    .module-card:hover .module-link {
        gap: 10px;
    }
    
    /* Tips Section */
    .tips-card {
        background: var(--light);
        border-radius: 16px;
        padding: 20px 24px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    
    @media (min-width: 768px) {
        .tips-card {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    
    .tips-content {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .tips-icon {
        width: 48px;
        height: 48px;
        background: #eef2ff;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .tips-icon i {
        font-size: 22px;
        color: #4f46e5;
    }
    
    .tips-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 4px;
    }
    
    .tips-text {
        font-size: 12px;
        color: var(--gray);
    }
    
    .btn-light-modern {
        background: white;
        border: 1px solid var(--border);
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: var(--dark);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-light-modern:hover {
        background: var(--light);
        transform: translateY(-1px);
    }
    
    .btn-outline-modern {
        background: transparent;
        border: 1px solid var(--border);
        padding: 8px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 500;
        color: var(--gray);
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .btn-outline-modern:hover {
        background: var(--light);
        color: var(--dark);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .welcome-banner {
            padding: 20px 24px;
        }
        
        .welcome-title {
            font-size: 20px;
        }
        
        .dashboard-grid {
            gap: 16px;
        }
        
        .module-card-body {
            padding: 20px;
        }
        
        .tips-card {
            padding: 16px 20px;
        }
        
        .tips-content {
            flex-direction: column;
            text-align: center;
        }
        
        .tips-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
    }
</style>

<div class="parent-dashboard">
    <!-- Welcome Banner -->
    <div class="welcome-banner">
        <div class="welcome-title">
            <i class="fas fa-heart"></i> Welcome back, {{ Auth::user()->name }}!
        </div>
        <div class="welcome-subtitle">
            Access your children's records, track academic performance, and stay updated with school activities.
        </div>
        <div class="date-badge mt-3">
            <i class="fas fa-calendar-alt me-1"></i> {{ date('l, F j, Y') }}
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="row mb-3">
        <div class="col-md-3"><div class="card"><div class="card-body"><h4 class="mb-1">{{ $stats['children_count'] ?? 0 }}</h4><span>Total Children</span></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h4 class="mb-1">{{ $stats['attendance_total'] ?? 0 }}</h4><span>Total Attendance Logs</span></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h4 class="mb-1">{{ number_format($stats['mid_total'] ?? 0, 2) }}</h4><span>Combined Mid Marks</span></div></div></div>
        <div class="col-md-3"><div class="card"><div class="card-body"><h4 class="mb-1">{{ number_format($stats['final_total'] ?? 0, 2) }}</h4><span>Combined Final Marks</span></div></div></div>
    </div>

    <!-- Dashboard Modules Grid -->
    <div class="dashboard-grid">
        <!-- My Children Module -->
        <a href="{{ route('my_children') }}" class="module-card">
            <div class="module-card-body">
                <div class="module-icon primary">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="module-title">My Children</h3>
                <p class="module-desc">View and manage your children's profiles, track their progress, and monitor academic performance.</p>
                <div class="module-link">
                    <span>Access Module</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>

        <!-- Academic Timetable Module -->
        <a href="{{ route('tt.index') }}" class="module-card">
            <div class="module-card-body">
                <div class="module-icon success">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="module-title">Academic Timetable</h3>
                <p class="module-desc">Access class schedules, examination timetables, and important academic dates for your children.</p>
                <div class="module-link">
                    <span>Access Module</span>
                    <i class="fas fa-arrow-right"></i>
                </div>
            </div>
        </a>

        @if(($children ?? collect())->isNotEmpty())
            <a href="{{ route('parent.attendance.index') }}" class="module-card">
                <div class="module-card-body">
                    <div class="module-icon warning"><i class="fas fa-calendar-check"></i></div>
                    <h3 class="module-title">Attendance</h3>
                    <p class="module-desc">Track daily attendance for your child with present, absent, late, and excused records.</p>
                    <div class="module-link"><span>Open Attendance</span><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>

            <a href="{{ route('parent.academics.index') }}" class="module-card">
                <div class="module-card-body">
                    <div class="module-icon info"><i class="fas fa-book-open"></i></div>
                    <h3 class="module-title">Academics</h3>
                    <p class="module-desc">See mid/final marks, assignments, and quizzes submitted by your child.</p>
                    <div class="module-link"><span>Open Academics</span><i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
        @endif
    </div>

    @if(($recent_attendance ?? collect())->isNotEmpty())
        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0">Recent Attendance Updates</h6></div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead><tr><th>Date</th><th>Student ID</th><th>Status</th><th>Marked By</th></tr></thead>
                    <tbody>
                    @foreach($recent_attendance as $row)
                        <tr>
                            <td>{{ optional($row->attendance_date)->format('Y-m-d') }}</td>
                            <td>{{ $row->user_id }}</td>
                            <td><span class="badge badge-{{ $row->status === 'present' ? 'success' : ($row->status === 'absent' ? 'danger' : 'warning') }}">{{ ucfirst($row->status) }}</span></td>
                            <td>{{ optional($row->markedBy)->name ?: '—' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Quick Tips Section -->
    <div class="tips-card">
        <div class="tips-content">
            <div class="tips-icon">
                <i class="fas fa-lightbulb"></i>
            </div>
            <div>
                <div class="tips-title">Quick Tips for Parents</div>
                <div class="tips-text">Stay updated with your children's academic progress and school activities</div>
            </div>
        </div>
        <div class="tips-buttons">
            <button class="btn-light-modern" onclick="showNotifications()">
                <i class="fas fa-bell me-1"></i> Get Updates
            </button>
            <button class="btn-outline-modern" onclick="contactSupport()">
                <i class="fas fa-life-ring me-1"></i> Need Help?
            </button>
        </div>
    </div>
</div>

<script>
    function showNotifications() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Stay Updated',
                text: 'You will receive notifications about your children\'s academic progress and school events.',
                icon: 'info',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Got it'
            });
        } else {
            alert('Notification preferences can be set in your profile settings.');
        }
    }

    function contactSupport() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Need Help?',
                html: '<p>Contact school administration:</p>' +
                      '<div class="text-start mt-3">' +
                      '<i class="fas fa-phone me-2"></i> +1 234 567 8900<br>' +
                      '<i class="fas fa-envelope me-2"></i> support@neoedu.com<br>' +
                      '<i class="fas fa-clock me-2"></i> Mon-Fri: 8:00 AM - 5:00 PM' +
                      '</div>',
                icon: 'question',
                confirmButtonColor: '#4f46e5',
                confirmButtonText: 'Close'
            });
        } else {
            alert('Contact school administration for assistance.');
        }
    }
</script>

@endsection
