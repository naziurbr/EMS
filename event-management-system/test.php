<?php
require __DIR__ . '/vendor/autoload.php';

echo "<h1>Event Management System</h1>";
echo "<p>If you can see this, the basic setup is working!</p>";

// Test database connection
try {
    $db = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure you have created the database and updated the .env file with the correct credentials.</p>";
}
