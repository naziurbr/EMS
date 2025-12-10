<?php
require __DIR__ . '/vendor/autoload.php';

// Function to get hashed password
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

try {
    $db = new PDO(
        'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'],
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $db->beginTransaction();

    // 1. Insert default roles if they don't exist
    $roles = [
        ['name' => 'super_admin', 'description' => 'Super Administrator with full access'],
        ['name' => 'admin', 'description' => 'Administrator with full access to most features'],
        ['name' => 'event_manager', 'description' => 'Can create and manage events'],
        ['name' => 'staff', 'description' => 'Staff member with limited access'],
        ['name' => 'user', 'description' => 'Regular user']
    ];

    $roleIds = [];
    foreach ($roles as $role) {
        $stmt = $db->prepare("INSERT IGNORE INTO roles (name, description) VALUES (?, ?)");
        $stmt->execute([$role['name'], $role['description']]);
        $roleIds[$role['name']] = $db->lastInsertId();
    }

    // 2. Create admin user
    $adminEmail = 'admin@example.com';
    $adminPassword = 'admin123'; // In production, use a strong password
    $hashedPassword = hashPassword($adminPassword);

    $stmt = $db->prepare("INSERT INTO users (username, email, password, first_name, last_name, is_active) 
                         VALUES (?, ?, ?, ?, ?, 1)");
    $stmt->execute(['admin', $adminEmail, $hashedPassword, 'Admin', 'User']);
    $userId = $db->lastInsertId();

    // 3. Assign super_admin role
    $stmt = $db->prepare("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)");
    $stmt->execute([$userId, $roleIds['super_admin']]);

    // Commit transaction
    $db->commit();

    echo "✅ Setup completed successfully!\n";
    echo "Admin email: " . $adminEmail . "\n";
    echo "Admin password: " . $adminPassword . "\n";
    echo "\n⚠️ IMPORTANT: Change these credentials after your first login!\n";

} catch (PDOException $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
}
