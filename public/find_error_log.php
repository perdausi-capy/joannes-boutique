<?php
/**
 * Quick script to find PHP error log location
 */
echo "=== PHP Error Log Location Finder ===\n\n";

// Method 1: Check ini_get
$errorLogPath = ini_get('error_log');
if ($errorLogPath) {
    echo "✓ Error log path from ini_get('error_log'):\n";
    echo "  $errorLogPath\n";
    echo "  File exists: " . (file_exists($errorLogPath) ? 'YES' : 'NO') . "\n\n";
} else {
    echo "✗ ini_get('error_log') returned empty\n";
    echo "  This means error_log is not explicitly set in php.ini\n\n";
}

// Method 2: Check common XAMPP locations
echo "Checking common XAMPP locations:\n";
$commonPaths = [
    'C:\\xampp\\php\\logs\\php_error_log',
    'C:\\xampp\\php\\logs\\error_log',
    'C:\\xampp\\apache\\logs\\error.log',
    'C:\\xampp\\apache\\logs\\php_error.log',
    'C:\\Windows\\php_error.log',
    getcwd() . '\\php_error_log',
    __DIR__ . '\\php_error_log',
];

foreach ($commonPaths as $path) {
    if (file_exists($path)) {
        echo "  ✓ FOUND: $path\n";
        echo "    Size: " . filesize($path) . " bytes\n";
        echo "    Modified: " . date('Y-m-d H:i:s', filemtime($path)) . "\n\n";
    }
}

// Method 3: Check php.ini location
$phpIniPath = php_ini_loaded_file();
echo "PHP Configuration:\n";
echo "  php.ini location: $phpIniPath\n";
if ($phpIniPath && file_exists($phpIniPath)) {
    $phpIniContent = file_get_contents($phpIniPath);
    if (preg_match('/error_log\s*=\s*(.+)/i', $phpIniContent, $matches)) {
        echo "  error_log setting: " . trim($matches[1]) . "\n";
    } else {
        echo "  error_log setting: Not explicitly set (using system default)\n";
    }
}

echo "\n=== Quick Test ===\n";
error_log("TEST MESSAGE - " . date('Y-m-d H:i:s'));
echo "Test message written to error log.\n";
echo "Check the paths above to find it.\n";

