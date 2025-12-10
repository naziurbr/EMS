<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
require_once __DIR__ . '/config.php';

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get database connection
$pdo = getDB();

// Set page title
$pageTitle = 'Home - Event Management System';

// Include header
require_once __DIR__ . '/templates/header.php';
?>

<!-- Hero Section -->
<section class="hero-section text-center">
    <div class="container">
        <h1 class="display-4 fw-bold mb-4">Welcome to EventEase</h1>
        <p class="lead mb-4">Discover and manage events with ease</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="<?php echo url('events.php'); ?>" class="btn btn-primary btn-lg">Browse Events</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a href="<?php echo url('register.php'); ?>" class="btn btn-outline-light btn-lg">Sign Up</a>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="container mb-5">
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="feature-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <h3>Find Events</h3>
            <p>Discover exciting events happening near you or create your own.</p>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-icon">
                <i class="bi bi-ticket-perforated"></i>
            </div>
            <h3>Easy Registration</h3>
            <p>Register for events with just a few clicks.</p>
        </div>
        <div class="col-md-4 mb-4">
            <div class="feature-icon">
                <i class="bi bi-people"></i>
            </div>
            <h3>Connect</h3>
            <p>Connect with other event enthusiasts and organizers.</p>
        </div>
    </div>
</section>

<!-- Upcoming Events Preview -->
<section class="bg-light py-5">
    <div class="container">
        <h2 class="text-center mb-4">Upcoming Events</h2>
        <div class="row">
            <?php
            try {
                $stmt = $pdo->query("SELECT e.*, ec.name as category_name 
                                   FROM events e 
                                   LEFT JOIN event_categories ec ON e.category_id = ec.id 
                                   WHERE e.start_datetime >= NOW() 
                                   AND e.status = 'approved'
                                   ORDER BY e.start_datetime ASC 
                                   LIMIT 3");
                $events = $stmt->fetchAll();

                if (count($events) > 0) {
                    foreach ($events as $event) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '  <div class="card h-100">';
                        if (!empty($event['image_url'])) {
                            echo '    <img src="' . htmlspecialchars($event['image_url']) . '" class="card-img-top" alt="' . htmlspecialchars($event['title']) . '">';
                        } else {
                            echo '    <div class="card-img-top bg-secondary text-white d-flex align-items-center justify-content-center" style="height: 200px;">';
                            echo '        <i class="bi bi-calendar-event" style="font-size: 3rem;"></i>';
                            echo '    </div>';
                        }
                        echo '    <div class="card-body d-flex flex-column">';
                        echo '      <h5 class="card-title">' . htmlspecialchars($event['title']) . '</h5>';
                        if (!empty($event['category_name'])) {
                            echo '      <span class="badge bg-primary mb-2">' . htmlspecialchars($event['category_name']) . '</span>';
                        }
                        echo '      <p class="card-text flex-grow-1">' . substr(htmlspecialchars($event['description']), 0, 100) . '...</p>';
                        echo '      <div class="mt-auto">';
                        echo '          <p class="text-muted mb-2"><i class="bi bi-calendar-event"></i> ' . date('M j, Y', strtotime($event['start_datetime'])) . '</p>';
                        echo '          <a href="' . url('event.php?id=' . $event['id']) . '" class="btn btn-primary w-100">View Details</a>';
                        echo '      </div>';
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                    echo '<div class="col-12 text-center mt-4">';
                    echo '  <a href="' . url('events.php') . '" class="btn btn-outline-primary">View All Events</a>';
                    echo '</div>';
                } else {
                    echo '<div class="col-12 text-center">';
                    echo '  <div class="alert alert-info">No upcoming events at the moment. Check back soon!</div>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                error_log('Database error: ' . $e->getMessage());
                echo '<div class="col-12 text-center">';
                echo '  <div class="alert alert-danger">Unable to load events. Please try again later.</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<?php
// Include footer
require_once __DIR__ . '/templates/footer.php';
?>

<?php
// Initialize the application
$app = new App\Core\App($config);

// Include routes
require APP_PATH . '/routes/web.php';

// Run the application
$app->run();

// Require helper functions
require BASE_PATH . '/app/helpers/url.php';
require BASE_PATH . '/app/helpers/security.php';
require BASE_PATH . '/app/helpers/string.php';
require BASE_PATH . '/app/helpers/validator.php';
require BASE_PATH . '/app/helpers/auth.php';

// Require core files
require BASE_PATH . '/app/core/App.php';
require BASE_PATH . '/app/core/Controller.php';
require BASE_PATH . '/app/core/Model.php';
require BASE_PATH . '/app/core/Database.php';
require BASE_PATH . '/app/core/Request.php';
require BASE_PATH . '/app/core/Response.php';
require BASE_PATH . '/app/core/Session.php';
require BASE_PATH . '/app/core/View.php';
