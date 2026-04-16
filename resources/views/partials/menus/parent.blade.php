<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <!-- Mobile Toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        <span class="sidebar-mobile-title">Navigation Menu</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>

    <div class="sidebar-content">
        <!-- User Profile Section -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="user-avatar-wrapper me-3">
                        <a href="{{ route('my_account') }}" class="user-avatar-link">
                            <img src="{{ Auth::user()->photo }}" 
                                 width="48" 
                                 height="48" 
                                 class="rounded-circle border border-2 border-white shadow-sm" 
                                 alt="{{ Auth::user()->name }}"
                                 onerror="this.src='{{ asset('global_assets/images/placeholder.jpg') }}'">
                            <span class="avatar-status online"></span>
                        </a>
                    </div>
                    <div class="user-info flex-grow-1">
                        <div class="user-name font-weight-semibold">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="user-role">
                            <i class="icon-user-tie font-size-xs me-1"></i>
                            <span class="font-size-xs opacity-75">Parent</span>
                        </div>
                    </div>
                    <div class="user-actions">
                        <a href="{{ route('my_account') }}" class="text-white-50" title="Edit Profile">
                            <i class="icon-pencil3"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" 
                       class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <i class="icon-home4"></i>
                        </div>
                        <span class="nav-text">Dashboard</span>
                        @if(Route::is('dashboard'))
                            <span class="active-indicator"></span>
                        @endif
                    </a>
                </li>

                <!-- My Children -->
                <li class="nav-item">
                    <a href="{{ route('my_children') }}" 
                       class="nav-link {{ in_array(Route::currentRouteName(), ['my_children']) ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <i class="icon-users4"></i>
                        </div>
                        <span class="nav-text">My Children</span>
                        @if(in_array(Route::currentRouteName(), ['my_children']))
                            <span class="active-indicator"></span>
                        @endif
                    </a>
                </li>

                <!-- Academic Timetable -->
                <li class="nav-item">
                    <a href="{{ route('tt.index') }}" 
                       class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <i class="icon-calendar3"></i>
                        </div>
                        <span class="nav-text">Timetable</span>
                        @if(in_array(Route::currentRouteName(), ['tt.index']))
                            <span class="active-indicator"></span>
                        @endif
                    </a>
                </li>

                <!-- My Account -->
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" 
                       class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}">
                        <div class="nav-icon-wrapper">
                            <i class="icon-user"></i>
                        </div>
                        <span class="nav-text">My Account</span>
                        @if(in_array(Route::currentRouteName(), ['my_account']))
                            <span class="active-indicator"></span>
                        @endif
                    </a>
                </li>
            </ul>

            <!-- Footer Section -->
            <div class="sidebar-footer mt-auto p-3">
                <div class="text-center">
                    <small class="opacity-50">
                        <i class="icon-clock"></i>
                        Last login: {{ Auth::user()->last_login_at ? date('M d, H:i', strtotime(Auth::user()->last_login_at)) : 'Today' }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Sidebar Dark Theme Enhancement */
.sidebar-dark {
    background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);
}

