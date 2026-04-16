<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>{{ $app_name }} | Smart Student Management System</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #818cf8;
            --secondary: #ec4899;
            --dark: #0f172a;
            --gray: #64748b;
            --light-gray: #f1f5f9;
            --white: #ffffff;
            --gradient: linear-gradient(135deg, #6366f1 0%, #ec4899 100%);
            --gradient-hover: linear-gradient(135deg, #4f46e5 0%, #db2777 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--white);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light-gray);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* Navigation */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .top-nav.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            background: rgba(255, 255, 255, 0.98);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 0;
            gap: 20px;
        }

        .brand {
            font-size: 1.5rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        .brand:hover {
            transform: scale(1.05);
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--gray);
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary);
        }

        /* Button Styles */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
            background: var(--gradient-hover);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .btn-large {
            padding: 14px 36px;
            font-size: 1rem;
        }

        /* Hero Section */
        .hero {
            padding: 140px 0 80px;
            background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="%236366f1" fill-opacity="0.05" d="M100 0L200 200H0L100 0z"/></svg>') repeat;
            opacity: 0.1;
            pointer-events: none;
        }

        .hero-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 800;
            line-height: 1.2;
            margin: 0 0 20px;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--gray);
            margin: 0 0 32px;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            flex-wrap: wrap;
        }

        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 48px;
            padding-top: 32px;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            display: block;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .hero-card {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hero-card h3 {
            font-size: 1.3rem;
            margin: 0 0 16px;
            color: var(--dark);
        }

        .hero-card ul {
            list-style: none;
            padding: 0;
        }

        .hero-card li {
            padding: 10px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--gray);
        }

        .hero-card li i {
            color: var(--primary);
            font-size: 1.1rem;
        }

        /* Section Styles */
        section {
            padding: 80px 0;
        }

        .section-alt {
            background: var(--light-gray);
        }

        .section-title {
            font-size: clamp(1.8rem, 3vw, 2.5rem);
            font-weight: 700;
            text-align: center;
            margin: 0 0 16px;
        }

        .section-subtitle {
            text-align: center;
            color: var(--gray);
            max-width: 600px;
            margin: 0 auto 48px;
            font-size: 1.1rem;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 32px;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: var(--gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-icon i {
            font-size: 2rem;
            color: white;
        }

        .feature-card h4 {
            font-size: 1.25rem;
            margin: 0 0 12px;
        }

        .feature-card p {
            color: var(--gray);
            line-height: 1.6;
            margin: 0;
        }

        /* About Section */
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }

        .about-content p {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 24px;
        }

        .tech-stack {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .tech-badge {
            background: white;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 500;
            color: var(--primary);
            border: 1px solid var(--primary);
            transition: all 0.3s ease;
        }

        .tech-badge:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        /* CTA Section */
        .cta {
            background: var(--gradient);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="80" fill="white" fill-opacity="0.05"/></svg>') repeat;
            pointer-events: none;
        }

        .cta .section-title,
        .cta .section-subtitle {
            color: white;
        }

        .cta .btn-outline {
            border-color: white;
            color: white;
        }

        .cta .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: var(--gray);
            padding: 48px 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-col h4 {
            color: white;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-col p {
            margin-bottom: 16px;
            line-height: 1.6;
        }

        .footer-col a {
            color: var(--gray);
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: color 0.3s ease;
        }

        .footer-col a:hover {
            color: var(--primary);
        }

        .social-links {
            display: flex;
            gap: 16px;
            margin-top: 20px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-btn {
                display: block;
            }

            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 100%;
                height: calc(100vh - 70px);
                background: white;
                flex-direction: column;
                padding: 40px 20px;
                transition: left 0.3s ease;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            .nav-links.active {
                left: 0;
            }

            .hero-wrap {
                grid-template-columns: 1fr;
                gap: 40px;
            }

            .hero {
                padding: 120px 0 60px;
            }

            .hero-stats {
                justify-content: center;
            }

            section {
                padding: 60px 0;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .social-links {
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .hero-actions {
                flex-direction: column;
            }

            .hero-actions .btn {
                width: 100%;
            }

            .hero-stats {
                flex-direction: column;
                gap: 16px;
            }

            .container {
                padding: 0 16px;
            }
        }

        /* Animations */
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

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
</head>
<body>

    <header class="top-nav" id="navbar">
        <div class="container">
            <div class="inner">
                <a class="brand" href="{{ route('landing') }}">{{ $app_name }}</a>
                <div class="menu-btn" onclick="toggleMenu()">
                    <i class="fas fa-bars"></i>
                </div>
                <nav class="nav-links" id="navLinks">
                    <a href="#features">Features</a>
                    <a href="#about">About</a>
                    <a href="#contact">Contact</a>
                    <a class="btn btn-primary" href="{{ route('login') }}">Login</a>
                </nav>
            </div>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <div class="hero-wrap">
                    <div data-aos="fade-up" data-aos-duration="800">
                        <h1>Smart Student Management System</h1>
                        <p>
                            Welcome to <strong>{{ $app_name }}</strong> — a comprehensive platform designed to streamline 
                            student information, academic records, fee management, and institutional operations.
                        </p>
                        <div class="hero-actions">
                            <a class="btn btn-primary btn-large" href="{{ route('login') }}">
                                <i class="fas fa-rocket"></i> Login to Dashboard
                            </a>
                            <a class="btn btn-outline btn-large" href="#features">
                                <i class="fas fa-play"></i> Explore Features
                            </a>
                        </div>
                        <div class="hero-stats">
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="5000">0</span>
                                <span class="stat-label">Students Managed</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="200">0</span>
                                <span class="stat-label">Teachers</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number counter" data-target="98">0</span>
                                <span class="stat-label">Satisfaction Rate</span>
                            </div>
                        </div>
                    </div>
                    <div data-aos="fade-up" data-aos-delay="200" data-aos-duration="800">
                        <div class="hero-card animate-float">
                            <h3><i class="fas fa-star" style="color: #f59e0b;"></i> What Visitors Can Do</h3>
                            <ul>
                                <li><i class="fas fa-check-circle"></i> Explore project features and modules</li>
                                <li><i class="fas fa-check-circle"></i> Understand the system architecture</li>
                                <li><i class="fas fa-check-circle"></i> Access the secure dashboard via login</li>
                                <li><i class="fas fa-check-circle"></i> Contact support for inquiries</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Powerful Features</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Everything you need to manage your educational institution efficiently
                </p>
                <div class="features-grid">
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4>User Management</h4>
                        <p>Manage students, teachers, parents, and administrators with role-based access control.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Academic Tracking</h4>
                        <p>Track student performance, manage marks, and generate comprehensive reports.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4>Timetable Management</h4>
                        <p>Create and manage class schedules and exam timetables effortlessly.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <h4>Fee Management</h4>
                        <p>Handle fee collection, generate invoices, and track payment history.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                        <div class="feature-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h4>Exam Management</h4>
                        <p>Create exams, manage marksheets, and generate student report cards.</p>
                    </div>
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="700">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4>Notifications</h4>
                        <p>Send alerts and announcements to students and parents instantly.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="section-alt">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">About This Project</h2>
                <div class="about-content">
                    <p data-aos="fade-up" data-aos-delay="100">
                        <strong>{{ $app_name }}</strong> is a comprehensive Student Management System developed as a final year project. 
                        It aims to digitize and streamline all academic and administrative operations of educational institutions.
                    </p>
                    <p data-aos="fade-up" data-aos-delay="200">
                        Built with modern web technologies, this system provides a secure, scalable, and user-friendly platform 
                        for managing students, teachers, exams, fees, and academic records efficiently.
                    </p>
                    <div class="tech-stack" data-aos="fade-up" data-aos-delay="300">
                        <span class="tech-badge">Laravel</span>
                        <span class="tech-badge">MySQL</span>
                        <span class="tech-badge">Bootstrap</span>
                        <span class="tech-badge">jQuery</span>
                        <span class="tech-badge">REST API</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contact" class="cta">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Ready to Get Started?</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">
                    Authorized users can sign in to access the full dashboard and all project tools
                </p>
                <a class="btn btn-outline btn-large" href="{{ route('login') }}" data-aos="zoom-in" data-aos-delay="200">
                    <i class="fas fa-arrow-right"></i> Login to Dashboard
                </a>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h4>{{ $app_name }}</h4>
                    <p>A comprehensive Student Management System for modern educational institutions.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-github"></i></a>
                    </div>
                </div>
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <a href="#features">Features</a>
                    <a href="#about">About</a>
                    <a href="{{ route('login') }}">Login</a>
                </div>
                <div class="footer-col">
                    <h4>Contact</h4>
                    <a href="mailto:support@{{ strtolower(str_replace(' ', '', $app_name)) }}.com">
                        <i class="fas fa-envelope"></i> support@{{ strtolower(str_replace(' ', '', $app_name)) }}.com
                    </a>
                    @if(!empty($contact_phone))
                    <a href="tel:{{ preg_replace('/\s+/', '', $contact_phone) }}">
                        <i class="fas fa-phone"></i> {{ $contact_phone }}
                    </a>
                    @endif
                </div>
                <div class="footer-col">
                    <h4>Legal</h4>
                    <a href="{{ route('privacy_policy') }}">Privacy Policy</a>
                    <a href="{{ route('terms_of_use') }}">Terms of Use</a>
                </div>
            </div>
            <div class="footer-bottom">
                <p>© {{ now()->year }} {{ $app_name }}. All rights reserved. | Final Year Project</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Mobile Menu Toggle
        function toggleMenu() {
            const navLinks = document.getElementById('navLinks');
            navLinks.classList.toggle('active');
        }

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Counter Animation
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const animateCounters = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / speed;
                
                if (count < target) {
                    counter.innerText = Math.ceil(count + increment);
                    setTimeout(animateCounters, 1);
                } else {
                    counter.innerText = target;
                }
            });
        };

        // Intersection Observer for counters
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateCounters();
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('.hero-stats');
        if (statsSection) {
            observer.observe(statsSection);
        }

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    if (window.innerWidth <= 768) {
                        document.getElementById('navLinks').classList.remove('active');
                    }
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            const navLinks = document.getElementById('navLinks');
            const menuBtn = document.querySelector('.menu-btn');
            if (window.innerWidth <= 768 && navLinks.classList.contains('active')) {
                if (!navLinks.contains(e.target) && !menuBtn.contains(e.target)) {
                    navLinks.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>