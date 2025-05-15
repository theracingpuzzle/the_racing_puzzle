<?php
// test_bet_query.php - Direct database check for a specific bet record

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
require_once __DIR__ . "/../../includes/db-connection.php";

// The specific ID we're looking for
$test_id = 20;

// Initialize output
echo "<h1>Database Test for Bet ID $test_id</h1>";
echo "<pre>";

try {
    // Test 1: Basic connection
    echo "Test 1: Database connection: " . ($conn ? "SUCCESS" : "FAILED") . "\n\n";
    
    // Test 2: Count all records
    $count_sql = "SELECT COUNT(*) FROM bet_records";
    $count_stmt = $conn->query($count_sql);
    $total_count = $count_stmt->fetchColumn();
    echo "Test 2: Total records in bet_records table: $total_count\n\n";
    
    // Test 3: Directly query the specific record
    $direct_sql = "SELECT * FROM bet_records WHERE id = $test_id";
    echo "Running query: $direct_sql\n";
    $direct_stmt = $conn->query($direct_sql);
    $direct_count = $direct_stmt->rowCount();
    echo "Records found: $direct_count\n\n";
    
    if ($direct_count > 0) {
        $record = $direct_stmt->fetch(PDO::FETCH_ASSOC);
        echo "Record details:\n";
        print_r($record);
        echo "\n";
    } else {
        echo "No record found with ID $test_id\n\n";
        
        // Test 4: Find if the ID exists anywhere
        $max_id_sql = "SELECT MAX(id) FROM bet_records";
        $max_id = $conn->query($max_id_sql)->fetchColumn();
        echo "Maximum ID in bet_records table: $max_id\n\n";
        
        // Test 5: Get a list of all IDs
        echo "List of all IDs in bet_records table:\n";
        $all_ids_sql = "SELECT id FROM bet_records ORDER BY id";
        $all_ids_stmt = $conn->query($all_ids_sql);
        $all_ids = $all_ids_stmt->fetchAll(PDO::FETCH_COLUMN);
        print_r($all_ids);
        echo "\n";
    }
    
    // Test 6: Check if SQLite is using integer primary keys correctly
    echo "Testing SQLite primary key behavior:\n";
    $test_sql = "SELECT * FROM sqlite_master WHERE type='table' AND name='bet_records'";
    $test_stmt = $conn->query($test_sql);
    $table_info = $test_stmt->fetch(PDO::FETCH_ASSOC);
    echo "Table creation SQL:\n";
    echo $table_info['sql'] . "\n\n";
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}

echo "</pre>";