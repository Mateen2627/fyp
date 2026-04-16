@extends('layouts.master')
@section('page_title', 'Parent Dashboard')
@section('content')

<!-- Welcome Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-4 border-0">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <div class="welcome-icon me-3">
                    <i class="icon-user-tie text-primary"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted mb-0 small">Access your children's records and academic performance</p>
                </div>
            </div>
            <div class="quick-stats">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                    <i class="icon-calendar me-1"></i>
                    {{ date('l, F j, Y') }}
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Modules Grid -->
<div class="row g-4">
    <!-- My Children Module -->
    <div class="col-12 col-md-6">
        <a href="{{ route('my_children') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="module-icon-wrapper mb-4">
                        <div class="module-icon bg-gradient-children">
                            <i class="icon-users4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">My Children</h5>
                    <p class="text-muted small mb-3">View and manage your children's profiles, track their progress, and monitor academic performance.</p>
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="progress" style="height: 3px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Academic Timetable Module -->
    <div class="col-12 col-md-6">
        <a href="{{ route('tt.index') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="module-icon-wrapper mb-4">
                        <div class="module-icon bg-gradient-timetable">
                            <i class="icon-calendar3"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Academic Timetable</h5>
                    <p class="text-muted small mb-3">Access class schedules, examination timetables, and important academic dates for your children.</p>
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="progress" style="height: 3px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Tips Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-gradient-tips">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row align-items-center justify-content-between text-center text-md-start">
                    <div class="d-flex align-items-center mb-3 mb-md-0">
                        <div class="tips-icon me-3">
                            <i class="icon-info3"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Quick Tips for Parents</h6>
                            <p class="small mb-0 opacity-75">Stay updated with your children's academic progress and school activities</p>
                        </div>
                    </div>
                    <div class="tips-buttons">
                        <button class="btn btn-light btn-sm me-2" onclick="showNotifications()">
                            <i class="icon-bell me-1"></i>
                            Get Updates
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="contactSupport()">
                            <i class="icon-lifebuoy me-1"></i>
                            Need Help?
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Section Styling */
.welcome-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.welcome-icon i {
    font-size: 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

/* Dashboard Module Cards */
.dashboard-module-card {
    transition: all 0.3s ease;
    cursor: pointer;
    overflow: hidden;
    position: relative;
}

.dashboard-module-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(102,126,234,0.05) 0%, rgba(118,75,162,0.05) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.dashboard-module-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
}

.dashboard-module-card:hover::before {
    opacity: 1;
}

.dashboard-module-card:hover .progress-bar {
    width: 100% !important;
    transition: width 0.5s ease;
}

.dashboard-module-card:hover .module-icon {
    transform: scale(1.1) rotate(5deg);
}

.dashboard-module-card:hover .icon-arrow-right14 {
    transform: translateX(5px);
}

/* Module Icons */
.module-icon-wrapper {
    text-align: center;
}

.module-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.module-icon i {
    font-size: 2.5rem;
    color: white;
}

.bg-gradient-children {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.bg-gradient-timetable {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 5px 15px rgba(240,147,251,0.3);
}

/* Quick Tips Section */
.bg-gradient-tips {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.tips-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.tips-icon i {
    font-size: 1.5rem;
    color: white;
}

.tips-buttons .btn-light {
    background: rgba(255,255,255,0.2);
    border: none;
    color: white;
    transition: all 0.2s ease;
}

.tips-buttons .btn-light:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-2px);
}

.tips-buttons .btn-outline-light {
    border: 1px solid rgba(255,255,255,0.5);
    color: white;
}

.tips-buttons .btn-outline-light:hover {
    background: rgba(255,255,255,0.2);
    transform: translateY(-2px);
}

/* Progress Bar Animation */
.progress {
    border-radius: 10px;
    background: rgba(0,0,0,0.05);
}

.progress-bar {
    transition: width 0.5s ease;
}

/* Badge Styling */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
    border-radius: 0.5rem;
}

.bg-opacity-10 {
    --bs-bg-opacity: 0.1;
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .welcome-icon {
        width: 50px;
        height: 50px;
    }
    
    .welcome-icon i {
        font-size: 1.5rem;
    }
    
    .module-icon {
        width: 60px;
        height: 60px;
    }
    
    .module-icon i {
        font-size: 2rem;
    }
    
    .card-body {
        padding: 1.25rem !important;
    }
    
    .tips-icon {
        width: 40px;
        height: 40px;
    }
    
    .tips-icon i {
        font-size: 1.25rem;
    }
}

@media (max-width: 575.98px) {
    .welcome-icon {
        width: 40px;
        height: 40px;
    }
    
    .welcome-icon i {
        font-size: 1.25rem;
    }
    
    .module-icon {
        width: 50px;
        height: 50px;
    }
    
    .module-icon i {
        font-size: 1.5rem;
    }
    
    .module-icon-wrapper {
        margin-bottom: 1rem !important;
    }
    
    .tips-buttons {
        width: 100%;
        display: flex;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .tips-buttons .btn-sm {
        flex: 1;
        font-size: 0.75rem;
        padding: 0.375rem 0.5rem;
    }
    
    .quick-stats {
        width: 100%;
        text-align: center;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dashboard-module-card {
    animation: fadeInUp 0.5s ease forwards;
    opacity: 0;
}

.dashboard-module-card:nth-child(1) {
    animation-delay: 0.1s;
}

.dashboard-module-card:nth-child(2) {
    animation-delay: 0.2s;
}

/* Hover Effects */
.icon-arrow-right14 {
    transition: transform 0.3s ease;
}

.text-decoration-none:hover .icon-arrow-right14 {
    transform: translateX(5px);
}

/* Card Footer Progress */
.card-footer {
    padding-top: 0;
}

/* Focus States for Accessibility */
.dashboard-module-card:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.3);
}

/* Dark Mode Support */
@media (prefers-color-scheme: dark) {
    .dashboard-module-card {
        background: #2d2d2d;
    }
    
    .card-body h5, .card-body p {
        color: #e0e0e0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars on page load
    setTimeout(function() {
        document.querySelectorAll('.dashboard-module-card .progress-bar').forEach(bar => {
            bar.style.width = '100%';
        });
    }, 500);
    
    // Add click ripple effect for cards
    const cards = document.querySelectorAll('.dashboard-module-card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons inside
            if (e.target.closest('.tips-buttons')) return;
            
            const ripple = document.createElement('div');
            ripple.classList.add('ripple-effect');
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
});

// Quick Tips Functions
function showNotifications() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Stay Updated',
            text: 'You will receive notifications about your children\'s academic progress and school events.',
            icon: 'info',
            confirmButtonColor: '#667eea',
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
                  '<i class="icon-phone2 me-2"></i> +1 234 567 8900<br>' +
                  '<i class="icon-envelope me-2"></i> support@school.com<br>' +
                  '<i class="icon-clock me-2"></i> Mon-Fri: 8:00 AM - 5:00 PM' +
                  '</div>',
            icon: 'question',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'Close'
        });
    } else {
        alert('Contact school administration for assistance.');
    }
}

// Ripple effect CSS
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(102,126,234,0.3);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
        z-index: 1;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .dashboard-module-card {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(rippleStyle);
</script>
@endsection