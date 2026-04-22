@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover min-vh-100 d-flex align-items-center">
        <!-- Main content -->
        <div class="content-wrapper w-100">
            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center p-3 p-md-4">
                <!-- Login card -->
                <form class="login-form w-100" method="post" action="{{ route('login') }}" style="max-width: 440px;">
                    @csrf
                    <div class="card mb-0 shadow-xl border-0 rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            
                            <!-- Logo / Brand Header - Clean & Professional -->
                            <div class="text-center mb-4">
                                <!-- Removed the cluttered text, just clean brand -->
                                <div class="mb-3">
                                    <span class="brand-logo" style="font-size: 2.8rem; font-weight: 800; background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); -webkit-background-clip: text; background-clip: text; color: transparent;">SPAMS</span>
                                </div>
                                <h4 class="mb-1 fw-semibold" style="color: #1e293b;">Welcome back</h4>
                                <p class="text-muted small mb-0">Sign in to your account</p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="icon-warning2 me-2"></i>
                                        <div>
                                            <strong class="d-block">Access Error:</strong>
                                            @foreach ($errors->all() as $error)
                                                <span class="d-block small">{{ $error }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Login Form -->
                            <div class="form-group mb-3">
                                <label class="form-label text-dark small fw-semibold mb-2">Email or Username</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 ps-3">
                                        <i class="icon-envelope5 text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-1 bg-transparent" 
                                           name="identity" value="{{ old('identity') }}" 
                                           placeholder="Enter your email or username"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="form-group mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-dark small fw-semibold mb-0">Password</label>
                                    <a href="{{ route('password.request') }}" class="small text-decoration-none" style="color: #6366f1;">
                                        Forgot password?
                                    </a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent border-end-0 ps-3">
                                        <i class="icon-lock2 text-muted"></i>
                                    </span>
                                    <input required name="password" type="password" 
                                           class="form-control border-start-0 ps-1 bg-transparent" 
                                           placeholder="Enter your password"
                                           id="password">
                                    <button class="btn btn-link text-muted border-0" type="button" 
                                            onclick="togglePassword()" 
                                            style="cursor: pointer; text-decoration: none;">
                                        <i class="icon-eye" id="togglePasswordIcon" style="font-size: 1rem;"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" 
                                           id="remember" {{ old('remember') ? 'checked' : '' }}
                                           style="border-color: #cbd5e1;">
                                    <label class="form-check-label text-muted small" for="remember">
                                        Keep me signed in
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #6366f1 0%, #ec4899 100%); border: none; border-radius: 12px;">
                                    <span>Sign In</span>
                                    <i class="icon-arrow-right8"></i>
                                </button>
                            </div>

                            <!-- Demo Credentials - Clean & Minimal -->
                            <div class="text-center mt-4 pt-3">
                                <p class="small text-muted mb-2">Demo Access</p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <span class="px-2 py-1" style="background: #f8fafc; border-radius: 8px; font-size: 0.7rem; color: #475569;">admin@demo.com</span>
                                    <span class="px-2 py-1" style="background: #f8fafc; border-radius: 8px; font-size: 0.7rem; color: #475569;">secret</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Professional Gradient Background */
        .page-content.login-cover {
            background: linear-gradient(145deg, #0f172a 0%, #1e1b4b 50%, #2e1065 100%);
            position: relative;
            min-height: 100vh;
        }
        
        /* Animated gradient overlay */
        .page-content.login-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 60%),
                        radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Optional floating particles effect */
        .page-content.login-cover::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="30" cy="30" r="2" fill="white" opacity="0.1"/><circle cx="170" cy="80" r="3" fill="white" opacity="0.08"/><circle cx="100" cy="150" r="2" fill="white" opacity="0.12"/><circle cx="50" cy="180" r="1.5" fill="white" opacity="0.1"/><circle cx="150" cy="20" r="2.5" fill="white" opacity="0.07"/></svg>');
            background-repeat: repeat;
            background-size: 40px 40px;
            pointer-events: none;
            opacity: 0.5;
        }

        /* Card Styling - Clean Glassmorphism */
        .login-form .card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(0px);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .login-form .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
        }

        /* Input Group Styling - Modern Minimal */
        .input-group-text {
            background: transparent;
            border: 1px solid #e2e8f0;
            border-right: none;
            color: #94a3b8;
        }

        .input-group .form-control {
            border: 1px solid #e2e8f0;
            border-left: none;
            background: transparent;
            font-size: 0.9rem;
            padding: 0.75rem 0.75rem 0.75rem 0.25rem;
        }

        .input-group .form-control:focus {
            background: transparent;
            border-color: #cbd5e1;
            box-shadow: none;
            outline: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: #cbd5e1;
        }

        .input-group .form-control::placeholder {
            color: #cbd5e1;
            font-size: 0.85rem;
        }

        /* Form Label */
        .form-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        /* Button Styling */
        .btn-primary {
            transition: all 0.3s ease;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
            background: linear-gradient(135deg, #4f46e5 0%, #db2777 100%) !important;
        }

        /* Checkbox Styling */
        .form-check-input {
            cursor: pointer;
            width: 1rem;
            height: 1rem;
            margin-top: 0.15rem;
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
        }

        /* Alert Styling */
        .alert-danger {
            background: #fef2f2;
            border: none;
            border-left: 3px solid #ef4444;
            color: #b91c1c;
            border-radius: 12px;
            font-size: 0.85rem;
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .login-form {
                max-width: 100% !important;
                margin: 0 16px;
            }
            
            .card-body {
                padding: 2rem 1.5rem !important;
            }
            
            .btn-primary {
                padding: 0.75rem !important;
            }
            
            .brand-logo {
                font-size: 2.2rem !important;
            }
        }

        /* Smooth Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form {
            animation: fadeInUp 0.5s ease-out;
        }

        /* Link Styling */
        a {
            transition: color 0.2s ease;
        }
        
        a:hover {
            color: #4f46e5 !important;
        }
        
        /* Icon inside password toggle */
        .btn-link:hover {
            background: transparent !important;
        }
        
        .btn-link:focus {
            box-shadow: none !important;
        }
    </style>

    <script>
        // Password visibility toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.className = 'icon-eye-blocked';
            } else {
                passwordInput.type = 'password';
                toggleIcon.className = 'icon-eye';
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alert = document.querySelector('.alert');
            if (alert) {
                setTimeout(function() {
                    if (alert) {
                        alert.style.transition = 'opacity 0.4s ease';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            if (alert && alert.remove) alert.remove();
                        }, 400);
                    }
                }, 5000);
            }
        });
    </script>
@endsection