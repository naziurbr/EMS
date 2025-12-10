<?php
require __DIR__ . '/vendor/autoload.php';

// Database connection
try {
    $db = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Start transaction
    $db->beginTransaction();

    // 1. Disable foreign key checks temporarily
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

    // 2. Truncate tables in correct order to avoid foreign key constraints
    $tables = ['user_roles', 'users', 'roles'];
    foreach ($tables as $table) {
        $db->exec("TRUNCATE TABLE `$table`");
    }

    // 3. Enable foreign key checks
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");

    // 4. Insert default roles
    $roles = [
        ['name' => 'super_admin', 'description' => 'Super Administrator with full access'],
        ['name' => 'admin', 'description' => 'Administrator with full access to most features'],
        ['name' => 'event_manager', 'description' => 'Can create and manage events'],
        ['name' => 'staff', 'description' => 'Staff member with limited access'],
        ['name' => 'user', 'description' => 'Regular user']
    ];

    $roleStmt = $db->prepare("INSERT INTO roles (name, description) VALUES (?, ?)");
    foreach ($roles as $role) {
        $roleStmt->execute([$role['name'], $role['description']]);
    }

    // 5. Get the super_admin role ID
    $superAdminRole = $db->query("SELECT id FROM roles WHERE name = 'super_admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    
    if (!$superAdminRole) {
        throw new Exception("Failed to create super_admin role");
    }

    // 6. Create admin user
    $adminEmail = 'admin@example.com';
    $adminPassword = password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]);
    
    $userStmt = $db->prepare("INSERT INTO users (username, email, password, first_name, last_name, is_active, created_at, updated_at) 
                            VALUES (?, ?, ?, ?, ?, 1, NOW(), NOW())");
    $userStmt->execute(['admin', $adminEmail, $adminPassword, 'Admin', 'User']);
    $userId = $db->lastInsertId();

    // 7. Assign super_admin role
    $userRoleStmt = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $userRoleStmt->execute([$userId, $superAdminRole['id']]);

    // Commit transaction
    $db->commit();

    echo "✅ Database setup completed successfully!\n";
    echo "Admin Login Details:\n";
    echo "Email: " . $adminEmail . "\n";
    echo "Password: admin123\n\n";
    echo "⚠️ IMPORTANT: Change these credentials after your first login!\n";

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
    if (strpos($e->getMessage(), 'SQLSTATE') !== false) {
        echo "\nDatabase Error Details:\n";
        echo "Code: " . $e->getCode() . "\n";
        echo "SQL State: " . $e->errorInfo[0] . "\n";
        echo "Driver Error: " . ($e->errorInfo[1] ?? 'N/A') . "\n";
        echo "Error Message: " . ($e->errorInfo[2] ?? $e->getMessage()) . "\n";
    }
}
