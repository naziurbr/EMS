<?php
// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        // Database connection
        $pdo = new PDO('mysql:host=localhost;dbname=event_ems', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Check user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            echo "✅ Login successful!<br>";
            echo "User ID: " . $user['id'] . "<br>";
            echo "Email: " . $user['email'] . "<br>";
            echo "<a href='login.php'>Go to Login Page</a>";
            exit;
        } else {
            $error = "❌ Invalid email or password";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Test Login</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Email:</label>
                                <input type="email" name="email" class="form-control" required value="admin@example.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password:</label>
                                <input type="password" name="password" class="form-control" required value="password">
                            </div>
                            <button type="submit" class="btn btn-primary">Test Login</button>
                        </form>
                        
                        <div class="mt-3">
                            <p>Try these test credentials:</p>
                            <p>Email: admin@example.com<br>Password: password</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
