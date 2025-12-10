<?php
// Database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=event_ems;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $pdo->beginTransaction();

    // 1. Create admin user if not exists
    $email = 'admin@example.com';
    $username = 'admin';
    $password = 'password'; // New password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if admin exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Update existing admin
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashedPassword, $email]);
        $userId = $user['id'];
        echo "<p>✅ Admin password updated successfully.</p>";
    } else {
        // Create new admin
        $stmt = $pdo->prepare("INSERT INTO users (email, username, password, email_verified_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$email, $username, $hashedPassword]);
        $userId = $pdo->lastInsertId();
        echo "<p>✅ Admin user created successfully.</p>";
    }

    // 2. Get admin role ID
    $stmt = $pdo->query("SELECT id FROM roles WHERE name = 'admin'");
    $role = $stmt->fetch();
    
    if (!$role) {
        // Create admin role if not exists
        $pdo->exec("INSERT INTO roles (name, description) VALUES ('admin', 'Administrator')");
        $roleId = $pdo->lastInsertId();
    } else {
        $roleId = $role['id'];
    }

    // 3. Assign admin role to user
    // First, remove any existing role assignments
    $stmt = $pdo->prepare("DELETE FROM user_roles WHERE user_id = ?");
    $stmt->execute([$userId]);
    
    // Then add the admin role
    $stmt = $pdo->prepare("INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $stmt->execute([$userId, $roleId]);

    // Commit transaction
    $pdo->commit();

    echo "<p>✅ Admin user setup complete.</p>";
    echo "<p>You can now login with:</p>";
    echo "<ul>";
    echo "<li>Email: admin@example.com</li>";
    echo "<li>Password: password</li>";
    echo "</ul>";
    echo "<p><a href='public/login.php'>Go to Login Page</a></p>";

} catch (PDOException $e) {
    // Rollback transaction on error
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    die("Error: " . $e->getMessage());
}
?>
