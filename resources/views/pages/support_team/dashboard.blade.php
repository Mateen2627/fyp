@extends('layouts.master')
@section('page_title', 'My Dashboard')
@section('content')

@if(Qs::userIsTeamSA())
<div class="row g-3 g-xl-4 mb-4">
    <!-- Total Students Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card dashboard-card bg-gradient-blue h-100 border-0 shadow-lg">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-xl-4">
                <div>
                    <h2 class="fw-bold text-white mb-1 display-6">{{ $users->where('user_type', 'student')->count() }}</h2>
                    <span class="text-white-80 small text-uppercase tracking-wide">Total Students</span>
                </div>
                <div class="icon-wrapper bg-white-20 rounded-circle p-3">
                    <i class="icon-users4 icon-2x text-white"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 px-xl-4">
                <a href="#" class="text-white-80 text-decoration-none small d-flex align-items-center">
                    <span>View Details</span>
                    <i class="icon-arrow-right14 ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Teachers Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card dashboard-card bg-gradient-danger h-100 border-0 shadow-lg">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-xl-4">
                <div>
                    <h2 class="fw-bold text-white mb-1 display-6">{{ $users->where('user_type', 'teacher')->count() }}</h2>
                    <span class="text-white-80 small text-uppercase tracking-wide">Total Teachers</span>
                </div>
                <div class="icon-wrapper bg-white-20 rounded-circle p-3">
                    <i class="icon-users2 icon-2x text-white"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 px-xl-4">
                <a href="#" class="text-white-80 text-decoration-none small d-flex align-items-center">
                    <span>View Details</span>
                    <i class="icon-arrow-right14 ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Administrators Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card dashboard-card bg-gradient-success h-100 border-0 shadow-lg">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-xl-4">
                <div>
                    <h2 class="fw-bold text-white mb-1 display-6">{{ $users->where('user_type', 'admin')->count() }}</h2>
                    <span class="text-white-80 small text-uppercase tracking-wide">Administrators</span>
                </div>
                <div class="icon-wrapper bg-white-20 rounded-circle p-3">
                    <i class="icon-pointer icon-2x text-white"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 px-xl-4">
                <a href="#" class="text-white-80 text-decoration-none small d-flex align-items-center">
                    <span>View Details</span>
                    <i class="icon-arrow-right14 ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Total Parents Card -->
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card dashboard-card bg-gradient-indigo h-100 border-0 shadow-lg">
            <div class="card-body d-flex align-items-center justify-content-between p-3 p-xl-4">
                <div>
                    <h2 class="fw-bold text-white mb-1 display-6">{{ $users->where('user_type', 'parent')->count() }}</h2>
                    <span class="text-white-80 small text-uppercase tracking-wide">Total Parents</span>
                </div>
                <div class="icon-wrapper bg-white-20 rounded-circle p-3">
                    <i class="icon-user icon-2x text-white"></i>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-3 px-xl-4">
                <a href="#" class="text-white-80 text-decoration-none small d-flex align-items-center">
                    <span>View Details</span>
                    <i class="icon-arrow-right14 ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endif

{{-- Events Calendar Section --}}
<div class="card shadow-lg border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row align-items-start align-items-sm-center justify-content-between">
        <h5 class="card-title fw-bold mb-2 mb-sm-0">
            <i class="icon-calendar3 text-primary me-2"></i>
            School Events Calendar
        </h5>
        <div class="d-flex align-items-center">
            {!! Qs::getPanelOptions() !!}
        </div>
    </div>

    <div class="card-body p-0 p-md-3">
        <div class="fullcalendar-basic"></div>
    </div>
</div>

<style>
/* Dashboard Cards Styling */
.dashboard-card {
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    position: relative;
    overflow: hidden;
    border-radius: 1.25rem !important;
    cursor: pointer;
}

.dashboard-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.25) !important;
}

.dashboard-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 100%);
    pointer-events: none;
}

.dashboard-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
    opacity: 0;
    transition: opacity 0.4s ease;
    pointer-events: none;
}

.dashboard-card:hover::after {
    opacity: 1;
}

