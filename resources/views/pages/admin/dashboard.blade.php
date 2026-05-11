@extends('layouts.master')
@section('page_title', 'Admin Dashboard')
@section('content')

<!-- Welcome Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-4 border-0">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
            <div class="d-flex align-items-center mb-3 mb-md-0">
                <div class="welcome-icon me-3">
                    <i class="icon-shield text-primary"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Welcome back, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted mb-0 small">Manage school operations efficiently with the modules below</p>
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
    <!-- Users Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-users">
                            <i class="icon-users"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Users Module</h5>
                    <p class="text-muted small mb-0">Manage administrators, teachers, parents, and student accounts.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Student Promotion Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('students.promotion') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-promotion">
                            <i class="icon-graduation"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Student Promotion</h5>
                    <p class="text-muted small mb-0">Promote students to next classes and manage academic progression.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Finance Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('payments.manage') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-finance">
                            <i class="icon-cash2"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Finance Module</h5>
                    <p class="text-muted small mb-0">Manage fee payments, invoices, and financial transactions.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Classes Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('classes.index') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-classes">
                            <i class="icon-books"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Classes Module</h5>
                    <p class="text-muted small mb-0">Create and manage classes, sections, and class allocations.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Subjects Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('subjects.index') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-subjects">
                            <i class="icon-book"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Subjects Module</h5>
                    <p class="text-muted small mb-0">Manage academic subjects, syllabi, and subject assignments.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Exams Module -->
    <div class="col-12 col-sm-6 col-lg-4">
        <a href="{{ route('exams.index') }}" class="text-decoration-none">
            <div class="card dashboard-module-card border-0 shadow-sm h-100">
                <div class="card-body p-4 text-center text-sm-start">
                    <div class="module-icon-wrapper mb-3">
                        <div class="module-icon bg-gradient-exams">
                            <i class="icon-pencil"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Exams Module</h5>
                    <p class="text-muted small mb-0">Manage examinations, timetables, and grading systems.</p>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 px-4">
                    <div class="d-flex align-items-center text-primary">
                        <span class="small fw-semibold">Access Module</span>
                        <i class="icon-arrow-right14 ms-2"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Quick Stats Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 bg-gradient-admin">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-8 mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon me-3">
                                <i class="icon-stats-bars"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-white">Quick Overview</h6>
                                <p class="small mb-0 text-white-50">Monitor key metrics and system performance</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex justify-content-md-end gap-2">
                            <button class="btn btn-light btn-sm" onclick="showAdminHelp()">
                                <i class="icon-lifebuoy me-1"></i>
                                Need Help?
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Welcome Section */
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

.dashboard-module-card:hover .module-icon {
    transform: scale(1.05);
}

.dashboard-module-card:hover .icon-arrow-right14 {
    transform: translateX(5px);
}

/* Module Icons */
.module-icon-wrapper {
    text-align: center;
}

