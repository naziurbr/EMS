<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user info with fallbacks
$user = [
    'username' => $_SESSION['username'] ?? 'User',
    'email' => $_SESSION['user_email'] ?? 'No email',
    'roles' => $_SESSION['user_roles'] ?? ['guest']
];

// If username is not set, try to get it from the database
try {
    if (empty($user['username']) || $user['username'] === 'User') {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=event_ems;charset=utf8mb4',
            'root',
            ''
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $pdo->prepare("SELECT username FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $dbUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($dbUser) {
            $user['username'] = $dbUser['username'];
            $_SESSION['username'] = $user['username'];
        }
    }
} catch (PDOException $e) {
    // Log error but don't break the page
    error_log("Error fetching username: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Event Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: #343a40;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,.8);
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,.1);
            color: white;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            padding: 20px;
        }
        .welcome-card {
            background: linear-gradient(45deg, #4e73df, #224abe);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="text-center p-3">
                    <h4>Event EMS</h4>
                </div>
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="dashboard.php">
                                <i class='bx bxs-dashboard'></i> Dashboard
                            </a>
                        </li>
                        <?php if (in_array('super_admin', $user['roles']) || in_array('admin', $user['roles'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class='bx bxs-user-detail'></i> Users
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if (in_array('event_manager', $user['roles']) || in_array('super_admin', $user['roles']) || in_array('admin', $user['roles'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class='bx bxs-calendar-event'></i> Events
                            </a>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <i class='bx bxs-ticket'></i> My Tickets
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="logout.php">
                                <i class='bx bx-log-out'></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <div class="btn-group me-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary">Share</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary">Export</button>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle">
                            <i class='bx bxs-calendar'></i> This week
                        </button>
                    </div>
                </div>

                <!-- Welcome Card -->
                <div class="welcome-card">
                    <h3>Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</h3>
                    <p class="mb-0">You're logged in as: <?php echo htmlspecialchars(implode(', ', $user['roles'])); ?></p>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase mb-0">Events</h6>
                                        <h2 class="mb-0">12</h2>
                                    </div>
                                    <i class='bx bxs-calendar-event' style="font-size: 2.5rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase mb-0">Tickets</h6>
                                        <h2 class="mb-0">45</h2>
                                    </div>
                                    <i class='bx bxs-ticket' style="font-size: 2.5rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase mb-0">Upcoming</h6>
                                        <h2 class="mb-0">5</h2>
                                    </div>
                                    <i class='bx bx-time' style="font-size: 2.5rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="card-title text-uppercase mb-0">Notifications</h6>
                                        <h2 class="mb-0">3</h2>
                                    </div>
                                    <i class='bx bxs-bell' style="font-size: 2.5rem; opacity: 0.3;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="card">
                    <div class="card-header">
                        <i class='bx bx-history me-2'></i> Recent Activity
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class='bx bx-check-circle text-success me-2'></i>
                                    You logged in to the system
                                </div>
                                <small class="text-muted">Just now</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class='bx bx-calendar-event text-primary me-2'></i>
                                    New event "Tech Conference 2023" created
                                </div>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <i class='bx bx-user text-info me-2'></i>
                                    Profile information updated
                                </div>
                                <small class="text-muted">Yesterday</small>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
