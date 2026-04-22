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
                            <img src="{{ Auth::user()->photo }}" width="48" height="48" class="rounded-circle" alt="photo" style="border: 2px solid rgba(255,255,255,0.2);">
                        </a>
                    </div>
                    <div class="media-body">
                        <div class="media-title font-weight-semibold text-white">{{ Auth::user()->name }}</div>
                        <div class="font-size-xs opacity-75">
                            <i class="fas fa-user-graduate font-size-sm"></i> &nbsp;Student
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
                @include('pages.student.menu')
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}">
                        <i class="fas fa-user-circle"></i>
                        <span>My Account</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Modern Sidebar Styling - Matching Admin Style */
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
    
    .sidebar-user .media-title {
        font-weight: 600;
        font-size: 14px;
        color: white;
        margin-bottom: 4px;
    }
    
    .sidebar-user .opacity-75 {
        color: rgba(255, 255, 255, 0.75);
        font-size: 12px;
    }
    
    .text-white-50 {
        color: rgba(255, 255, 255, 0.5);
    }
    
    .hover-text-white:hover {
        color: white !important;
    }
    
    /* Navigation Items */
    .nav-sidebar {
        padding: 8px 0;
    }
    
    .nav-sidebar .nav-item {
        margin: 2px 0;
    }
    
    .nav-sidebar .nav-link {
        padding: 12px 16px;
        margin: 4px 12px;
        border-radius: 12px;
        transition: all 0.2s ease;
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
        transition: all 0.2s ease;
        color: rgba(167, 139, 250, 0.7);
    }
    
    .nav-sidebar .nav-link span {
        flex: 1;
    }
    
    /* Hover Effects */
    .nav-sidebar .nav-link:hover {
        background: rgba(99, 102, 241, 0.2);
        color: white;
        transform: translateX(4px);
    }
    
    .nav-sidebar .nav-link:hover i {
        transform: scale(1.1);
        color: #a78bfa;
    }
    
    /* Active Link */
    .nav-sidebar .nav-link.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }
    
    .nav-sidebar .nav-link.active i {
        color: white;
        transform: scale(1);
    }
    
    /* Card Styling */
    .card-sidebar-mobile {
        background: transparent !important;
        box-shadow: none !important;
        border: none !important;
    }
    
    /* Scrollbar */
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
    
    .sidebar-content::-webkit-scrollbar-thumb:hover {
        background: rgba(99, 102, 241, 0.8);
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .sidebar-dark {
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 100%);
        }
        
        .nav-sidebar .nav-link {
            margin: 2px 8px;
            padding: 10px 14px;
        }
        
        .sidebar-user .card-body {
            padding: 16px;
        }
        
        .sidebar-user img {
            width: 40px;
            height: 40px;
        }
    }
    
    /* Animation for active link */
    @keyframes activePulse {
        0% {
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
        50% {
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.5);
        }
        100% {
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }
    }
    
    .nav-sidebar .nav-link.active {
        animation: activePulse 2s infinite;
    }
    
    /* Smooth transitions */
    .sidebar,
    .sidebar-content,
    .nav-sidebar .nav-link {
        transition: all 0.3s ease;
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