{{-- <!-- Make sure Font Awesome is loaded in your layout header -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md" id="mainSidebar">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle" id="closeSidebarBtn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <span class="sidebar-brand">NeoEdu</span>
        <a href="#" class="sidebar-mobile-expand" id="expandSidebarBtn">
            <i class="fas fa-expand"></i>
            <i class="fas fa-compress"></i>
        </a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img src="{{ Qs::getUserPhoto(Auth::user()) }}" width="48" height="48" class="rounded-circle" alt="photo">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-75">
                            <i class="fas fa-user-shield"></i> &nbsp;Administrator
                        </div>
                    </div>
                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white-50 hover-text-white">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.courses.index') }}" class="nav-link {{ Route::is('admin.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Manage Courses</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ Route::is('admin.classes.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i>
                        <span>Manage Classes</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.assign-teachers.index') }}" class="nav-link {{ Route::is('admin.assign-teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        <span>Assign Teachers</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}" class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.system-settings.index') }}" class="nav-link {{ Route::is('admin.system-settings.*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>System Settings</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
    /* Modern Sidebar Styling */
    .sidebar-dark {
        background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
        will-change: transform;
    }
    
    /* Sidebar Brand */
    .sidebar-mobile-toggler {
        background: rgba(0, 0, 0, 0.3);
        padding: 14px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }
    
    .sidebar-mobile-toggler .sidebar-brand {
        font-weight: 800;
        font-size: 18px;
        background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 1px;
    }
    
    .sidebar-mobile-toggler a {
        color: rgba(255, 255, 255, 0.6);
        transition: all 0.2s ease;
        font-size: 14px;
        cursor: pointer;
    }
    
    .sidebar-mobile-toggler a:hover {
        color: white;
        transform: scale(1.05);
    }
    
    /* User Profile Section */
    .sidebar-user {
        background: rgba(99, 102, 241, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 16px;
    }
    
    .sidebar-user .card-body {
        padding: 20px;
    }
    
    .sidebar-user .media {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .sidebar-user .media-title {
        font-weight: 600;
        font-size: 14px;
        color: white;
        margin-bottom: 4px;
    }
    
    .sidebar-user .opacity-75 {
        color: rgba(255, 255, 255, 0.75);
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .hover-text-white:hover {
        color: white !important;
    }
    
    /* Navigation Items */
    .card-sidebar-mobile {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
    }
    
    .nav-sidebar {
        padding: 8px 12px;
        list-style: none;
        margin: 0;
    }
    
    .nav-sidebar .nav-item {
        margin-bottom: 4px;
        list-style: none;
    }
    
    .nav-sidebar .nav-link {
        padding: 12px 14px;
        border-radius: 12px;
        transition: all 0.2s ease;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 14px;
        text-decoration: none;
    }
    
    .nav-sidebar .nav-link i {
        font-size: 18px;
        width: 24px;
        text-align: center;
        color: rgba(167, 139, 250, 0.8);
        transition: all 0.2s ease;
    }
    
    .nav-sidebar .nav-link span {
        flex: 1;
    }
    
    /* Hover Effects - Desktop only */
    @media (min-width: 769px) {
        .nav-sidebar .nav-link:hover {
            background: rgba(99, 102, 241, 0.15);
            color: white;
        }
        
        .nav-sidebar .nav-link:hover i {
            color: #a78bfa;
            transform: translateX(2px);
        }
    }
    
    /* Active Link */
    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }
    
    .nav-sidebar .nav-link.active i {
        color: white;
    }
    
    /* Scrollbar - Desktop only */
    @media (min-width: 769px) {
        .sidebar-content::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
        }
        
        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.5);
            border-radius: 4px;
        }
    }
    
    /* Profile image hover - Desktop only */
    @media (min-width: 769px) {
        .sidebar-user img {
            transition: all 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-user img:hover {
            transform: scale(1.05);
            border-color: #a78bfa;
        }
        
        /* Settings icon hover */
        .sidebar-user .ml-3 a {
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        .sidebar-user .ml-3 a:hover {
            transform: rotate(90deg);
        }
    }
    
    /* Sidebar Overlay */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        display: none;
    }
    
    .sidebar-overlay.active {
        display: block;
    }

    
    
    /* ========================================
       RESPONSIVE STYLES
    ======================================== */
    
    /* Desktop Styles - Sidebar visible by default */
    @media (min-width: 769px) {
        .sidebar-dark {
            width: 280px;
            position: sticky;
            top: 0;
            transform: translateX(0) !important;
        }
        
        .sidebar-overlay {
            display: none !important;
        }
    }
    
    /* Tablet & Mobile Styles */
    @media (max-width: 768px) {
        .sidebar-dark {
            width: 280px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 1050;
            transform: translateX(-100%);
        }
        
        .sidebar-dark.sidebar-open {
            transform: translateX(0);
        }
        
        .sidebar-overlay {
            display: none;
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        .sidebar-mobile-toggler {
            padding: 12px 16px;
        }
        
        .sidebar-mobile-toggler .sidebar-brand {
            font-size: 16px;
        }
        
        .sidebar-user .card-body {
            padding: 14px;
        }
        
        .sidebar-user img {
            width: 36px;
            height: 36px;
        }
        
        .nav-sidebar {
            padding: 8px 10px;
        }
        
        .nav-sidebar .nav-link {
            padding: 10px 12px;
        }
        
        .nav-sidebar .nav-link i {
            font-size: 16px;
            width: 22px;
        }
    }
    
    /* Small Mobile Devices */
    @media (max-width: 480px) {
        .sidebar-dark {
            width: 260px;
        }
        
        .sidebar-user img {
            width: 32px;
            height: 32px;
        }
        
        .nav-sidebar .nav-link {
            padding: 8px 10px;
        }
        
        .nav-sidebar .nav-link i {
            font-size: 14px;
            width: 20px;
        }
    }
</style>

<script>
    (function() {
        'use strict';
        
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('mainSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const expandBtn = document.getElementById('expandSidebarBtn');
            
            // Function to open sidebar
            function openSidebar() {
                if (sidebar) {
                    sidebar.classList.add('sidebar-open');
                    if (overlay) overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            }
            
            // Function to close sidebar
            function closeSidebar() {
                if (sidebar) {
                    sidebar.classList.remove('sidebar-open');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
            
            // Toggle function
            function toggleSidebar() {
                if (sidebar && sidebar.classList.contains('sidebar-open')) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
            
            // Close button click (the arrow icon)
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    closeSidebar();
                });
            }
            
            // Expand button click
            if (expandBtn) {
                expandBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    // Optional: Add expand/collapse functionality here
                    console.log('Expand button clicked');
                });
            }
            
            // Close sidebar when clicking overlay
            if (overlay) {
                overlay.addEventListener('click', function() {
                    closeSidebar();
                });
            }
            
            // Handle mobile toggle from navbar (if exists)
            const mobileNavToggle = document.querySelector('.sidebar-mobile-main-toggle');
            if (mobileNavToggle) {
                mobileNavToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
            
            // Also listen for navbar toggler that triggers sidebar
            const sideNavToggle = document.querySelector('[data-toggle="sidebar"]');
            if (sideNavToggle) {
                sideNavToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    toggleSidebar();
                });
            }
            
            // Close sidebar when clicking a nav link on mobile
            const navLinks = document.querySelectorAll('.nav-sidebar .nav-link');
            navLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(closeSidebar, 200);
                    }
                });
            });
            
            // Handle window resize
            let resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 768) {
                        closeSidebar();
                    }
                }, 250);
            });
            
            // Also handle the navbar toggler from top_menu
            const navbarToggler = document.querySelector('#mobileSidebarToggle');
            if (navbarToggler) {
                navbarToggler.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    toggleSidebar();
                });
            }
        });
    })();
