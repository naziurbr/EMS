<?php

if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            redirect('login.php');
        }
    }
}

if (!function_exists('has_role')) {
    function has_role($role) {
        if (!is_logged_in()) return false;
        
        if (!isset($_SESSION['user_roles'])) {
            try {
                $pdo = new PDO(
                    'mysql:host=localhost;dbname=event_ems',
                    'root',
                    ''
                );
                $stmt = $pdo->prepare("SELECT r.name FROM roles r 
                                     JOIN user_roles ur ON r.id = ur.role_id 
                                     WHERE ur.user_id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                $_SESSION['user_roles'] = $stmt->fetchAll(PDO::FETCH_COLUMN);
            } catch (PDOException $e) {
                return false;
            }
        }
        
        return in_array($role, $_SESSION['user_roles']);
    }
}

if (!function_exists('require_role')) {
    function require_role($role) {
        require_login();
        if (!has_role($role)) {
            http_response_code(403);
            echo '403 Forbidden - Insufficient permissions';
            exit;
        }
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        if (empty($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            http_response_code(403);
            echo 'Invalid CSRF token';
            exit;
        }
    }
}
