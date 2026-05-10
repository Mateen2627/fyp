<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle"><i class="fas fa-arrow-left"></i></a>
        <span class="sidebar-brand">SPAMS</span>
        <a href="#" class="sidebar-mobile-expand"><i class="fas fa-expand"></i><i class="fas fa-compress"></i></a>
    </div>

    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}">
                            <img src="{{ Qs::getUserPhoto(Auth::user()) }}" width="48" height="48" class="rounded-circle" alt="photo" style="border: 2px solid rgba(255,255,255,0.2);">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-75">
                            <i class="fas fa-chalkboard-user"></i> &nbsp;Teacher
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
                @include('pages.teacher.menu')
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ Route::is('my_account') ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>My Account</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Modern Sidebar Styling - Teacher */
    .sidebar-dark {
        background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
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
    }
    
    .sidebar-mobile-toggler a:hover {
        color: white;
        transform: scale(1.1);
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
    
    /* Hover Effects */
    .nav-sidebar .nav-link:hover {
        background: rgba(99, 102, 241, 0.15);
        color: white;
    }
    
    .nav-sidebar .nav-link:hover i {
        color: #a78bfa;
        transform: translateX(2px);
    }
    
    /* Active Link */
    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
    }
    
    .nav-sidebar .nav-link.active i {
        color: white;
    }
    
    /* Scrollbar */
    .sidebar-content {
        height: 100vh;
        overflow-y: auto;
    }
    
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
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar-dark {
            width: 100%;
        }
        
        .sidebar-mobile-toggler {
            padding: 14px 16px;
        }
        
        .sidebar-user .card-body {
            padding: 16px;
        }
        
        .sidebar-user img {
            width: 40px;
            height: 40px;
        }
        
        .nav-sidebar {
            padding: 8px 8px;
        }
        
        .nav-sidebar .nav-link {
            padding: 10px 12px;
        }
        
        .nav-sidebar .nav-link i {
            font-size: 16px;
            width: 22px;
        }
    }
    
    /* Profile image hover */
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
</style>