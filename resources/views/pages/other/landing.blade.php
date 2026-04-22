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
            --gradient-bg: linear-gradient(125deg, #f0f4ff 0%, #f9f0ff 100%);
            --glass-bg: rgba(255,255,255,0.75);
            --shadow-sm: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02);
            --shadow-md: 0 20px 25px -12px rgba(0,0,0,0.1);
            --shadow-lg: 0 25px 40px -12px rgba(0,0,0,0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--dark);
            background: var(--gradient-bg);
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(99, 102, 241, 0.15);
            transition: all 0.3s ease;
        }
        .top-nav.scrolled {
            background: rgba(255, 255, 255, 0.95);
            box-shadow: var(--shadow-sm);
            border-bottom-color: transparent;
        }
        .container {
            max-width: 1280px;
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
            font-size: 1.6rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-decoration: none;
            letter-spacing: -0.3px;
            transition: all 0.2s;
        }
        .brand:hover {
            opacity: 0.9;
            transform: scale(1.02);
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 32px;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.2s;
            position: relative;
        }
        .nav-links a:not(.btn-primary)::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gradient);
            border-radius: 2px;
            transition: width 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.1);
        }
        .nav-links a:not(.btn-primary):hover::after {
            width: 100%;
        }
        .nav-links a:not(.btn-primary):hover {
            color: var(--primary);
        }
        .menu-btn {
            display: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--primary);
            background: rgba(99,102,241,0.1);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .menu-btn:hover {
            background: rgba(99,102,241,0.2);
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 28px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            letter-spacing: -0.2px;
        }
        .btn-primary {
            background: var(--gradient);
            color: white;
            box-shadow: 0 6px 14px rgba(99, 102, 241, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 24px rgba(99, 102, 241, 0.4);
            background: var(--gradient-hover);
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--primary);
            color: var(--primary);
            backdrop-filter: blur(4px);
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(99,102,241,0.2);
        }
        .btn-large {
            padding: 14px 36px;
            font-size: 1rem;
        }

        /* Hero Section */
        .hero {
            padding: 150px 0 100px;
            background: radial-gradient(circle at 10% 30%, rgba(99,102,241,0.08) 0%, rgba(236,72,153,0.05) 100%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -20%;
            right: -10%;
            width: 70%;
            height: 130%;
            background: radial-gradient(ellipse, rgba(99,102,241,0.1) 0%, rgba(236,72,153,0) 70%);
            pointer-events: none;
        }
        .hero-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }
        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.8rem);
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
            gap: 20px;
            flex-wrap: wrap;
        }
        .hero-stats {
            display: flex;
            gap: 42px;
            margin-top: 50px;
            padding-top: 20px;
        }
        .stat-item {
            text-align: center;
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(8px);
            padding: 10px 20px;
            border-radius: 40px;
            min-width: 110px;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: block;
        }
        .stat-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray);
        }
        .hero-card {
            background: var(--glass-bg);
            backdrop-filter: blur(12px);
            border-radius: 32px;
            padding: 32px;
            box-shadow: var(--shadow-lg);
            border: 1px solid rgba(255,255,255,0.6);
            transition: all 0.3s;
        }
        .hero-card:hover {
            transform: translateY(-6px);
        }
        .hero-card h3 {
            font-size: 1.4rem;
            margin: 0 0 16px;
            font-weight: 700;
        }
        .hero-card ul {
            list-style: none;
        }
        .hero-card li {
            padding: 12px 0;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .hero-card li i {
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-size: 1.2rem;
        }

        /* Section */
        section {
            padding: 90px 0;
        }
        .section-alt {
            background: rgba(255,255,255,0.5);
            backdrop-filter: blur(2px);
        }
        .section-title {
            font-size: clamp(2rem, 4vw, 2.8rem);
            font-weight: 800;
            text-align: center;
            margin: 0 0 16px;
            letter-spacing: -0.02em;
            background: var(--gradient);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .section-subtitle {
            text-align: center;
            color: var(--gray);
            max-width: 640px;
            margin: 0 auto 56px;
            font-size: 1.1rem;
        }

        /* Features Grid - redesigned accordion style cards but keeping grid for clarity, but enhanced */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 32px;
        }
        .feature-card {
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(8px);
            padding: 32px 28px;
            border-radius: 32px;
            transition: all 0.4s cubic-bezier(0.2, 0.9, 0.4, 1.1);
            border: 1px solid rgba(255,255,255,0.7);
            box-shadow: var(--shadow-sm);
        }
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-md);
            border-color: rgba(99,102,241,0.3);
            background: white;
        }
        .feature-icon {
            width: 64px;
            height: 64px;
            background: var(--gradient);
            border-radius: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            transition: all 0.3s;
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 10px 20px -5px rgba(99,102,241,0.4);
        }
        .feature-icon i {
            font-size: 1.8rem;
            color: white;
        }
        .feature-card h4 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .feature-card p, .feature-card ul {
            color: var(--gray);
            line-height: 1.5;
        }
        .feature-card ul {
            list-style: none;
            margin-top: 12px;
            padding-left: 0;
        }
        .feature-card li {
            margin: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }
        .feature-card li i {
            color: var(--primary);
            font-size: 0.8rem;
        }

        /* AI Special badge */
        .ai-badge {
            background: linear-gradient(120deg, #f0f4ff, #ffe6f0);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 0.7rem;
            font-weight: 700;
            display: inline-block;
            margin-top: 12px;
            color: var(--primary-dark);
            border: 1px solid rgba(99,102,241,0.3);
        }

        /* About Section */
        .about-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        .about-content p {
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 24px;
            font-weight: 500;
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
            border-radius: 60px;
            font-weight: 600;
            background: linear-gradient(135deg, white, #f8fafc);
            color: var(--primary);
            border: 1px solid rgba(99,102,241,0.2);
            transition: all 0.2s;
            box-shadow: var(--shadow-sm);
        }
        .tech-badge:hover {
            transform: translateY(-3px);
            border-color: var(--primary);
            color: var(--primary-dark);
        }

        /* CTA Section */
        .cta {
            background: var(--gradient);
            text-align: center;
            position: relative;
            border-radius: 48px 48px 0 0;
            margin-top: 20px;
        }
        .cta::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 20% 30%, rgba(255,255,255,0.2) 0%, rgba(0,0,0,0) 70%);
            pointer-events: none;
        }
        .cta .section-title, .cta .section-subtitle {
            color: white;
        }
        .cta .btn-outline {
            border-color: white;
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .cta .btn-outline:hover {
            background: white;
            color: var(--primary);
        }

        /* Footer */
        footer {
            background: #0a0f1e;
            color: #a0aec0;
            padding: 56px 0 24px;
            border-top: 1px solid rgba(99,102,241,0.2);
        }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 40px;
            margin-bottom: 48px;
        }
        .footer-col h4 {
            color: white;
            margin-bottom: 20px;
            font-weight: 700;
        }
        .footer-col p, .footer-col a {
            color: #94a3b8;
            text-decoration: none;
            display: block;
            margin-bottom: 12px;
            transition: 0.2s;
        }
        .footer-col a:hover {
            color: var(--primary-light);
            transform: translateX(4px);
        }
        .social-links {
            display: flex;
            gap: 16px;
            margin-top: 20px;
        }
        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.05);
            border-radius: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.2s;
        }
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        .footer-bottom {
            text-align: center;
            padding-top: 28px;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .menu-btn {
                display: flex;
            }
            .nav-links {
                position: fixed;
                top: 70px;
                left: -100%;
                width: 80%;
                height: calc(100vh - 70px);
                background: rgba(255,255,255,0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 40px 24px;
                transition: left 0.3s ease;
                box-shadow: var(--shadow-lg);
                border-radius: 0 24px 24px 0;
                gap: 28px;
                align-items: flex-start;
            }
            .nav-links.active {
                left: 0;
            }
            .hero-wrap {
                grid-template-columns: 1fr;
                gap: 48px;
            }
            .hero {
                padding: 120px 0 60px;
            }
            .hero-stats {
                flex-wrap: wrap;
                justify-content: center;
                gap: 16px;
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
                flex-direction: row;
                flex-wrap: wrap;
            }
        }

        /* animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        .feature-card {
            transition: all 0.3s;
        }
    </style>
</head>
<body>

    <header class="top-nav" id="navbar">
        <div class="container">
    <div class="inner">
        <a class="brand" href="{{ route('landing') }}">SPEMS</a>
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
                            <h3><i class="fas fa-magic" style="background: var(--gradient); -webkit-background-clip:text; background-clip:text; color:transparent;"></i> Next-Gen AI Integration</h3>
                            <ul>
                                <li><i class="fas fa-brain"></i> AI-powered facial recognition attendance</li>
                                <li><i class="fas fa-chart-simple"></i> Real-time performance analytics</li>
                                <li><i class="fas fa-robot"></i> Smart reporting & predictions</li>
                                <li><i class="fas fa-shield-alt"></i> Role-based secure dashboards</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section: Updated according to requirements -->
        <section id="features">
            <div class="container">
                <h2 class="section-title" data-aos="fade-up">Powerful Features</h2>
                <p class="section-subtitle" data-aos="fade-up" data-aos-delay="100">AI-driven ecosystem for modern education</p>
                <div class="features-grid">
                    <!-- Admin Panel Card -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="200">
                        <div class="feature-icon"><i class="fas fa-user-shield"></i></div>
                        <h4>Admin Panel</h4>
                        <ul>
                            <li><i class="fas fa-check-circle"></i> Manage users & subjects data</li>
                            <li><i class="fas fa-microchip"></i> AI training for face recognition</li>
                            <li><i class="fas fa-database"></i> System configuration & reporting</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-brain"></i> AI Admin Core</div>
                    </div>
                    <!-- Student Panel Card -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="300">
                        <div class="feature-icon"><i class="fas fa-user-graduate"></i></div>
                        <h4>Student Panel</h4>
                        <ul>
                            <li><i class="fas fa-calendar-check"></i> View attendance records</li>
                            <li><i class="fas fa-tasks"></i> View & submit tasks</li>
                            <li><i class="fas fa-chart-line"></i> Check grades & performance reports</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-chart-pie"></i> Real-time Insights</div>
                    </div>
                    <!-- Teacher Panel Card -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="400">
                        <div class="feature-icon"><i class="fas fa-chalkboard-user"></i></div>
                        <h4>Teacher Panel</h4>
                        <ul>
                            <li><i class="fas fa-camera"></i> Mark attendance (AI facial recognition)</li>
                            <li><i class="fas fa-cloud-upload-alt"></i> Upload tasks & assignments</li>
                            <li><i class="fas fa-star"></i> Assign grades & generate performance reports</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-face-smile"></i> Smart Grading</div>
                    </div>
                    <!-- Parent Panel Card -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="500">
                        <div class="feature-icon"><i class="fas fa-heart"></i></div>
                        <h4>Parent Panel</h4>
                        <ul>
                            <li><i class="fas fa-chart-simple"></i> Monitor real-time performance</li>
                            <li><i class="fas fa-bell"></i> Attendance & grade alerts</li>
                            <li><i class="fas fa-file-signature"></i> Track child’s academic journey</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-eye"></i> Live Tracking</div>
                    </div>
                    <!-- AI Attendance Special Card (extra highlight) -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="600">
                        <div class="feature-icon"><i class="fas fa-fingerprint"></i></div>
                        <h4>AI Attendance <span style="font-size:0.8rem;">⚡</span></h4>
                        <ul>
                            <li><i class="fas fa-video"></i> Automatic facial recognition marking</li>
                            <li><i class="fas fa-clock"></i> Real-time presence tracking</li>
                            <li><i class="fas fa-chart-line"></i> Attendance analytics dashboard</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-microchip"></i> Powered by TensorFlow</div>
                    </div>
                    <!-- Additional Analytics Module -->
                    <div class="feature-card" data-aos="fade-up" data-aos-delay="700">
                        <div class="feature-icon"><i class="fas fa-chart-column"></i></div>
                        <h4>Performance Analytics</h4>
                        <ul>
                            <li><i class="fas fa-chart-gantt"></i> Student progress prediction</li>
                            <li><i class="fas fa-file-alt"></i> Automated report generation</li>
                            <li><i class="fas fa-envelope-open-text"></i> Smart notifications & insights</li>
                        </ul>
                        <div class="ai-badge"><i class="fas fa-chart-line"></i> AI Predictive Models</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section (unchanged concepts) -->
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
                        <span class="tech-badge">Face Recognition AI</span>
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

    <!-- Scripts (Same logic, untouched) -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true, offset: 100 });
        function toggleMenu() { document.getElementById('navLinks').classList.toggle('active'); }
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) navbar.classList.add('scrolled');
            else navbar.classList.remove('scrolled');
        });
        const counters = document.querySelectorAll('.counter');
        const speed = 200;
        const animateCounters = () => {
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const increment = target / speed;
                if (count < target) { counter.innerText = Math.ceil(count + increment); setTimeout(animateCounters, 1); } 
                else { counter.innerText = target; }
            });
        };
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) { animateCounters(); observer.unobserve(entry.target); } });
        }, { threshold: 0.5 });
        const statsSection = document.querySelector('.hero-stats');
        if(statsSection) observer.observe(statsSection);
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); if(window.innerWidth <= 768) document.getElementById('navLinks').classList.remove('active'); }
            });
        });
        document.addEventListener('click', function(e) {
            const navLinks = document.getElementById('navLinks');
            const menuBtn = document.querySelector('.menu-btn');
            if(window.innerWidth <= 768 && navLinks.classList.contains('active') && !navLinks.contains(e.target) && !menuBtn.contains(e.target)) navLinks.classList.remove('active');
        });
    </script>
</body>
</html>