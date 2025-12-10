<?php
// Test database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=event_ems',
        'root',
        ''
    );
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo "✅ Database connection successful!<br>";
    echo "Users in database: " . $result['count'] . "<br>";
    
    // Test session
    session_start();
    echo "✅ Session is working";
    
} catch (PDOException $e) {
    echo "❌ Database connection failed: " . $e->getMessage();
}
