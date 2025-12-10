<?php
// Database connection
try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=event_ems;charset=utf8mb4',
        'root',
        ''
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check users table
    echo "<h2>Users in database:</h2>";
    $stmt = $pdo->query("SELECT id, email, username, password FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($users)) {
        echo "No users found in the database.";
    } else {
        echo "<table border='1' cellpadding='8'>";
        echo "<tr><th>ID</th><th>Email</th><th>Username</th><th>Password Hash</th><th>Verification</th></tr>";
        
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . substr(htmlspecialchars($user['password']), 0, 30) . "...</td>";
            
            // Test password verification
            $testPassword = 'password'; // Default password we've been using
            $isValid = password_verify($testPassword, $user['password']);
            $status = $isValid ? '✅ Valid' : '❌ Invalid';
            echo "<td>Test with 'password': $status</td>";
            
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Check user_roles table
    echo "<h2>User Roles:</h2>";
    $stmt = $pdo->query("SELECT ur.user_id, r.name as role_name 
                        FROM user_roles ur 
                        JOIN roles r ON ur.role_id = r.id");
    $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($roles)) {
        echo "No user roles found.";
    } else {
        echo "<ul>";
        foreach ($roles as $role) {
            echo "<li>User ID: " . $role['user_id'] . " - Role: " . $role['role_name'] . "</li>";
        }
        echo "</ul>";
    }
    
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>
