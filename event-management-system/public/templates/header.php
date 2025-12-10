<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set default page title if not set
$pageTitle = $pageTitle ?? 'Event Management System';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
                        url('https://source.unsplash.com/random/1920x1080/?event,conference') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 120px 0;
            margin-bottom: 50px;
            text-align: center;
        }
        
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            color: var(--primary-color);
        }
        
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 40px 0;
            margin-top: 60px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo url('/'); ?>">
                <i class="bi bi-calendar-event me-2"></i>EventEase
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active fw-bold' : ''; ?>" 
                           href="<?php echo url('/'); ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'events.php' ? 'active fw-bold' : ''; ?>" 
                           href="<?php echo url('events.php'); ?>">Events</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active fw-bold' : ''; ?>" 
                           href="<?php echo url('about.php'); ?>">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active fw-bold' : ''; ?>" 
                           href="<?php echo url('contact.php'); ?>">Contact</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle me-2" type="button" 
                                    id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="<?php echo url('profile.php'); ?>">
                                    <i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><a class="dropdown-item" href="<?php echo url('dashboard.php'); ?>">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo url('logout.php'); ?>">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?php echo url('login.php'); ?>" class="btn btn-outline-primary me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                        <a href="<?php echo url('register.php'); ?>" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
