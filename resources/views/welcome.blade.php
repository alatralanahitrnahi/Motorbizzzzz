<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Pro - Comprehensive Inventory Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 100px 0;
            min-height: 70vh;
            display: flex;
            align-items: center;
        }
        .feature-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }
        .feature-card:hover {
            transform: translateY(-10px);
        }
        .feature-icon {
            font-size: 3rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-get-started {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: bold;
            border-radius: 50px;
        }
        .stats-section {
            background: #f8f9fa;
            padding: 80px 0;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: bold;
            color: #667eea;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-boxes text-primary"></i> Inventory Pro
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="btn btn-outline-primary" href="{{ route('login') }}">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Comprehensive Inventory Management System
                    </h1>
                    <p class="lead mb-5">
                        Streamline your inventory operations with our powerful, role-based management system. 
                        Perfect for businesses of all sizes looking to optimize their stock management.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg btn-get-started">
                            <i class="fas fa-rocket"></i> Get Started
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle"></i> Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-warehouse" style="font-size: 15rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="row text-center mb-5">
                <div class="col-12">
                    <h2 class="display-5 fw-bold mb-3">Powerful Features</h2>
                    <p class="lead">Everything you need to manage your inventory efficiently</p>
                </div>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-users-cog"></i>
                        </div>
                        <h4>Role-Based Access</h4>
                        <p class="text-muted">
                            Admin, Purchase Team, and Inventory Manager roles with 
                            specific permissions and access levels.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4>Real-time Analytics</h4>
                        <p class="text-muted">
                            Get instant insights into your inventory levels, 
                            stock movements, and business performance.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h4>Mobile Responsive</h4>
                        <p class="text-muted">
                            Access your inventory system from anywhere, 
                            on any device with our responsive design.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Secure & Reliable</h4>
                        <p class="text-muted">
                            Enterprise-grade security with encrypted data 
                            and regular automated backups.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h4>Smart Alerts</h4>
                        <p class="text-muted">
                            Get notified about low stock levels, expiring items, 
                            and important inventory events.
                        </p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card feature-card p-4 text-center">
                        <div class="feature-icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h4>Easy Integration</h4>
                        <p class="text-muted">
                            Seamlessly integrate with your existing systems 
                            and third-party applications.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3">
                    <div class="stat-number">99.9%</div>
                    <p class="text-muted">Uptime</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">500+</div>
                    <p class="text-muted">Happy Clients</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">1M+</div>
                    <p class="text-muted">Items Managed</p>
                </div>
                <div class="col-md-3">
                    <div class="stat-number">24/7</div>
                    <p class="text-muted">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">About Inventory Pro</h2>
                    <p class="lead mb-4">
                        Built for modern businesses that need reliable, scalable inventory management solutions.
                    </p>
                    <p class="mb-4">
                        Our system provides comprehensive tools for tracking stock levels, managing suppliers, 
                        processing purchase orders, and generating detailed reports. With role-based access control, 
                        your team can work efficiently while maintaining security and data integrity.
                    </p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Multi-location inventory tracking</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Automated reorder points</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Comprehensive reporting</li>
                        <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Supplier management</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="text-center">
                        <i class="fas fa-clipboard-list" style="font-size: 12rem; color: #667eea; opacity: 0.2;"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="hero-section">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-4">Ready to Get Started?</h2>
            <p class="lead mb-5">Join thousands of businesses already using Inventory Pro</p>
            <a href="{{ route('login') }}" class="btn btn-light btn-lg btn-get-started">
                <i class="fas fa-arrow-right"></i> Start Managing Your Inventory
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-boxes"></i> Inventory Pro</h5>
                    <p class="text-muted">Comprehensive Inventory Management System</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted">© 2024 Inventory Pro. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>