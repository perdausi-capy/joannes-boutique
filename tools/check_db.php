<?php
declare(strict_types=1);

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Utils/Database.php';

try {
    $db = new Database();
    $pdo = $db->connect();
    $stmt = $pdo->query('SELECT 1');
    $result = $stmt->fetchColumn();
    echo "OK: Connected. SELECT 1 => {$result}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'ERROR: ' . $e->getMessage() . "\n";
}


