<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-dark py-2 py-md-3">
    <div class="container-fluid px-3 px-md-4">
        <!-- Logo/Brand -->
        <a href="{{ route('dashboard') }}" class="navbar-brand d-flex align-items-center py-0">
            <h4 class="text-bold text-white mb-0 fs-4 fs-md-3">{{ Qs::getSystemName() }}</h4>
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
                        <i class="icon-home fs-5"></i>
                        <span class="d-md-none">Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('login') }}" class="nav-link px-3 py-2 d-flex align-items-center gap-2">
                        <i class="icon-user-tie fs-5"></i>
                        <span class="d-md-none">My Account</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link px-3 py-2 d-flex align-items-center gap-2 dropdown-toggle" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="icon-cog3 fs-5"></i>
                        <span class="d-md-none">Options</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="icon-cog3 me-2"></i>Option 1
                        </a>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="icon-cog3 me-2"></i>Option 2
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item py-2 px-4" href="#">
                            <i class="icon-cog3 me-2"></i>Option 3
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
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.navbar-brand h4 {
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

@media (max-width: 767.98px) {
    .navbar-brand h4 {
        font-size: 1.25rem;
    }
    
    .nav-link {
        border-radius: 8px;
        margin: 2px 0;
    }
    
    .nav-link:hover {
        background: rgba(255,255,255,0.1);
    }
    
    .dropdown-menu {
        border: none;
        background: rgba(255,255,255,0.05);
        margin-top: 0 !important;
        padding-left: 1.5rem;
    }
    
    .dropdown-item {
        color: rgba(255,255,255,0.8);
        border-radius: 6px;
    }
    
    .dropdown-item:hover {
        background: rgba(255,255,255,0.1);
        color: white;
    }
    
    .dropdown-divider {
        border-top-color: rgba(255,255,255,0.1);
    }
}

/* Desktop hover effects */
@media (min-width: 768px) {
    .nav-link {
        position: relative;
        transition: all 0.3s ease;
    }
    
    .nav-link:hover {
        background: rgba(255,255,255,0.1);
        transform: translateY(-1px);
    }
    
    .dropdown-menu {
        margin-top: 0.5rem;
        border-radius: 12px;
        animation: fadeInUp 0.3s ease;
    }
    
    .dropdown-item {
        transition: all 0.2s ease;
    }
    
    .dropdown-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }
}

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
}

/* Active state for current page */
.nav-item .nav-link[href="{{ route('login') }}"] {
    background: rgba(255,255,255,0.15);
    border-radius: 8px;
}
</style>