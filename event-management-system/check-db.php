<?php
// Database configuration
$host = 'localhost';
$dbname = 'event_ems';
$username = 'root';
$password = '';

try {
    // Connect to MySQL
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>✅ Database Connection Successful!</h2>";
    
    // List all tables
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tables in database '$dbname':</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";
    
    // Check if roles exist
    try {
        $roles = $pdo->query("SELECT * FROM roles")->fetchAll(PDO::FETCH_ASSOC);
        echo "<h3>Existing Roles:</h3>";
        echo "<ul>";
        foreach ($roles as $role) {
            echo "<li>{$role['name']} - {$role['description']}</li>";
        }
        echo "</ul>";
    } catch (Exception $e) {
        echo "<p>❌ Error checking roles: " . $e->getMessage() . "</p>";
    }
    
    // Check if admin user exists
    try {
        $admin = $pdo->query("SELECT * FROM users WHERE email = 'admin@example.com'")->fetch(PDO::FETCH_ASSOC);
        if ($admin) {
            echo "<h3>✅ Admin User Exists</h3>";
            echo "<p>Email: " . $admin['email'] . "</p>";
            echo "<p>Username: " . $admin['username'] . "</p>";
        } else {
            echo "<p>❌ Admin user not found</p>";
        }
    } catch (Exception $e) {
        echo "<p>❌ Error checking admin user: " . $e->getMessage() . "</p>";
    }
    
} catch(PDOException $e) {
    echo "<h2>❌ Database Connection Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
    echo "<h3>Troubleshooting:</h3>";
    echo "<ol>";
    echo "<li>Make sure MySQL server is running in XAMPP</li>";
    echo "<li>Check if database 'event_ems' exists</li>";
    echo "<li>Verify database credentials in this script match your XAMPP setup</li>";
    echo "<li>Try creating the database manually: <code>CREATE DATABASE IF NOT EXISTS event_ems</code></li>";
    echo "</ol>";
}
?>
