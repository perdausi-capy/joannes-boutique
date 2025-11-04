<?php
/**
 * Database Class
 * Handles PDO connection
 */

class Database {
    private $pdo;
    
    public function __construct() {
        // Load the database configuration and get PDO connection
        $this->pdo = require __DIR__ . '/../config/database.php';
    }
    
    public function connect() {
        return $this->pdo;
    }
}
?>