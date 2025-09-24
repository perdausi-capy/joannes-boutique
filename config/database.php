<?php
// Database connection using PDO

declare(strict_types=1);

$dbConfig = [
	'driver' => getenv('DB_DRIVER') ?: 'mysql',
	'host' => getenv('DB_HOST') ?: '127.0.0.1',
	'port' => getenv('DB_PORT') ?: '3306',
	'database' => getenv('DB_NAME') ?: 'joannes_boutique',
	'username' => getenv('DB_USER') ?: 'root',
	'password' => getenv('DB_PASSWORD') ?: '',
	'charset' => 'utf8mb4',
];

$dsn = sprintf(
	"%s:host=%s;port=%s;dbname=%s;charset=%s",
	$dbConfig['driver'],
	$dbConfig['host'],
	$dbConfig['port'],
	$dbConfig['database'],
	$dbConfig['charset']
);

try {
	$pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	]);
} catch (PDOException $e) {
	http_response_code(500);
	echo 'Database connection failed.';
	exit;
}

return $pdo;


