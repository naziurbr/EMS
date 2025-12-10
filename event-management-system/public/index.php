<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
$config = require __DIR__ . '/../config/app.php';

// Set error reporting
if ($config['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Define base URL
define('BASE_URL', $config['base_url']);

// Database connection
try {
    $pdo = new PDO(
        "mysql:host={$config['db']['host']};dbname={$config['db']['database']};charset={$config['db']['charset']}",
        $config['db']['username'],
        $config['db']['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Helper functions
function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

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
                $pdo = getDB();
                $stmt = $pdo->query("SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 3");
                $events = $stmt->fetchAll();

                if (count($events) > 0) {
                    foreach ($events as $event) {
                        echo '<div class="col-md-4 mb-4">';
                        echo '  <div class="card h-100">';
                        echo '    <img src="' . htmlspecialchars($event['image_url'] ?? 'https://via.placeholder.com/300x200') . '" class="card-img-top" alt="Event Image">';
                        echo '    <div class="card-body">';
                        echo '      <h5 class="card-title">' . htmlspecialchars($event['title']) . '</h5>';
                        echo '      <p class="card-text">' . substr(htmlspecialchars($event['description']), 0, 100) . '...</p>';
                        echo '      <p class="text-muted">' . date('M j, Y', strtotime($event['event_date'])) . '</p>';
                        echo '      <a href="' . url('event.php?id=' . $event['id']) . '" class="btn btn-primary">View Details</a>';
                        echo '    </div>';
                        echo '  </div>';
                        echo '</div>';
                    }
                    echo '<div class="text-center mt-4">';
                    echo '  <a href="' . url('events.php') . '" class="btn btn-outline-primary">View All Events</a>';
                    echo '</div>';
                } else {
                    echo '<div class="col-12 text-center">';
                    echo '  <p>No upcoming events at the moment. Check back soon!</p>';
                    echo '</div>';
                }
            } catch (PDOException $e) {
                echo '<div class="col-12 text-center">';
                echo '  <p>Unable to load events. Please try again later.</p>';
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

// Initialize the application
$app = new App\Core\App();

// Include routes
require BASE_PATH . '/routes/web.php';

// Run the application
$app->run();
