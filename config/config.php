
<?php
// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Populate $_ENV and process environment for getenv()/putenv()
        $_ENV[$name] = $value;
        if (function_exists('putenv')) {
            putenv($name . '=' . $value);
        }
    }
}

// Normalize database password keys so both DB_PASS and DB_PASSWORD work
if (!empty($_ENV['DB_PASS']) && empty($_ENV['DB_PASSWORD'])) {
    $_ENV['DB_PASSWORD'] = $_ENV['DB_PASS'];
    if (function_exists('putenv')) { putenv('DB_PASSWORD=' . $_ENV['DB_PASS']); }
}
if (!empty($_ENV['DB_PASSWORD']) && empty($_ENV['DB_PASS'])) {
    $_ENV['DB_PASS'] = $_ENV['DB_PASSWORD'];
    if (function_exists('putenv')) { putenv('DB_PASS=' . $_ENV['DB_PASSWORD']); }
}

// Set error reporting based on environment
if (($_ENV['APP_ENV'] ?? 'development') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Set default timezone
date_default_timezone_set('Asia/Manila');

// Define constants
// Compute BASE_URL dynamically so links work under XAMPP subdirectories
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
$computedBaseUrl = $scheme . '://' . $host . ($scriptDir ?: '/');
define('BASE_URL', rtrim($computedBaseUrl, '/') . '/');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Security headers (skip when running from CLI)
if (php_sapi_name() !== 'cli') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    if (($_ENV['APP_ENV'] ?? 'development') === 'production') {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}