</script> --}}


{{-- Admin sidebar — replace karo pura file content isse: --}}

<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="fas fa-arrow-left"></i>
        </a>
        <span class="sidebar-brand">NeoEdu</span>
        <a href="#" class="sidebar-mobile-expand">
            <i class="fas fa-expand"></i>
            <i class="fas fa-compress"></i>
        </a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img src="{{ Qs::getUserPhoto(Auth::user()) }}"
                                 width="48" height="48"
                                 class="rounded-circle" alt="photo"
                                 style="border: 2px solid rgba(255,255,255,0.2);"
                                 onerror="this.src='{{ Qs::getDefaultUserImage() }}'">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold text-white">
                            {{ Auth::user()->name }}
                        </div>
                        <div class="font-size-xs opacity-75">
                            <i class="fas fa-user-shield font-size-sm"></i> &nbsp;Administrator
                        </div>
                    </div>
                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}" class="text-white-50 hover-text-white">
                            <i class="fas fa-cog"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                       class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>Manage Users</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.courses.index') }}"
                       class="nav-link {{ Route::is('admin.courses.*') ? 'active' : '' }}">
                        <i class="fas fa-book"></i>
                        <span>Manage Courses</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.classes.index') }}"
                       class="nav-link {{ Route::is('admin.classes.*') ? 'active' : '' }}">
                        <i class="fas fa-chalkboard"></i>
                        <span>Manage Classes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.assign-teachers.index') }}"
                       class="nav-link {{ Route::is('admin.assign-teachers.*') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        <span>Assign Teachers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.reports.index') }}"
                       class="nav-link {{ Route::is('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Reports</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.system-settings.index') }}"
                       class="nav-link {{ Route::is('admin.system-settings.*') ? 'active' : '' }}">
                        <i class="fas fa-sliders-h"></i>
                        <span>System Settings</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</div>

<style>
    .sidebar-dark {
        background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
    }
    .sidebar-mobile-toggler .sidebar-brand {
        font-weight: 800;
        font-size: 18px;
        background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        letter-spacing: 1px;
    }
    .sidebar-user {
        background: rgba(99, 102, 241, 0.1);
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        margin-bottom: 16px;
    }
    .card-sidebar-mobile {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }
    .nav-sidebar .nav-link {
        padding: 12px 16px;
        margin: 4px 12px;
        border-radius: 12px;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .nav-sidebar .nav-link i {
        font-size: 1.1rem;
        width: 24px;
        text-align: center;
        color: rgba(167, 139, 250, 0.7);
    }
    .nav-sidebar .nav-link:hover {
        background: rgba(99, 102, 241, 0.2);
        color: white;
    }
    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }
    .nav-sidebar .nav-link.active i {
        color: white;
    }
    .text-white-50 { color: rgba(255, 255, 255, 0.5); }
    .hover-text-white:hover { color: white !important; }
</style>