.module-icon {
    width: 60px;
    height: 60px;
    margin: 0 auto;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.module-icon i {
    font-size: 1.75rem;
    color: white;
}

/* Gradient Backgrounds */
.bg-gradient-users {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 5px 15px rgba(102,126,234,0.3);
}

.bg-gradient-promotion {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    box-shadow: 0 5px 15px rgba(240,147,251,0.3);
}

.bg-gradient-finance {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    box-shadow: 0 5px 15px rgba(79,172,254,0.3);
}

.bg-gradient-classes {
    background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
    box-shadow: 0 5px 15px rgba(67,233,123,0.3);
}

.bg-gradient-subjects {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    box-shadow: 0 5px 15px rgba(250,112,154,0.3);
}

.bg-gradient-exams {
    background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
    box-shadow: 0 5px 15px rgba(161,140,209,0.3);
}

/* Quick Stats Section */
.bg-gradient-admin {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stats-icon {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.stats-icon i {
    font-size: 1.5rem;
    color: white;
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

/* Hover Effects */
.icon-arrow-right14 {
    transition: transform 0.3s ease;
}

.text-decoration-none:hover .icon-arrow-right14 {
    transform: translateX(5px);
}

/* Card Footer */
.card-footer {
    background: transparent;
    border-top: none;
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
        width: 50px;
        height: 50px;
    }
    
    .module-icon i {
        font-size: 1.5rem;
    }
    
    .dashboard-module-card .card-body {
        text-align: center !important;
    }
    
    .dashboard-module-card .card-footer .d-flex {
        justify-content: center !important;
    }
    
    .stats-icon {
        width: 40px;
        height: 40px;
    }
    
    .stats-icon i {
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
        width: 45px;
        height: 45px;
    }
    
    .module-icon i {
        font-size: 1.25rem;
    }
    
    .quick-stats {
        width: 100%;
        text-align: center;
    }
    
    .btn-light.btn-sm {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
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

.dashboard-module-card:nth-child(1) { animation-delay: 0.1s; }
.dashboard-module-card:nth-child(2) { animation-delay: 0.15s; }
.dashboard-module-card:nth-child(3) { animation-delay: 0.2s; }
.dashboard-module-card:nth-child(4) { animation-delay: 0.25s; }
.dashboard-module-card:nth-child(5) { animation-delay: 0.3s; }
.dashboard-module-card:nth-child(6) { animation-delay: 0.35s; }

/* Focus States */
.dashboard-module-card:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(102,126,234,0.3);
}

/* Text Colors */
.text-white-50 {
    color: rgba(255, 255, 255, 0.7);
}

/* Gap Utility */
.gap-2 {
    gap: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add ripple effect on cards
    const cards = document.querySelectorAll('.dashboard-module-card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking on buttons inside
            if (e.target.closest('.btn')) return;
            
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
    
    // Add keyboard navigation support
    const moduleLinks = document.querySelectorAll('.dashboard-module-card');
    let currentIndex = -1;
    
    document.addEventListener('keydown', function(e) {
        // Arrow keys for navigation
        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
            e.preventDefault();
            currentIndex = (currentIndex + 1) % moduleLinks.length;
            moduleLinks[currentIndex].focus();
            moduleLinks[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
            e.preventDefault();
            currentIndex = currentIndex <= 0 ? moduleLinks.length - 1 : currentIndex - 1;
            moduleLinks[currentIndex].focus();
            moduleLinks[currentIndex].scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else if (e.key === 'Enter' && currentIndex >= 0) {
            e.preventDefault();
            moduleLinks[currentIndex].click();
        }
    });
});

// Admin help function
function showAdminHelp() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Admin Support Center',
            html: '<p class="text-start">Quick Tips for Administrators:</p>' +
                  '<ul class="text-start">' +
                  '<li><strong>Users Module:</strong> Manage all user accounts and permissions</li>' +
                  '<li><strong>Student Promotion:</strong> Promote students to next academic year</li>' +
                  '<li><strong>Finance Module:</strong> Track fee collections and payments</li>' +
                  '<li><strong>Classes Module:</strong> Organize classes and sections</li>' +
                  '<li><strong>Subjects Module:</strong> Define curriculum subjects</li>' +
                  '<li><strong>Exams Module:</strong> Set up examination schedules</li>' +
                  '</ul>' +
                  '<hr>' +
                  '<p class="text-start small text-muted mb-0">For technical support, contact the IT department or refer to the documentation.</p>',
            icon: 'info',
            confirmButtonColor: '#667eea',
            confirmButtonText: 'Got it',
            width: '500px'
        });
    } else {
        alert('Admin Dashboard Help:\n\n• Users Module - Manage all user accounts\n• Student Promotion - Promote students to next class\n• Finance Module - Track payments and fees\n• Classes Module - Manage classes and sections\n• Subjects Module - Define academic subjects\n• Exams Module - Set up examinations');
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

<!-- Optional: Add keyboard shortcut hint -->
<div class="position-fixed bottom-3 end-3 d-none d-md-block" style="bottom: 20px; right: 20px; z-index: 1040;">
    <div class="bg-dark text-white rounded-pill px-3 py-2 small opacity-75">
        <i class="icon-keyboard me-1"></i>
        Use arrow keys to navigate
    </div>
</div>

@endsection