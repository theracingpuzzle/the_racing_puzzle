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
        
        // Create all tables based on your schema
        
        // Table: test
        $tempConn->exec('CREATE TABLE test (
            id INTEGER PRIMARY KEY
        )');
        
        // Table: racecourses
        $tempConn->exec('CREATE TABLE racecourses (
            id INTEGER PRIMARY KEY,
            name TEXT
        )');
        
        // Table: horse_tracker
        $tempConn->exec('CREATE TABLE horse_tracker (
            id INTEGER PRIMARY KEY,
            horse_name TEXT NOT NULL,
            trainer TEXT,
            notes TEXT,
            date_added TEXT DEFAULT datetime(\'now\'),
            user_id INTEGER NOT NULL DEFAULT 0,
            horse_id INTEGER NOT NULL DEFAULT 0,
            silk_url TEXT
        )');
        
        // Table: users
        $tempConn->exec('CREATE TABLE users (
            user_id INTEGER PRIMARY KEY,
            username TEXT NOT NULL,
            email TEXT NOT NULL,
            password_hash TEXT NOT NULL,
            password_reset_token TEXT,
            password_reset_expires DATETIME,
            registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
            is_active INTEGER DEFAULT 1
        )');
        
        // Table: user_profiles
        $tempConn->exec('CREATE TABLE user_profiles (
            profile_id INTEGER PRIMARY KEY,
            user_id INTEGER NOT NULL,
            display_name TEXT,
            favorite_racing_type TEXT,
            theme TEXT DEFAULT "light",
            newsletter_subscription INTEGER DEFAULT 0
        )');
        
        // Table: leagues
        $tempConn->exec('CREATE TABLE leagues (
            id INTEGER PRIMARY KEY,
            name TEXT NOT NULL,
            description TEXT,
            creator_id INTEGER NOT NULL,
            ranking_type TEXT NOT NULL,
            pin TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
        
        // Table: league_members
        $tempConn->exec('CREATE TABLE league_members (
            league_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (league_id, user_id)
        )');
        
        // Table: league_bets
        $tempConn->exec('CREATE TABLE league_bets (
            id INTEGER PRIMARY KEY,
            league_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            race_id INTEGER NOT NULL,
            horse_id INTEGER NOT NULL,
            bet_amount REAL NOT NULL,
            odds TEXT NOT NULL,
            is_winner BOOLEAN DEFAULT 0,
            returns REAL DEFAULT 0,
            bet_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )');
        
        // Table: bet_records
        $tempConn->exec('CREATE TABLE bet_records (
            id INTEGER PRIMARY KEY,
            date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            bet_type VARCHAR(50) NOT NULL,
            stake DECIMAL(10, 2) NOT NULL,
            selection VARCHAR(100) NOT NULL,
            racecourse VARCHAR(100) NOT NULL,
            odds VARCHAR(20) NOT NULL,
            jockey VARCHAR(100) NOT NULL,
            trainer VARCHAR(100) NOT NULL,
            outcome VARCHAR(20) NOT NULL,
            returns DECIMAL(10, 2) DEFAULT 0,
            user_id INTEGER NOT NULL DEFAULT 0,
            parent_bet_id INTEGER,
            profit DECIMAL(10, 2) DEFAULT 0
        )');
        
        // Add a test user
        $password_hash = password_hash('password123', PASSWORD_DEFAULT);
        $tempConn->exec("INSERT INTO users (username, email, password_hash, is_active) 
                         VALUES ('testuser', 'test@example.com', '$password_hash', 1)");
        
        // Create test user profile
        $tempConn->exec("INSERT INTO user_profiles (user_id, display_name, theme) 
                         VALUES (1, 'Test User', 'light')");
        
        // Add some test data for racecourses
        $tempConn->exec("INSERT INTO racecourses (name) VALUES ('Ascot')");
        $tempConn->exec("INSERT INTO racecourses (name) VALUES ('Cheltenham')");
        
        // Set permissions
        chmod($db_path, 0666);
        
    } catch (PDOException $e) {
        die("Failed to create database: " . $e->getMessage());
    }
}
?>