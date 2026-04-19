<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Student Management and Evaluation System - Comprehensive educational management platform">
    <meta name="author" content="Your Institution Name">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    
    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <!-- Theme Color for Mobile Browsers -->
    <meta name="theme-color" content="#4e73df">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

    <title>{{ config('app.name', 'Student Management and Evaluation System') }}</title>

    @include('partials.login.inc_top')
    
    <style>
        /* ========================================
           CSS RESET & VARIABLES
        ======================================== */
        :root {
            --primary: #4e73df;
            --primary-dark: #224abe;
            --primary-light: #6b8eff;
            --secondary: #1cc88a;
            --secondary-dark: #17a673;
            --danger: #e74a3b;
            --warning: #f6c23e;
            --info: #36b9cc;
            --dark: #1a1c23;
            --gray: #858796;
            --light: #f8f9fc;
            --gradient-primary: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
            --gradient-secondary: linear-gradient(135deg, #1cc88a 0%, #13855e 100%);
            --gradient-bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            
            /* Mobile breakpoints */
            --mobile: 576px;
            --tablet: 768px;
            --desktop: 992px;
            --large: 1200px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #2c3e50;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9ecf5 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            width: 100%;
            position: relative;
        }

        /* ========================================
           RESPONSIVE TYPOGRAPHY
        ======================================== */
        html {
            font-size: 16px;
            scroll-behavior: smooth;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        @media (max-width: 768px) {
            html {
                font-size: 14px;
            }
        }

        @media (max-width: 576px) {
            html {
                font-size: 13px;
            }
        }

        /* ========================================
           CUSTOM SCROLLBAR
        ======================================== */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        @media (min-width: 768px) {
            ::-webkit-scrollbar {
                width: 10px;
                height: 10px;
            }
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #224abe, #4e73df);
        }

        /* ========================================
           SELECTION STYLING
        ======================================== */
        ::selection {
            background: rgba(78, 115, 223, 0.2);
            color: #2c3e50;
        }

        /* ========================================
           FOCUS STATES FOR ACCESSIBILITY
        ======================================== */
        :focus-visible {
            outline: 2px solid rgba(78, 115, 223, 0.5);
            outline-offset: 2px;
            border-radius: 4px;
        }

        /* ========================================
           PAGE TRANSITION LOADER
        ======================================== */
        .page-transition {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, #4e73df, #1cc88a, #36b9cc, #4e73df);
            background-size: 300% 100%;
            animation: gradientShift 2s ease infinite;
            z-index: 10000;
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

        /* ========================================
           TOAST NOTIFICATIONS - MOBILE FRIENDLY
        ======================================== */
        .toast-container {
            position: fixed;
            top: 70px;
            right: 16px;
            left: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
            margin: 0 auto;
            pointer-events: none;
        }
        
        @media (min-width: 768px) {
            .toast-container {
                left: auto;
                right: 20px;
                max-width: 380px;
            }
        }
        
        .toast {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
            border-left: 4px solid;
            animation: slideInRight 0.3s ease;
            pointer-events: auto;
            overflow: hidden;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .toast.fade-out {
            animation: fadeOut 0.3s ease forwards;
        }
        
        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .toast.bg-success { border-left-color: #1cc88a; }
        .toast.bg-error { border-left-color: #e74a3b; }
        .toast.bg-warning { border-left-color: #f6c23e; }
        .toast.bg-info { border-left-color: #36b9cc; }

        /* ========================================
           BACK TO TOP BUTTON - MOBILE OPTIMIZED
        ======================================== */
        .back-to-top {
            position: fixed;
            bottom: 16px;
            right: 16px;
            width: 44px;
            height: 44px;
            background: var(--gradient-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
            box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
            z-index: 99;
            border: none;
            font-size: 18px;
            touch-action: manipulation;
        }
        
        @media (min-width: 768px) {
            .back-to-top {
                bottom: 30px;
                right: 30px;
                width: 50px;
                height: 50px;
                font-size: 20px;
            }
        }
        
        .back-to-top.visible {
            opacity: 1;
            visibility: visible;
        }
        
        .back-to-top:active {
            transform: scale(0.95);
        }
        
        @media (hover: hover) {
            .back-to-top:hover {
                transform: translateY(-5px) scale(1.05);
                box-shadow: 0 8px 25px rgba(78, 115, 223, 0.5);
            }
        }

        /* ========================================
           MAINTENANCE BANNER
        ======================================== */
        .maintenance-banner {
            background: linear-gradient(135deg, #f6c23e 0%, #f4b619 100%);
            color: #2c3e50;
            text-align: center;
            padding: 12px 16px;
            font-weight: 600;
            font-size: 0.875rem;
            position: relative;
            z-index: 1000;
            animation: slideDown 0.5s ease;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .maintenance-banner i {
            margin-right: 8px;
        }

        /* ========================================
           LOADING SPINNER
        ======================================== */
        .loading-spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(78, 115, 223, 0.2);
            border-radius: 50%;
            border-top-color: #4e73df;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ========================================
           MAIN CONTENT AREA - RESPONSIVE
        ======================================== */
        main {
            flex: 1;
            padding: 16px;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.5s ease-out;
        }
        
        @media (min-width: 768px) {
            main {
                padding: 20px;
            }
        }
        
        @media (min-width: 1200px) {
            main {
                padding: 30px;
            }
        }
        
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

        /* ========================================
           CARD STYLES - RESPONSIVE
        ======================================== */
        .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 16px;
        }
        
        @media (min-width: 768px) {
            .card {
                margin-bottom: 20px;
            }
        }
        
        @media (hover: hover) {
            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 1rem 2.5rem 0 rgba(58, 59, 69, 0.2);
            }
        }
        
        /* Touch feedback for cards on mobile */
        .card:active {
            transform: scale(0.98);
            transition: transform 0.1s ease;
        }

        /* ========================================
           BUTTON STYLES - TOUCH FRIENDLY
        ======================================== */
        .btn {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            padding: 10px 20px;
            font-size: 0.875rem;
            border-radius: 8px;
            touch-action: manipulation;
        }
        
        @media (min-width: 768px) {
            .btn {
                padding: 12px 24px;
                font-size: 0.95rem;
            }
        }
        
        .btn:active {
            transform: scale(0.97);
        }
        
        /* Ripple effect */
        .btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn:active::before {
            width: 300px;
            height: 300px;
        }

        /* ========================================
           NAVBAR - FULLY RESPONSIVE
        ======================================== */
        .navbar {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            padding: 12px 16px;
        }
        
        @media (min-width: 768px) {
            .navbar {
                padding: 16px 24px;
            }
        }
        
        .navbar-brand {
            font-weight: 800;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.25rem;
            white-space: normal;
            word-break: break-word;
        }
        
        @media (min-width: 768px) {
            .navbar-brand {
                font-size: 1.5rem;
            }
        }
        
        /* Mobile menu improvements */
        .navbar-toggler {
            padding: 8px 12px;
            border: none;
            background: transparent;
            touch-action: manipulation;
        }
        
        .navbar-collapse {
            max-height: 80vh;
            overflow-y: auto;
        }
        
        @media (max-width: 767.98px) {
            .navbar-nav {
                padding: 16px 0;
            }
            
            .nav-item {
                margin: 4px 0;
            }
            
            .nav-link {
                padding: 12px 16px !important;
                border-radius: 8px;
            }
        }

        /* ========================================
           FOOTER - RESPONSIVE
        ======================================== */
        footer {
            background: linear-gradient(135deg, #1a1c23 0%, #2d3748 100%);
            color: #cbd5e0;
            padding: 32px 16px 20px;
            margin-top: auto;
        }
        
        @media (min-width: 768px) {
            footer {
                padding: 40px 24px 24px;
            }
        }
        
        footer a {
            color: #cbd5e0;
            transition: color 0.3s ease;
            text-decoration: none;
            display: inline-block;
            padding: 4px 0;
        }
        
        footer a:active {
            color: #4e73df;
        }
        
        @media (hover: hover) {
            footer a:hover {
                color: #4e73df;
            }
        }
        
        /* Footer grid for responsive layout */
        .footer-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        @media (min-width: 768px) {
            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 32px;
            }
        }
        
        @media (min-width: 992px) {
            .footer-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        /* ========================================
           ANIMATIONS
        ======================================== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .slide-up {
            animation: slideUp 0.5s ease forwards;
        }

        /* ========================================
           UTILITY CLASSES
        ======================================== */
        .text-gradient {
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        .d-none {
            display: none !important;
        }
        
        /* Responsive spacing utilities */
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        
        @media (min-width: 768px) {
            .mt-md-0 { margin-top: 0; }
            .mt-md-2 { margin-top: 0.5rem; }
            .mt-md-3 { margin-top: 1rem; }
            .mt-md-4 { margin-top: 1.5rem; }
        }

        /* ========================================
           PRINT STYLES
        ======================================== */
        @media print {
            .no-print {
                display: none !important;
            }
            
            body {
                background: white;
                padding: 20px;
            }
            
            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
                break-inside: avoid;
            }
            
            a {
                text-decoration: none !important;
                color: black !important;
            }
        }

        /* ========================================
           SAFE AREA INSETS FOR NOTCH DEVICES
        ======================================== */
        @supports (padding: max(0px)) {
            .toast-container {
                top: max(70px, env(safe-area-inset-top));
                right: max(16px, env(safe-area-inset-right));
                left: max(16px, env(safe-area-inset-left));
            }
            
            .back-to-top {
                bottom: max(16px, env(safe-area-inset-bottom));
                right: max(16px, env(safe-area-inset-right));
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
    
    <script>
        (function() {
            'use strict';
            
            // Page transition indicator
            const pageTransition = document.getElementById('pageTransition');
            
            if (pageTransition) {
                pageTransition.classList.add('loading');
                setTimeout(function() {
                    pageTransition.classList.remove('loading');
                }, 500);
            }
            
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
                
                backToTop.addEventListener('click', function(e) {
                    e.preventDefault();
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
                toast.className = `toast bg-${type}`;
                toast.setAttribute('role', 'alert');
                
                const icons = {
                    success: 'fa-check-circle',
                    error: 'fa-exclamation-circle',
                    warning: 'fa-exclamation-triangle',
                    info: 'fa-info-circle'
                };
                
                const bgColors = {
                    success: '#1cc88a',
                    error: '#e74a3b',
                    warning: '#f6c23e',
                    info: '#36b9cc'
                };
                
                toast.innerHTML = `
                    <div class="toast-body p-3" style="background: white;">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 me-3">
                                <i class="fas ${icons[type] || icons.info}" style="color: ${bgColors[type] || bgColors.info}; font-size: 18px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <strong class="d-block mb-1" style="color: ${bgColors[type] || bgColors.info};">${type.charAt(0).toUpperCase() + type.slice(1)}</strong>
                                <p class="mb-0 text-dark" style="font-size: 0.875rem;">${message}</p>
                            </div>
                            <button type="button" class="btn-close ms-2" data-dismiss="toast" aria-label="Close" style="font-size: 12px; background: transparent; border: none; cursor: pointer;">&times;</button>
                        </div>
                    </div>
                `;
                
                container.appendChild(toast);
                
                // Auto remove
                setTimeout(function() {
                    toast.classList.add('fade-out');
                    setTimeout(function() {
                        if (toast.parentNode) toast.parentNode.removeChild(toast);
                    }, 300);
                }, duration);
                
                // Close button
                const closeBtn = toast.querySelector('.btn-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', function() {
                        toast.classList.add('fade-out');
                        setTimeout(() => toast.remove(), 300);
                    });
                }
            };
            
            // Offline/online detection
            window.addEventListener('offline', function() {
                window.showToast('You are offline. Some features may be unavailable.', 'warning');
            });
            
            window.addEventListener('online', function() {
                window.showToast('You are back online!', 'success');
            });
            
            // Form loading states
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('[type="submit"]');
                    if (submitBtn && !submitBtn.classList.contains('no-loading')) {
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="loading-spinner me-2"></span> Processing...';
                        submitBtn.disabled = true;
                        submitBtn.dataset.originalText = originalText;
                    }
                });
            });
            
            // Handle escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openModals = document.querySelectorAll('.modal.show');
                    openModals.forEach(modal => {
                        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                            const bsModal = bootstrap.Modal.getInstance(modal);
                            if (bsModal) bsModal.hide();
                        }
                    });
                }
            });
            
            // Active navigation state
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
            
            // Touch feedback improvements
            const clickableElements = document.querySelectorAll('.btn, .card, .nav-link, .dropdown-item');
            clickableElements.forEach(el => {
                el.addEventListener('touchstart', function() {
                    this.style.opacity = '0.7';
                });
                el.addEventListener('touchend', function() {
                    this.style.opacity = '1';
                });
            });
            
            console.log('Page fully loaded - responsive layout ready');
        })();
    </script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
</body>

</html>