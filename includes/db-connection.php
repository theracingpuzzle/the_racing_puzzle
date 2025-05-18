<?php
// Check if on Render
if (getenv('RENDER') === 'true') {
    // Use /tmp for Render
    $db_path = '/tmp/racingpuzzle.db';
    
    // Create database with tables if it doesn't exist
    if (!file_exists($db_path)) {
        createDatabase($db_path);
    }
} else {
    // Use existing path for local development
    $db_path = __DIR__ . '/../racingpuzzle.db';
    
    // Check if database exists
    if (!file_exists($db_path)) {
        die("Error: Database file not found at $db_path");
    }
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

// Function to create database and tables
function createDatabase($db_path) {
    try {
        // Create a new database file
        $tempConn = new PDO('sqlite:' . $db_path);
        $tempConn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create your tables
        // REPLACE THESE WITH YOUR ACTUAL TABLE DEFINITIONS
        $tempConn->exec('CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            username TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            email TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
        
        // Create any other tables your application needs
        // $tempConn->exec('CREATE TABLE your_other_table (...)');
        
        // Add a test user
        $password = password_hash('password123', PASSWORD_DEFAULT);
        $tempConn->exec("INSERT INTO users (username, password, email) 
                         VALUES ('testuser', '$password', 'test@example.com')");
        
        // Add any other initial data
        
        // Set permissions
        chmod($db_path, 0666);
        
    } catch (PDOException $e) {
        die("Failed to create database: " . $e->getMessage());
    }
}
?>
