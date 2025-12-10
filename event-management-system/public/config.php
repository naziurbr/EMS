<?php
// Session configuration must be set before starting the session
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
    session_start();
}

// Base URL - Update this to match your installation path
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/for%20testing/event-management-system/public');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'event_ems');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application paths
define('APP_ROOT', dirname(__DIR__));
define('VIEW_PATH', APP_ROOT . '/app/views');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set default timezone
date_default_timezone_set('UTC');

// Helper function to get full URL
function url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

// Helper function to redirect
function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

// Database connection
function getDB() {
    static $pdo;
    if (!$pdo) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    return $pdo;
}
?>
