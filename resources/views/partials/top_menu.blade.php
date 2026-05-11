@php
    $authUser = Auth::user();
    $studentRecord = Qs::userIsStudent() ? Qs::findStudentRecord($authUser->id) : null;
    $profileUrl = $studentRecord
        ? route('students.show', Qs::hash($studentRecord->id))
        : route('users.show', Qs::hash($authUser->id));
@endphp
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="navbar navbar-expand-md navbar-dark" id="mainNavbar">
    <!-- Brand Area -->
    <div class="d-flex align-items-center">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text">NeoEdu</span>
        </a>
    </div>

    <!-- Mobile Toggle Buttons -->
    <div class="d-md-none d-flex gap-2">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button" id="mobileSidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navbar Collapsible Content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <!-- Left Side - Sidebar Toggle (Desktop only) -->
        <ul class="navbar-nav d-none d-md-flex">
            <li class="nav-item">
                <a href="#" class="nav-link sidebar-control sidebar-main-toggle" id="desktopSidebarToggle">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Center Spacer -->
        <span class="navbar-text ml-md-3 mr-md-auto"></span>

        <!-- Right Side - User Menu -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ Qs::getUserPhoto($authUser) }}" class="user-avatar" alt="photo" onerror="this.src='{{ Qs::getDefaultUserImage() }}'">
                    <span class="user-name">{{ $authUser->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ $profileUrl }}" class="dropdown-item">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="{{ route('my_account') }}" class="dropdown-item">
                        <i class="fas fa-cog"></i> Account Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item text-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>

<style>
    /* ========================================
       CLEAN PROFESSIONAL NAVBAR
    ======================================== */
    
    .navbar {
        background: #0f172a;
        padding: 12px 24px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        position: sticky;
        top: 0;
        z-index: 1030;
    }
    
    /* Brand Text */
    .brand-text {
        font-size: 1.25rem;
        font-weight: 600;
        color: white;
        letter-spacing: -0.3px;
        transition: opacity 0.2s ease;
    }
    
    .brand-link {
        text-decoration: none;
    }
    
    .brand-link:hover .brand-text {
        opacity: 0.8;
    }
    
    /* Navbar Toggler */
    .navbar-toggler {
        border: none;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        padding: 6px 12px;
        transition: all 0.2s ease;
    }
    
    .navbar-toggler:hover {
        background: rgba(255, 255, 255, 0.1);
    }
    
    .navbar-toggler:active {
        transform: scale(0.96);
    }
    
    .navbar-toggler i {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.7);
    }
    
    /* Nav Links */
    .navbar-nav .nav-link {
        color: rgba(255, 255, 255, 0.7);
        transition: all 0.2s ease;
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.85rem;
    }
    
    .navbar-nav .nav-link:hover {
        color: rgba(255, 255, 255, 1);
        background: rgba(255, 255, 255, 0.08);
    }
    
    .navbar-nav .nav-link:active {
        transform: scale(0.96);
    }
    
    /* User Avatar */
    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        object-fit: cover;
        transition: all 0.2s ease;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .user-name {
        margin-left: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }
    
    /* Dropdown Menu */
    .dropdown-menu {
        background: white;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 15px 30px -10px rgba(0, 0, 0, 0.12);
        margin-top: 12px;
        min-width: 220px;
        padding: 8px 0;
        animation: dropdownFade 0.2s ease;
    }
    
    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Dropdown Items */
    .dropdown-item {
        padding: 10px 20px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #334155;
    }
    
    .dropdown-item i {
        width: 18px;
        font-size: 0.85rem;
        color: #94a3b8;
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background: #f8fafc;
        color: #1e293b;
        padding-left: 26px;
    }
    
    .dropdown-item:hover i {
        color: #4f46e5;
        transform: translateX(2px);
    }
    
    .dropdown-item.text-danger:hover {
        background: #fef2f2;
        color: #dc2626;
    }
    
    .dropdown-item.text-danger:hover i {
        color: #dc2626;
    }
    
    .dropdown-divider {
        margin: 6px 0;
        border-top-color: #eef2ff;
    }
    
    /* Responsive Navbar */
    @media (max-width: 767.98px) {
        .navbar {
            padding: 10px 16px;
        }
        
        .brand-text {
            font-size: 1.1rem;
        }
        
        .user-name {
            display: none;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
        }
        
        .dropdown-menu {
            position: absolute;
            right: 0;
            left: auto;
            min-width: 240px;
            margin-top: 8px;
        }
        
        .navbar-collapse {
            max-height: calc(100vh - 60px);
            overflow-y: auto;
        }
        
        .navbar-nav .nav-link {
            padding: 10px 16px;
        }
    }
    
    /* Desktop */
    @media (min-width: 768px) {
        .dropdown-menu {
            margin-top: 10px;
        }
        
        .navbar-nav .nav-link {
            position: relative;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile sidebar toggle from navbar
        const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');
        const sidebar = document.getElementById('mainSidebar');
        
        if (mobileSidebarToggle && sidebar) {
            mobileSidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (sidebar.classList.contains('sidebar-open')) {
                    sidebar.classList.remove('sidebar-open');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (overlay) overlay.classList.remove('active');
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.add('sidebar-open');
                    const overlay = document.getElementById('sidebarOverlay');
                    if (overlay) overlay.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            });
        }
        
        // Close dropdown when clicking outside (improved mobile experience)
        document.addEventListener('click', function(e) {
            const dropdowns = document.querySelectorAll('.dropdown-menu');
            const toggles = document.querySelectorAll('.dropdown-toggle');
            
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(e.target) && !Array.from(toggles).some(toggle => toggle.contains(e.target))) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                        const parent = dropdown.closest('.dropdown');
                        if (parent) parent.classList.remove('show');
                    }
                }
            });
        });
    });
</script>