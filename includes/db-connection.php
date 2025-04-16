<?php
// SQLite database path
$db_path = __DIR__ . '/../racingpuzzle.db';

// Check if database exists
if (!file_exists($db_path)) {
    die("Error: Database file not found at $db_path");
}

// Check if file and directory are writable
if (!is_writable($db_path) || !is_writable(dirname($db_path))) {
    die("Error: Database or directory is not writable. Check permissions.");
}

try {
    // Create PDO instance for SQLite
    $conn = new PDO('sqlite:' . $db_path);

    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Enable foreign keys support
    $conn->exec('PRAGMA foreign_keys = ON;');

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Set timezone
date_default_timezone_set('UTC');
?>
