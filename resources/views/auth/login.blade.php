@extends('layouts.login_master')

@section('content')
    <div class="page-content login-cover min-vh-100 d-flex align-items-center">
        <!-- Main content -->
        <div class="content-wrapper w-100">
            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center p-3 p-md-4">
                <!-- Login card -->
                <form class="login-form w-100" method="post" action="{{ route('login') }}" style="max-width: 420px;">
                    @csrf
                    <div class="card mb-0 shadow-lg border-0 rounded-3 overflow-hidden">
                        <div class="card-body p-4 p-md-5">
                            <!-- Header Icon -->
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center bg-warning-100 rounded-circle p-3 mb-3">
                                    <i class="icon-people icon-2x text-warning" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="mb-1 fw-bold">Welcome Back!</h4>
                                <p class="text-muted mb-0">Sign in to continue to {{ Qs::getSystemName() }}</p>
                            </div>

                            <!-- Error Messages -->
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                                    <div class="d-flex align-items-center">
                                        <i class="icon-warning2 me-2"></i>
                                        <div>
                                            <strong class="d-block">Oops! Something went wrong:</strong>
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
                                <label class="form-label text-muted small fw-semibold mb-2">Login ID or Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-user text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" 
                                           name="identity" value="{{ old('identity') }}" 
                                           placeholder="Enter your username or email"
                                           required autofocus>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label text-muted small fw-semibold">Password</label>
                                    <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">
                                        Forgot?
                                    </a>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="icon-lock text-muted"></i>
                                    </span>
                                    <input required name="password" type="password" 
                                           class="form-control border-start-0 ps-0" 
                                           placeholder="Enter your password"
                                           id="password">
                                    <button class="btn btn-light border" type="button" 
                                            onclick="togglePassword()" 
                                            style="cursor: pointer;">
                                        <i class="icon-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                <div class="form-check">
                                    <input type="checkbox" name="remember" class="form-check-input" 
                                           id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted small" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                                    <span>Sign In</span>
                                    <i class="icon-circle-right2"></i>
                                </button>
                            </div>

                            <!-- Demo Credentials (Optional - you can remove this) -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="small text-muted mb-2">Demo Credentials:</p>
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    <span class="badge bg-light text-dark p-2">Admin: admin@demo.com</span>
                                    <span class="badge bg-light text-dark p-2">Password: secret</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        /* Page Background */
        .page-content.login-cover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            min-height: 100vh;
        }
        
        /* Optional overlay pattern */
        .page-content.login-cover::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" opacity="0.1"><path d="M10 10 L90 10 L90 90 L10 90 Z" fill="none" stroke="white" stroke-width="0.5"/></svg>');
            background-size: 30px 30px;
            pointer-events: none;
        }

        /* Card Styling */
        .login-form .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            transition: transform 0.3s ease;
        }

        .login-form .card:hover {
            transform: translateY(-5px);
        }

        /* Input Group Styling */
        .input-group-text {
            background: #f8f9fa;
            border-right: none;
        }

        .input-group .form-control {
            border-left: none;
            background: #f8f9fa;
        }

        .input-group .form-control:focus {
            background: #fff;
            border-color: #ced4da;
            box-shadow: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: #ced4da;
        }

        /* Password Toggle Button */
        .btn-light.border {
            background: #f8f9fa;
            border-color: #ced4da;
            border-left: none;
        }

        .btn-light.border:hover {
            background: #e9ecef;
        }

        /* Button Styling */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        /* Responsive Adjustments */
        @media (max-width: 576px) {
            .login-form {
                max-width: 100% !important;
            }
            
            .card-body {
                padding: 2rem !important;
            }
            
            .btn-primary {
                padding: 0.75rem !important;
            }
            
            .icon-2x {
                font-size: 2rem !important;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-form {
            animation: fadeInUp 0.6s ease;
        }

        /* Checkbox Styling */
        .form-check-input {
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        /* Alert Styling */
        .alert-danger {
            background: #fff2f0;
            border: none;
            border-left: 4px solid #dc3545;
            color: #dc3545;
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
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(function() {
                            alert.remove();
                        }, 500);
                    }
                }, 5000);
            }
        });
    </script>
@endsection