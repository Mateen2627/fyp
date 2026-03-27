<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Student Management and Evaluation System - Comprehensive educational management platform">
    <meta name="author" content="Your Institution Name">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Theme Color -->
    <meta name="theme-color" content="#4e73df">

    <title>{{ config('app.name', 'Student Management and Evaluation System') }}</title>

    @include('partials.login.inc_top')
    
    <!-- Additional Styles for Enhanced Visual Experience -->
    <style>
        /* Smooth scrolling and better font rendering */
        html {
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* Custom scrollbar for webkit browsers */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
        
        /* Selection styling */
        ::selection {
            background: rgba(78, 115, 223, 0.2);
            color: #2c3e50;
        }
        
        /* Focus outline for accessibility */
        :focus {
            outline: 2px solid rgba(78, 115, 223, 0.5);
            outline-offset: 2px;
        }
        
        /* Loading animation for page transitions */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #4e73df, #1cc88a, #36b9cc, #4e73df);
            background-size: 300% 100%;
            animation: gradientShift 2s ease infinite;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        }
        
        .page-transition.loading {
            opacity: 1;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Toast notification container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-width: 350px;
        }
        
        /* Back to top button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            color: white;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 5px 20px rgba(78, 115, 223, 0.4);
            z-index: 99;
            border: none;
            font-size: 20px;
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(78, 115, 223, 0.5);
        }
        
        /* Maintenance mode banner */
        .maintenance-banner {
            background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);
            color: #2c3e50;
            text-align: center;
            padding: 12px 20px;
            font-weight: 500;
            position: relative;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .maintenance-banner i {
            margin-right: 8px;
            animation: spin 4s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Loading spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(78, 115, 223, 0.2);
            border-radius: 50%;
            border-top-color: #4e73df;
            animation: spin 0.8s linear infinite;
        }
        
        /* Better focus styles for interactive elements */
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid rgba(78, 115, 223, 0.5);
            outline-offset: 2px;
            border-radius: 4px;
        }
        
        /* Improved typography */
        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf5 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Main content area */
        main {
            flex: 1;
            padding: 20px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }
        
        /* Card hover effects from dashboard */
        .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.2);
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            main {
                padding: 15px;
            }
            
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
            
            .toast-container {
                max-width: calc(100% - 40px);
                right: 20px;
                top: 20px;
            }
        }
        
        @media (max-width: 576px) {
            main {
                padding: 10px;
            }
            
            .back-to-top {
                bottom: 15px;
                right: 15px;
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
        }
        
        /* Print styles */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
                padding: 20px;
            }
            
            a {
                text-decoration: none !important;
                color: black !important;
            }
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- Page Transition Indicator -->
    <div class="page-transition" id="pageTransition"></div>
    
    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Maintenance Mode Banner (if enabled) -->
    @if(config('app.maintenance_mode', false))
    <div class="maintenance-banner no-print">
        <i class="fas fa-tools"></i>
        System is under maintenance. Some features may be unavailable.
    </div>
    @endif
    
    <!-- Header Section -->
    @include('partials.login.header')
    
    <!-- Main Content Area -->
    <main class="flex-grow-1">
        @yield('content')
    </main>
    
    <!-- Footer Section -->
    @include('partials.login.footer')
    
    <!-- Back to Top Button -->
    <button class="back-to-top no-print" id="backToTop" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
    
    <!-- Additional JavaScript for Enhanced Functionality -->
    <script>
        (function() {
            'use strict';
            
            // Page transition indicator
            const pageTransition = document.getElementById('pageTransition');
            
            // Show transition on page load
            document.addEventListener('DOMContentLoaded', function() {
                if (pageTransition) {
                    pageTransition.classList.add('loading');
                    
                    // Hide after load
                    setTimeout(function() {
                        pageTransition.classList.remove('loading');
                    }, 500);
                }
            });
            
            // Show transition on link clicks (except those with data-no-transition)
            document.addEventListener('click', function(e) {
                const link = e.target.closest('a');
                if (link && 
                    link.href && 
                    !link.hasAttribute('data-no-transition') && 
                    link.href.indexOf(window.location.origin) === 0 &&
                    !link.target) {
                    
                    e.preventDefault();
                    
                    if (pageTransition) {
                        pageTransition.classList.add('loading');
                    }
                    
                    setTimeout(function() {
                        window.location.href = link.href;
                    }, 300);
                }
            });
            
            // Back to top functionality
            const backToTop = document.getElementById('backToTop');
            
            if (backToTop) {
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        backToTop.classList.add('visible');
                    } else {
                        backToTop.classList.remove('visible');
                    }
                });
                
                backToTop.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
            
            // Toast notification system
            window.showToast = function(message, type = 'info', duration = 5000) {
                const container = document.getElementById('toastContainer');
                if (!container) return;
                
                const toast = document.createElement('div');
                toast.className = `toast show bg-${type} text-white`;
                toast.setAttribute('role', 'alert');
                toast.setAttribute('aria-live', 'assertive');
                toast.setAttribute('aria-atomic', 'true');
                
                const icons = {
                    success: 'check-circle',
                    error: 'exclamation-circle',
                    warning: 'exclamation-triangle',
                    info: 'info-circle'
                };
                
                toast.innerHTML = `
                    <div class="toast-header bg-${type} text-white border-0">
                        <i class="fas fa-${icons[type] || 'info-circle'} me-2"></i>
                        <strong class="me-auto">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                        <small>just now</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body bg-white text-dark">
                        ${message}
                    </div>
                `;
                
                container.appendChild(toast);
                
                // Initialize Bootstrap toast if available
                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    const bsToast = new bootstrap.Toast(toast, {
                        autohide: true,
                        delay: duration
                    });
                    bsToast.show();
                }
                
                // Auto remove after duration
                setTimeout(function() {
                    toast.classList.add('fade-out');
                    setTimeout(function() {
                        if (toast.parentNode) {
                            toast.parentNode.removeChild(toast);
                        }
                    }, 300);
                }, duration);
            };
            
            // Handle AJAX errors globally
            window.addEventListener('unhandledrejection', function(event) {
                console.error('Unhandled rejection:', event.reason);
                if (window.showToast) {
                    window.showToast('An unexpected error occurred. Please try again.', 'error');
                }
            });
            
            // Handle offline/online status
            window.addEventListener('offline', function() {
                window.showToast('You are offline. Some features may be unavailable.', 'warning');
            });
            
            window.addEventListener('online', function() {
                window.showToast('You are back online!', 'success');
            });
            
            // Add loading state to forms
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitButton = this.querySelector('[type="submit"]');
                    if (submitButton && !submitButton.classList.contains('no-loading')) {
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = '<span class="loading-spinner me-2"></span> Loading...';
                        submitButton.disabled = true;
                        
                        // Store original text to restore if needed
                        submitButton.dataset.originalText = originalText;
                    }
                });
            });
            
            // Add fade-in animation to main content
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.style.opacity = '0';
                mainContent.style.transform = 'translateY(20px)';
                mainContent.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                
                setTimeout(function() {
                    mainContent.style.opacity = '1';
                    mainContent.style.transform = 'translateY(0)';
                }, 100);
            }
            
            // Handle escape key for modals
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    // Close any open modals
                    const openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(modal => {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) {
                                bsModal.hide();
                            }
                        }
                    });
                    
                    // Close any open dropdowns
                    const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
                    openDropdowns.forEach(dropdown => {
                        dropdown.classList.remove('show');
                    });
                }
            });
            
            // Add active state to current navigation item
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
            
        })();
    </script>
    
    <!-- Add Font Awesome if not already included -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
</body>

</html>