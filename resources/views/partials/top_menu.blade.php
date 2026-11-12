@php
    $authUser = Auth::user();
    $studentRecord = Qs::userIsStudent() ? Qs::findStudentRecord($authUser->id) : null;
    $profileUrl = $studentRecord
        ? route('students.show', Qs::hash($studentRecord->id))
        : route('users.show', Qs::hash($authUser->id));
@endphp

<div class="navbar navbar-expand-md navbar-dark">
    <!-- Brand Area -->
    <div class="d-flex align-items-center">
       
        <a href="{{ route('dashboard') }}" class="brand-link">
            <span class="brand-text">NeoEdu</span>
        </a>
    </div>

    <!-- Mobile Toggle Buttons -->
    <div class="d-md-none d-flex gap-2">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile" aria-controls="navbar-mobile" aria-expanded="false" aria-label="Toggle account menu">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button" aria-label="Open navigation" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Navbar Collapsible Content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <!-- Left Side - Sidebar Toggle -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="nav-link sidebar-control sidebar-main-toggle d-none d-md-block" aria-label="Collapse or expand navigation" title="Collapse or expand navigation">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <!-- Center Spacer -->
        <span class="navbar-text ml-md-3 mr-md-auto"></span>

        <!-- Right Side - User Menu -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <button type="button" class="nav-link notification-settings" id="notification-permission-toggle" aria-pressed="false" title="Enable browser notifications">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span class="notification-settings-label">Enable alerts</span>
                </button>
            </li>
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
    
    /* Brand Icon */
    .brand-icon {
        width: 32px;
        height: 32px;
        background: #4f46e5;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .brand-icon i {
        font-size: 1rem;
        color: white;
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
    
    .brand-link:hover .brand-icon {
        background: #6366f1;
    }
    
    /* Navbar Toggler */
    .navbar-toggler {
        border: none;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 6px;
        padding: 6px 10px;
        transition: all 0.2s ease;
    }
    
    .navbar-toggler:hover {
        background: rgba(255, 255, 255, 0.1);
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
        border-radius: 6px;
        font-size: 0.85rem;
    }
    
    .navbar-nav .nav-link:hover {
        color: rgba(255, 255, 255, 1);
        background: rgba(255, 255, 255, 0.05);
    }
    
    /* User Avatar */
    .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        object-fit: cover;
        transition: all 0.2s ease;
    }
    
    .user-name {
        margin-left: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }
    
    /* Dropdown Menu */
    .dropdown-menu {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08);
        margin-top: 10px;
        min-width: 200px;
        padding: 8px 0;
    }
    
    /* Dropdown Items */
    .dropdown-item {
        padding: 8px 20px;
        font-size: 0.85rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #334155;
    }
    
    .dropdown-item i {
        width: 18px;
        font-size: 0.85rem;
        color: #94a3b8;
    }
    
    .dropdown-item:hover {
        background: #f8fafc;
        color: #1e293b;
    }
    
    .dropdown-item:hover i {
        color: #4f46e5;
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
    
    /* Responsive */
    @media (max-width: 767.98px) {
        .navbar {
            padding: 10px 16px;
        }
        
        .brand-text {
            font-size: 1.1rem;
        }
        
        .brand-icon {
            width: 28px;
            height: 28px;
        }
        
        .brand-icon i {
            font-size: 0.85rem;
        }
        
        .user-name {
            display: none;
        }
        
        .user-avatar {
            width: 28px;
            height: 28px;
        }
    }
    
    /* Desktop Hover */
    @media (min-width: 768px) {
        .dropdown-menu {
            margin-top: 12px;
        }
    }
</style>
