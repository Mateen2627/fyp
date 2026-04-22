<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark py-2 py-md-3">
    <div class="container-fluid px-3 px-md-4">
        <!-- Logo/Brand -->
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center py-0">
            <div class="brand-icon me-2">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h4 class="text-bold text-white mb-0 fs-4 fs-md-3 brand-text">SPAMS</h4>
            <span class="brand-badge ms-2 d-none d-sm-inline-block">v2.0</span>
        </a>

        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-toggle="collapse" data-target="#navbar-mobile" aria-controls="navbar-mobile" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbar-mobile">
            <ul class="navbar-nav ms-auto mt-3 mt-md-0">
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                        <i class="fas fa-home fs-5"></i>
                        <span class="d-md-none">Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                        <i class="fas fa-user-circle fs-5"></i>
                        <span class="d-md-none">My Account</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link px-3 py-2 d-flex align-items-center gap-2 dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fs-5"></i>
                        <span class="d-md-none">Options</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="fas fa-user me-2"></i>Profile Settings
                        </a>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="fas fa-bell me-2"></i>Notifications
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="fas fa-question-circle me-2"></i>Help Center
                        </a>
                        <a class="dropdown-item py-2 px-4 text-danger" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /main navbar -->

<style>
/* Additional custom styles for better mobile experience */
.navbar {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 50%, #1e1b4b 100%);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    position: sticky;
    top: 0;
    z-index: 1000;
    backdrop-filter: blur(10px);
}

/* Brand styling */
.navbar-brand {
    position: relative;
    transition: all 0.3s ease;
}

.navbar-brand:hover {
    transform: scale(1.02);
    opacity: 0.95;
}

.brand-icon {
    width: 36px;
    height: 36px;
    background: rgba(99, 102, 241, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.brand-icon i {
    font-size: 1.2rem;
    background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.navbar-brand:hover .brand-icon {
    background: rgba(99, 102, 241, 0.3);
    transform: rotate(5deg);
}

.brand-text {
    font-weight: 800;
    letter-spacing: 1px;
    background: linear-gradient(135deg, #ffffff 0%, #c7d2fe 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.brand-badge {
    background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
    padding: 2px 8px;
    border-radius: 20px;
    font-size: 0.65rem;
    font-weight: 600;
    color: white;
    letter-spacing: 0.5px;
}

/* Nav links styling */
.nav-link {
    font-weight: 500;
    font-size: 0.9rem;
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
}

.nav-link i {
    font-size: 1.1rem;
    transition: all 0.2s ease;
}

/* Desktop hover effects */
@media (min-width: 768px) {
    .nav-link {
        position: relative;
        transition: all 0.3s ease;
        margin: 0 2px;
    }
    
    .nav-link:hover {
        background: rgba(99, 102, 241, 0.2);
        transform: translateY(-2px);
    }
    
    .nav-link:hover i {
        transform: scale(1.1);
    }
    
    /* Active indicator */
    .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
        transition: all 0.3s ease;
        transform: translateX(-50%);
        border-radius: 2px;
    }
    
    .nav-link:hover::after {
        width: 70%;
    }
    
    /* Dropdown menu animation */
    .dropdown-menu {
        margin-top: 0.5rem;
        border-radius: 16px;
        animation: fadeInUp 0.3s ease;
        background: white;
        border: none;
        box-shadow: 0 20px 35px -10px rgba(0,0,0,0.15);
        overflow: hidden;
    }
    
    .dropdown-item {
        transition: all 0.2s ease;
        padding: 10px 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .dropdown-item i {
        width: 20px;
        color: #6366f1;
    }
    
    .dropdown-item:hover {
        background: linear-gradient(135deg, #f5f3ff 0%, #fdf2f8 100%);
        transform: translateX(5px);
        color: #4f46e5;
    }
    
    .dropdown-item.text-danger:hover {
        background: #fef2f2;
        color: #dc2626;
    }
}

/* Mobile responsive */
@media (max-width: 767.98px) {
    .navbar-brand {
        flex: 1;
    }
    
    .brand-text {
        font-size: 1.3rem;
    }
    
    .brand-icon {
        width: 32px;
        height: 32px;
    }
    
    .brand-icon i {
        font-size: 1rem;
    }
    
    .navbar-collapse {
        background: rgba(15, 23, 42, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 20px;
        margin-top: 12px;
        padding: 12px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    
    .nav-link {
        border-radius: 12px;
        margin: 4px 0;
        padding: 10px 16px !important;
    }
    
    .nav-link:hover {
        background: rgba(99, 102, 241, 0.2);
    }
    
    .dropdown-menu {
        border: none;
        background: rgba(255,255,255,0.05);
        margin-top: 0 !important;
        padding-left: 1.5rem;
        background: rgba(30, 41, 59, 0.9);
        backdrop-filter: blur(10px);
        border-radius: 12px;
    }
    
    .dropdown-item {
        color: rgba(255,255,255,0.8);
        border-radius: 8px;
        padding: 8px 16px;
    }
    
    .dropdown-item:hover {
        background: rgba(99, 102, 241, 0.3);
        color: white;
    }
    
    .dropdown-divider {
        border-top-color: rgba(255,255,255,0.1);
    }
    
    .navbar-toggler {
        padding: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 12px;
    }
    
    .navbar-toggler-icon {
        filter: brightness(0) invert(1);
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Smooth transitions */
.navbar-collapse {
    transition: all 0.3s ease;
}

.navbar-toggler:focus {
    outline: none;
    box-shadow: none;
}

/* Icon spacing */
.nav-link i {
    width: 1.5rem;
    text-align: center;
    transition: transform 0.2s ease;
}

/* Active state for current page */
.nav-item .nav-link[href="{{ route('login') }}"],
.nav-item .nav-link[href="{{ route('home') }}"] {
    position: relative;
}

.nav-item .nav-link[href="{{ route('login') }}"]::before,
.nav-item .nav-link[href="{{ route('home') }}"]::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 3px;
    height: 20px;
    background: linear-gradient(135deg, #a78bfa 0%, #f472b6 100%);
    border-radius: 3px;
}

/* Scroll effect */
.navbar.scrolled {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
    padding: 8px 0;
    transition: all 0.3s ease;
}
</style>

<script>
// Add scroll effect to navbar
document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.navbar');
    
    function handleScroll() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    }
    
    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check
});
</script>