<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

echo "<h2>Login Debug Information</h2>";

try {
    // 1. Check database connection
    echo "<h3>1. Database Connection Test</h3>";
    $pdo = new PDO(
        'mysql:host=localhost;dbname=event_ems;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "✅ Database connection successful<br>";

    // 2. Check if users table exists
    $tables = $pdo->query("SHOW TABLES LIKE 'users'")->fetchAll();
    if (empty($tables)) {
        die("❌ Error: 'users' table does not exist");
    }
    echo "✅ Users table exists<br>";

    // 3. Check if admin user exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute(['admin@example.com']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("❌ Error: No admin user found with email 'admin@example.com'");
    }
    echo "✅ Admin user found<br>";

    // 4. Test password verification
    $testPassword = 'password';
    $isValid = password_verify($testPassword, $user['password']);
    
    if (!$isValid) {
        echo "❌ Password verification failed for 'password'<br>";
        echo "Stored hash: " . substr($user['password'], 0, 20) . "...<br>";
        
        // Try to create a new hash
        $newHash = password_hash($testPassword, PASSWORD_DEFAULT);
        echo "New hash for 'password': " . substr($newHash, 0, 20) . "...<br>";
        
        // Update the password
        $updateStmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $updateStmt->execute([$newHash, 'admin@example.com']);
        echo "✅ Password has been reset. Try logging in again with password: password<br>";
    } else {
        echo "✅ Password verification successful<br>";
    }

    // 5. Check session configuration
    echo "<h3>2. Session Information</h3>";
    echo "Session ID: " . session_id() . "<br>";
    echo "Session Save Path: " . session_save_path() . "<br>";
    
    // 6. Test session writing
    $_SESSION['test_key'] = 'test_value';
    $sessionTest = (isset($_SESSION['test_key']) && $_SESSION['test_key'] === 'test_value');
    echo "Session write test: " . ($sessionTest ? "✅ Successful" : "❌ Failed") . "<br>";
    
    // 7. Check PHP configuration
    echo "<h3>3. PHP Configuration</h3>";
    echo "PHP Version: " . phpversion() . "<br>";
    echo "PDO Available: " . (extension_loaded('pdo') ? "✅ Yes" : "❌ No") . "<br>";
    echo "PDO MySQL Available: " . (extension_loaded('pdo_mysql') ? "✅ Yes" : "❌ No") . "<br>";
    
    // 8. Check file permissions
    echo "<h3>4. File Permissions</h3>";
    $filesToCheck = [
        __DIR__ . '/public',
        __DIR__ . '/app',
        session_save_path()
    ];
    
    foreach ($filesToCheck as $file) {
        if (is_writable($file)) {
            echo "✅ Writable: $file<br>";
        } else {
            echo "❌ Not writable: $file<br>";
        }
    }
    
    // 9. Try to log in programmatically
    echo "<h3>5. Login Test</h3>";
    if ($isValid) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        
        echo "✅ Session variables set. <a href='public/dashboard.php'>Go to Dashboard</a><br>";
        echo "<pre>Session Data: " . print_r($_SESSION, true) . "</pre>";
    }
    
} catch (PDOException $e) {
    echo "<div style='color:red;'>❌ Database Error: " . $e->getMessage() . "</div>";
}

// 10. Show login form
?>

<h3>Manual Login Test</h3>
<form action="public/login.php" method="post" style="border: 1px solid #ccc; padding: 20px; max-width: 400px;">
    <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" id="email" name="email" value="admin@example.com" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password" name="password" value="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<h3>Next Steps</h3>
<ol>
    <li>Check if the database 'event_ems' exists and is accessible</li>
    <li>Verify the 'users' table has the admin account</li>
    <li>Check PHP error logs for any issues</li>
    <li>Ensure the session save path is writable</li>
    <li>Try clearing your browser cookies and cache</li>
</ol>
