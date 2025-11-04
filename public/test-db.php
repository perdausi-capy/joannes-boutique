<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Utils/Database.php';

header('Content-Type: text/plain');
echo "Testing DB connection...\n";

try {
    $db = new Database();
    $pdo = $db->connect();
    $stmt = $pdo->query('SELECT DATABASE() as db, VERSION() as version');
    $row = $stmt->fetch();
    echo "OK: Connected to database: " . ($row['db'] ?? '(unknown)') . "\n";
    echo "MySQL version: " . ($row['version'] ?? '(unknown)') . "\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'ERROR: ' . $e->getMessage() . "\n";
}


