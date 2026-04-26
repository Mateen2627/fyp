<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="NeoEdu - Student Management and Evaluation System">
    <meta name="author" content="Your Institution Name">
    
    <title>{{ config('app.name', 'NeoEdu') }} | Secure Portal</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow: hidden;
            height: 100vh;
        }
        
        .brand-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
        }
        
        .accent-border {
            border-left: 4px solid #ffffff;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            transition: all 0.3s ease;
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #f472b6 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(79, 70, 229, 0.4);
        }
        
        /* Floating animation for illustrations */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .float-animation {
            animation: float 4s ease-in-out infinite;
        }
        
        .float-animation-delayed {
            animation: float 5s ease-in-out infinite 1s;
        }
        
        .float-animation-slow {
            animation: float 6s ease-in-out infinite 0.5s;
        }
        
        /* Custom focus ring */
        .focus-ring-custom:focus {
            ring: 2px solid #6366f1;
            ring-offset: 2px;
            outline: none;
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #8b5cf6;
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Illustration container */
        .illustration-container {
            position: relative;
            min-height: 160px;
            margin: 30px 0;
        }
        
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            filter: blur(20px);
        }
        
        /* Feature cards */
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateX(5px);
            background: rgba(255, 255, 255, 0.15);
        }
        
        /* Left side content - no scroll */
        .left-side-content {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        
        /* Scrollable right side */
        .right-side-scroll {
            overflow-y: auto;
            height: 100vh;
        }
        
        /* Hide scrollbar for cleaner look on left side */
        .left-side-content::-webkit-scrollbar {
            display: none;
        }
        
        /* Static logo - no animation */
        .logo-icon {
            transition: all 0.3s ease;
        }
        
        .logo-icon:hover {
            transform: scale(1.05);
        }
        
        /* Custom brand text styling */
        .brand-text {
            font-size: 1.75rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }
        
        @media (min-width: 768px) {
            .brand-text {
                font-size: 2rem;
            }
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 font-['Plus_Jakarta_Sans'] overflow-hidden">

    <div class="flex h-screen">
        <!-- Left Side - Brand Section with Animated Illustrations - NO SCROLL -->
        <div class="hidden lg:flex w-5/12 brand-gradient flex-col justify-between p-12 text-white relative left-side-content">
            <!-- Background Pattern -->
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
            
            <!-- Animated Floating Background Shapes -->
            <div class="floating-shape w-64 h-64 -top-20 -right-20"></div>
            <div class="floating-shape w-96 h-96 -bottom-40 -left-20 bg-white/5"></div>
            
            <!-- Top Section with Static Logo -->
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center shadow-lg logo-icon">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="brand-text font-bold tracking-tight">NeoEdu</span>
                </div>
            </div>
            
            <!-- Middle Section - All content fits without scroll -->
            <div class="relative z-10 flex-1 flex flex-col justify-center">
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    Student Performance Evaluation<br>
                    & Management System.
                </h1>
                <p class="text-white/80 text-base leading-relaxed mb-6">
                    A high-performance management system designed for real-time tracking with data-driven academic intelligence
                </p>
                
                <!-- Animated Illustrations below the text -->
                <div class="illustration-container">
                    <!-- Floating Student Illustration -->
                    <div class="float-animation absolute left-0 top-0">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/20">
                            <i class="fas fa-user-graduate text-3xl text-white/90"></i>
                            <div class="mt-1 text-xs text-white/70">Smart Learning</div>
                        </div>
                    </div>
                    
                    <!-- Floating Analytics Illustration -->
                    <div class="float-animation-delayed absolute right-0 top-0">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/20">
                            <i class="fas fa-chart-line text-3xl text-white/90"></i>
                            <div class="mt-1 text-xs text-white/70">Analytics</div>
                        </div>
                    </div>
                    
                    <!-- Floating AI Illustration -->
                    <div class="float-animation-slow absolute left-20 bottom-0">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/20">
                            <i class="fas fa-microchip text-3xl text-white/90"></i>
                            <div class="mt-1 text-xs text-white/70">AI Powered</div>
                        </div>
                    </div>
                    
                    <!-- Floating Security Illustration -->
                    <div class="float-animation absolute right-16 bottom-0">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-3 border border-white/20">
                            <i class="fas fa-shield-alt text-3xl text-white/90"></i>
                            <div class="mt-1 text-xs text-white/70">Secure</div>
                        </div>
                    </div>
                </div>

                <!-- Feature Cards -->
                <div class="space-y-3 mt-4">
                    <div class="feature-card p-3 bg-white/5 backdrop-blur-md rounded-xl border border-white/10 accent-border">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-face-smile-beam text-white text-lg"></i>
                            <div>
                                <p class="text-sm font-semibold text-white">AI-Powered Attendance</p>
                                <p class="text-xs text-white/60">Face-recognition & biometric integration ready</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-card p-3 bg-white/5 backdrop-blur-md rounded-xl border border-white/10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-chart-line text-white text-lg"></i>
                            <div>
                                <p class="text-sm font-semibold text-white">Advanced Analytics</p>
                                <p class="text-xs text-white/60">Real-time performance tracking & insights</p>
                            </div>
                        </div>
                    </div>
                    <div class="feature-card p-3 bg-white/5 backdrop-blur-md rounded-xl border border-white/10">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-shield-alt text-white text-lg"></i>
                            <div>
                                <p class="text-sm font-semibold text-white">Secure Platform</p>
                                <p class="text-xs text-white/60">Enterprise-grade security & data protection</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section - Version Info -->
            <div class="relative z-10 mt-6">
                <p class="text-xs text-white/40 font-medium uppercase tracking-widest">
                    System Version 3.0 // {{ now()->year }}
                </p>
            </div>
        </div>

        <!-- Right Side - Login Form (Scrollable) -->
        <div class="w-full lg:w-7/12 flex items-center justify-center p-6 md:p-8 bg-white right-side-scroll">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <div class="flex items-center justify-center gap-2 mb-4">
                        <div class="h-10 w-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-graduation-cap text-white text-lg"></i>
                        </div>
                        <span class="text-2xl font-bold tracking-tight text-slate-900">NeoEdu</span>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-900">Welcome Back</h2>
                    <p class="text-slate-500 text-sm mt-1">Sign in to continue</p>
                </div>

                <!-- Desktop Header -->
                <div class="hidden lg:block text-center mb-8">
                    <h2 class="text-3xl font-bold text-slate-900 tracking-tight">Secure Portal Access</h2>
                    <p class="text-slate-500 mt-2">Log in to manage student performance data</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email/Username Field -->
                    <div class="space-y-2">
                        <label class="text-sm font-semibold text-slate-700">System ID / Email</label>
                        <div class="relative group">
                            <i class="fas fa-id-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            <input type="text" name="identity" value="{{ old('identity') }}" placeholder="Enter your unique ID or email" 
                                class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all @error('identity') border-red-500 @enderror"
                                required autofocus>
                        </div>
                        @error('identity')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-sm font-semibold text-slate-700">Access Key</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Forgot?</a>
                        </div>
                        <div class="relative group">
                            <i class="fas fa-key absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                            <input type="password" name="password" id="password" placeholder="••••••••" 
                                class="w-full pl-12 pr-12 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all @error('password') border-red-500 @enderror"
                                required>
                            <button type="button" id="togglePassword" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-500 transition-colors">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between py-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-sm text-slate-600">Remember this device</span>
                        </label>
                    </div>

                    <!-- Submit Button with Gradient -->
                    <button type="submit" class="w-full btn-gradient text-white font-bold py-3.5 rounded-xl shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-2 mt-4" id="submitBtn">
                        Authenticate <i class="fas fa-arrow-right-long text-xs opacity-70"></i>
                    </button>
                </form>

                <!-- Footer -->
                <footer class="mt-10 text-center">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-widest">
                        <i class="fas fa-shield-alt mr-1"></i> Protected by Secure-Layer Encryption
                    </p>
                </footer>
            </div>
        </div>
    </div>

    <script>
        (function() {
            'use strict';
            
            // Password visibility toggle
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.querySelector('i').classList.toggle('fa-eye');
                    this.querySelector('i').classList.toggle('fa-eye-slash');
                });
            }
            
            // Form loading state
            const form = document.querySelector('form');
            const submitBtn = document.getElementById('submitBtn');
            
            if (form && submitBtn) {
                form.addEventListener('submit', function() {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="loading-spinner mr-2"></span> Authenticating...';
                    submitBtn.disabled = true;
                    submitBtn.dataset.originalText = originalText;
                });
            }
            
            console.log('NeoEdu Login Page Loaded');
        })();
    </script>
</body>

</html>