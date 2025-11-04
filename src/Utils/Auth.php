<?php
class Auth {
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
        session_regenerate_id(true);
    }
    
    public static function logout() {
        session_destroy();
        session_start();
        session_regenerate_id(true);
    }
    
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            $base = rtrim(BASE_URL, '/');
            header('Location: ' . $base . '/auth/login');
            exit;
        }
    }
    
    public static function isAdmin() {
        return self::isLoggedIn() && $_SESSION['user_role'] === 'admin';
    }
    
    public static function requireAdmin() {
        if (!self::isAdmin()) {
            http_response_code(403);
            die('Access denied');
        }
    }
    
    public static function user() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        $userModel = new User();
        return $userModel->findById($_SESSION['user_id']);
    }
}


