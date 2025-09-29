<?php
require_once __DIR__ . '/../src/Utils/Database.php';

// Optional models not strictly required, using raw SQL for speed

$database = new Database();
$db = $database->connect();

try {
	echo "Starting backfill of product images...\n";
	// Ensure table exists
	$db->exec("CREATE TABLE IF NOT EXISTS product_images (
		id INT PRIMARY KEY AUTO_INCREMENT,
		product_id INT NOT NULL,
		filename VARCHAR(255) NOT NULL,
		sort_order INT DEFAULT 0,
		created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
	)");

	// Add sort_order if missing
	try {
		$db->exec("ALTER TABLE product_images ADD COLUMN sort_order INT DEFAULT 0");
	} catch (PDOException $e) {
		// Ignore if it already exists
	}

	// Fetch products with a non-empty main image
	$stmt = $db->query("SELECT id, image FROM products WHERE image IS NOT NULL AND image <> ''");
	$products = $stmt->fetchAll();
	$inserted = 0;
	$skipped = 0;

	$checkStmt = $db->prepare("SELECT COUNT(*) AS cnt FROM product_images WHERE product_id = :pid AND filename = :fn");
	$insStmt = $db->prepare("INSERT INTO product_images (product_id, filename, sort_order) VALUES (:pid, :fn, 0)");

	foreach ($products as $p) {
		$pid = (int)$p['id'];
		$fn = $p['image'];
		$checkStmt->execute([':pid' => $pid, ':fn' => $fn]);
		$row = $checkStmt->fetch();
		if ((int)($row['cnt'] ?? 0) === 0) {
			$insStmt->execute([':pid' => $pid, ':fn' => $fn]);
			$inserted++;
		} else {
			$skipped++;
		}
	}

	echo "Backfill complete. Inserted: {$inserted}, Skipped (already present): {$skipped}.\n";
	exit(0);
} catch (PDOException $e) {
	fwrite(STDERR, "Backfill error: " . $e->getMessage() . "\n");
	exit(1);
}
