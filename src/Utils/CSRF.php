<?php
class CSRF {
    public static function init() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function generateToken() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function validateToken($token) {
        return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
    }
    
    public static function requireToken() {
        if (!self::validateToken($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            die('CSRF token validation failed');
        }
    }
}