/* Mobile Toggler */
.sidebar-mobile-toggler {
    background: rgba(0, 0, 0, 0.2);
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-mobile-title {
    font-weight: 500;
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.sidebar-mobile-toggler a {
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
}

.sidebar-mobile-toggler a:hover {
    color: white;
    transform: scale(1.1);
}

/* User Profile Section */
.sidebar-user {
    background: rgba(0, 0, 0, 0.15);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.user-avatar-wrapper {
    position: relative;
}

.user-avatar-link {
    display: block;
    transition: transform 0.3s ease;
}

.user-avatar-link:hover {
    transform: scale(1.05);
}

.avatar-status {
    position: absolute;
    bottom: 2px;
    right: 2px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #1e3c72;
}

.avatar-status.online {
    background: #28a745;
}

.avatar-status.away {
    background: #ffc107;
}

.avatar-status.busy {
    background: #dc3545;
}

.user-name {
    font-size: 0.9375rem;
    font-weight: 600;
    color: white;
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.user-role {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.7);
}

.user-actions a {
    opacity: 0.6;
    transition: all 0.3s ease;
}

.user-actions a:hover {
    opacity: 1;
    transform: rotate(15deg);
}

/* Navigation Items */
.nav-sidebar {
    padding: 0.5rem 0;
}

.nav-sidebar .nav-item {
    margin: 0.25rem 0;
    position: relative;
}

.nav-sidebar .nav-link {
    padding: 0.75rem 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    transition: all 0.3s ease;
    border-radius: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.nav-sidebar .nav-link:hover {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding-left: 1.5rem;
}

.nav-sidebar .nav-link.active {
    background: linear-gradient(90deg, rgba(255, 255, 255, 0.15), transparent);
    color: white;
    border-left: 3px solid #fff;
}

.nav-sidebar .nav-link.active .nav-icon-wrapper i {
    transform: scale(1.1);
}

/* Icon Wrapper */
.nav-icon-wrapper {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.nav-icon-wrapper i {
    font-size: 1.125rem;
    transition: all 0.3s ease;
}

.nav-sidebar .nav-link:hover .nav-icon-wrapper {
    transform: translateX(3px);
}

/* Nav Text */
.nav-text {
    flex: 1;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Active Indicator */
.active-indicator {
    width: 6px;
    height: 6px;
    background: white;
    border-radius: 50%;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
    }
    70% {
        box-shadow: 0 0 0 6px rgba(255, 255, 255, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
    }
}

/* Sidebar Footer */
.sidebar-footer {
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.6);
}

/* Card Sidebar Mobile */
.card-sidebar-mobile {
    background: transparent !important;
    box-shadow: none !important;
    display: flex;
    flex-direction: column;
    min-height: calc(100vh - 180px);
}

/* Responsive Adjustments */
@media (max-width: 767.98px) {
    .sidebar-user .card-body {
        padding: 1rem;
    }
    
    .user-avatar-link img {
        width: 40px !important;
        height: 40px !important;
    }
    
    .user-name {
        font-size: 0.875rem;
    }
    
    .nav-sidebar .nav-link {
        padding: 0.625rem 1rem;
    }
    
    .nav-icon-wrapper {
        width: 28px;
        height: 28px;
    }
    
    .nav-icon-wrapper i {
        font-size: 1rem;
    }
    
    .nav-text {
        font-size: 0.8125rem;
    }
}

@media (min-width: 768px) {
    .sidebar-mobile-toggler {
        display: none;
    }
}

/* Collapsed Sidebar Support */
.sidebar-collapsed .sidebar-user .card-body {
    padding: 1rem 0.5rem;
}

.sidebar-collapsed .user-info,
.sidebar-collapsed .user-actions,
.sidebar-collapsed .nav-text,
.sidebar-collapsed .active-indicator,
.sidebar-collapsed .sidebar-footer {
    display: none;
}

.sidebar-collapsed .user-avatar-wrapper {
    margin-right: 0 !important;
}

.sidebar-collapsed .user-avatar-link img {
    width: 36px !important;
    height: 36px !important;
}

.sidebar-collapsed .nav-link {
    justify-content: center;
    padding: 0.75rem;
}

.sidebar-collapsed .nav-icon-wrapper {
    margin: 0;
}

.sidebar-collapsed .nav-link:hover {
    padding-left: 0.75rem !important;
}

/* Scrollbar Styling */
.sidebar-content::-webkit-scrollbar {
    width: 5px;
}

.sidebar-content::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.1);
}

.sidebar-content::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 5px;
}

.sidebar-content::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
}

/* Hover Effects */
.nav-sidebar .nav-link {
    position: relative;
    overflow: hidden;
}

.nav-sidebar .nav-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: rgba(255, 255, 255, 0.05);
    transition: width 0.3s ease;
    z-index: -1;
}

.nav-sidebar .nav-link:hover::before {
    width: 100%;
}

/* Focus States for Accessibility */
.nav-sidebar .nav-link:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.1);
    color: white;
}

/* Animation for Active State */
.nav-sidebar .nav-link.active {
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

/* Tooltip for Collapsed Mode */
@media (max-width: 768px) {
    .nav-sidebar .nav-link {
        -webkit-tap-highlight-color: transparent;
    }
    
    .nav-sidebar .nav-link:active {
        transform: scale(0.98);
        transition: transform 0.1s ease;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle sidebar toggle animation
    const sidebarToggle = document.querySelector('.sidebar-mobile-main-toggle');
    const sidebarExpand = document.querySelector('.sidebar-mobile-expand');
    
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-mobile-main-open');
        });
    }
    
    if (sidebarExpand) {
        sidebarExpand.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-expanded-on-mobile');
        });
    }
    
    // Add ripple effect on nav items for mobile
    const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Don't add ripple if already active or if it's a dropdown
            if (this.classList.contains('active') || this.getAttribute('data-toggle') === 'dropdown') {
                return;
            }
            
            const ripple = document.createElement('span');
            ripple.classList.add('nav-ripple');
            this.appendChild(ripple);
            
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size/2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size/2) + 'px';
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Set user status based on activity (optional)
    function updateUserStatus() {
        const statusDot = document.querySelector('.avatar-status');
        if (statusDot) {
            // You can implement logic to check user activity
            statusDot.classList.add('online');
        }
    }
    
    updateUserStatus();
    
    // Handle window resize for sidebar state
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            if (window.innerWidth >= 768) {
                document.body.classList.remove('sidebar-mobile-main-open');
            }
        }, 250);
    });
});

// Ripple effect CSS
const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
    .nav-ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: scale(0);
        animation: navRipple 0.6s linear;
        pointer-events: none;
        z-index: 1;
    }
    
    @keyframes navRipple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .nav-sidebar .nav-link {
        position: relative;
        overflow: hidden;
    }
`;
document.head.appendChild(rippleStyle);
</script>