/* Gradient Backgrounds - Enhanced */
.bg-gradient-blue {
    background: linear-gradient(145deg, #4158D0 0%, #C850C0 46%, #FFCC70 100%);
    background-size: 200% 200%;
    animation: gradientShift 8s ease infinite;
}

.bg-gradient-danger {
    background: linear-gradient(145deg, #FA8BFF 0%, #2BD2FF 52%, #2BFF88 100%);
    background-size: 200% 200%;
    animation: gradientShift 8s ease infinite;
}

.bg-gradient-success {
    background: linear-gradient(145deg, #0093E9 0%, #80D0C7 100%);
    background-size: 200% 200%;
    animation: gradientShift 8s ease infinite;
}

.bg-gradient-indigo {
    background: linear-gradient(145deg, #A9C9FF 0%, #FFBBEC 100%);
    background-size: 200% 200%;
    animation: gradientShift 8s ease infinite;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Icon Wrapper - Enhanced */
.icon-wrapper {
    width: 65px;
    height: 65px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    position: relative;
    z-index: 1;
}

.dashboard-card:hover .icon-wrapper {
    transform: scale(1.15) rotate(8deg);
    background: rgba(255, 255, 255, 0.25) !important;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.bg-white-20 {
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.1);
}

/* Text Colors - Enhanced */
.text-white-80 {
    color: rgba(255, 255, 255, 0.95);
    font-weight: 400;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.text-white-80:hover {
    color: white;
    text-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Typography - Enhanced */
.tracking-wide {
    letter-spacing: 0.8px;
    font-weight: 500;
    text-transform: uppercase;
    font-size: 0.75rem;
    opacity: 0.9;
}

.display-6 {
    font-size: calc(1.8rem + 1.2vw);
    line-height: 1.1;
    font-weight: 700;
    text-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Calendar Card - Enhanced */
.card {
    border-radius: 1.25rem;
    overflow: hidden;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25) !important;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    background: linear-gradient(to bottom, #ffffff, #fafafa);
}

/* Panel Options Styling */
.btn-group > .btn {
    border-radius: 8px !important;
    margin: 0 2px;
    padding: 0.5rem 1rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-group > .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive Adjustments - Enhanced */
@media (max-width: 575.98px) {
    .dashboard-card .card-body {
        padding: 1.5rem !important;
    }
    
    .icon-wrapper {
        width: 55px;
        height: 55px;
    }
    
    .icon-2x {
        font-size: 1.75rem !important;
    }
    
    .display-6 {
        font-size: 2rem;
    }
    
    .card-header {
        padding: 1.25rem !important;
    }
    
    .tracking-wide {
        font-size: 0.7rem;
    }
}

@media (min-width: 576px) and (max-width: 767.98px) {
    .dashboard-card .card-body {
        padding: 1.75rem !important;
    }
    
    .icon-wrapper {
        width: 60px;
        height: 60px;
    }
}

@media (min-width: 768px) and (max-width: 991.98px) {
    .icon-wrapper {
        width: 60px;
        height: 60px;
    }
}

/* Grid Gap */
.g-3, .gy-3 {
    --bs-gap: 1.25rem;
}

/* Calendar Loading Animation */
.fullcalendar-basic {
    min-height: 500px;
    position: relative;
}

.fullcalendar-basic::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 50px;
    height: 50px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.fullcalendar-basic.loading::before {
    opacity: 1;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

@media (max-width: 767.98px) {
    .fullcalendar-basic {
        min-height: 450px;
        font-size: 0.875rem;
    }
    
    /* Enhanced mobile calendar styling */
    .fc-toolbar {
        flex-direction: column;
        gap: 1rem;
        padding: 0.5rem;
    }
    
    .fc-toolbar .fc-left,
    .fc-toolbar .fc-center,
    .fc-toolbar .fc-right {
        float: none !important;
        text-align: center !important;
        margin: 0 !important;
    }
    
    .fc-button-group {
        margin: 0.25rem !important;
        display: inline-flex;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .fc button {
        padding: 0.5rem 0.75rem !important;
        font-size: 0.8125rem !important;
        border-radius: 6px !important;
        border: none;
        background: #f8f9fa;
        color: #495057;
        font-weight: 500;
    }
    
    .fc button:hover {
        background: #e9ecef;
    }
    
    .fc-state-active {
        background: #007bff !important;
        color: white !important;
    }
    
    .fc-toolbar-title {
        font-size: 1.25rem !important;
        font-weight: 600;
        color: #2c3e50;
    }
}

/* Card Footer Links - Enhanced */
.card-footer a {
    opacity: 0.85;
    transition: all 0.2s ease;
    font-weight: 500;
    padding: 0.25rem 0;
}

.card-footer a:hover {
    opacity: 1;
    transform: translateX(5px);
}

/* Animation for Cards - Enhanced */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.row > [class*="col-"] {
    animation: slideInUp 0.6s cubic-bezier(0.165, 0.84, 0.44, 1) forwards;
    opacity: 0;
}

.row > [class*="col-"]:nth-child(1) { animation-delay: 0.1s; }
.row > [class*="col-"]:nth-child(2) { animation-delay: 0.2s; }
.row > [class*="col-"]:nth-child(3) { animation-delay: 0.3s; }
.row > [class*="col-"]:nth-child(4) { animation-delay: 0.4s; }

.card:not([class*="col-"]) {
    animation: fadeIn 0.8s ease forwards;
}

/* Additional responsive fixes - Enhanced */
@media (max-width: 767.98px) {
    .fc-header-toolbar {
        display: flex;
        flex-direction: column;
        gap: 15px;
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .fc-toolbar-chunk {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .fc-toolbar-title {
        font-size: 1.35rem !important;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .fc-view-container {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    /* Enhance calendar cells on mobile */
    .fc-day-grid .fc-row {
        min-height: 3em !important;
    }
    
    .fc-day-number {
        padding: 6px !important;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .fc-event {
        padding: 2px 4px !important;
        font-size: 0.75rem !important;
        border-radius: 4px !important;
        margin: 1px 2px !important;
    }
}

/* Hover effect for calendar events */
.fc-event {
    transition: all 0.2s ease;
    cursor: pointer;
    border: none !important;
    background: linear-gradient(145deg, #3498db, #2980b9);
    padding: 3px 6px !important;
    margin: 2px 3px !important;
    border-radius: 6px !important;
    box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
}

.fc-event:hover {
    transform: translateY(-1px) scale(1.02);
    box-shadow: 0 5px 12px rgba(52, 152, 219, 0.4);
}

/* Custom scrollbar for calendar */
.fc-scroller {
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

.fc-scroller::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.fc-scroller::-webkit-scrollbar-track {
    background: #f7fafc;
    border-radius: 10px;
}

.fc-scroller::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 10px;
}

.fc-scroller::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Today highlight enhancement */
.fc-today {
    background: rgba(52, 152, 219, 0.05) !important;
}

.fc-day-number.fc-today {
    background: #3498db !important;
    color: white !important;
    border-radius: 50% !important;
    width: 28px;
    height: 28px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 2px;
    font-weight: bold;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add loading class initially
    const calendarEl = document.querySelector('.fullcalendar-basic');
    if (calendarEl) {
        calendarEl.classList.add('loading');
    }
    
    // Add responsive behavior to calendar
    if (calendarEl) {
        // Check if FullCalendar is initialized
        const checkCalendar = setInterval(function() {
            if (typeof $ !== 'undefined' && $.fn.fullCalendar) {
                clearInterval(checkCalendar);
                
                // Remove loading class
                calendarEl.classList.remove('loading');
                
                // Get calendar instance
                const calendar = $(calendarEl).fullCalendar('getCalendar');
                
                // Adjust view based on screen size
                function adjustCalendarView() {
                    if (window.innerWidth < 768) {
                        calendar.changeView('listMonth');
                    } else {
                        calendar.changeView('month');
                    }
                }
                
                // Initial adjustment
                adjustCalendarView();
                
                // Adjust on resize with debounce
                let resizeTimer;
                window.addEventListener('resize', function() {
                    calendarEl.classList.add('loading');
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(function() {
                        adjustCalendarView();
                        calendarEl.classList.remove('loading');
                    }, 250);
                });
                
                // Add smooth transitions for view changes
                $(calendarEl).on('viewRender', function() {
                    setTimeout(function() {
                        calendarEl.classList.remove('loading');
                    }, 100);
                });
            }
        }, 100);
    }
    
    // Add ripple effect to cards
    const cards = document.querySelectorAll('.dashboard-card');
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            ripple.classList.add('ripple-effect');
            this.appendChild(ripple);
            
            const x = e.clientX - e.target.offsetLeft;
            const y = e.clientY - e.target.offsetTop;
            
            ripple.style.left = `${x}px`;
            ripple.style.top = `${y}px`;
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
});

// Add ripple effect styles dynamically
const style = document.createElement('style');
style.textContent = `
    .ripple-effect {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        transform: scale(0);
        animation: ripple 0.6s linear;
        pointer-events: none;
    }
    
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection