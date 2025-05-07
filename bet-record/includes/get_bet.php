<?php
// bet-record/includes/get_bet.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Add error logging
error_log("get_bet.php called");

// Require authentication
require_once __DIR__ . '/../../user-management/auth.php';
requireLogin();

// Database connection
require_once __DIR__ . "/../../includes/db-connection.php";

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'bet' => null
];

// Log request data
error_log("GET data: " . print_r($_GET, true));
error_log("Session user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'Not set'));

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $response['message'] = 'Bet ID is required';
    error_log("Error: Bet ID not provided");
    echo json_encode($response);
    exit;
}

// Sanitize the ID
$bet_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
error_log("Sanitized bet_id: $bet_id");

// Get the current user ID from session
$user_id = $_SESSION['user_id'];
error_log("User ID from session: $user_id");

try {
    // Verify database connection
    error_log("Database connection status: " . ($conn ? 'Connected' : 'Not connected'));
    
    // Prepare query to get bet details
    $sql = "SELECT * FROM bet_records WHERE id = :bet_id AND user_id = :user_id";
    error_log("SQL Query: $sql");
    error_log("Parameters: bet_id=$bet_id, user_id=$user_id");
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':bet_id', $bet_id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    
    error_log("Query executed. Row count: " . $stmt->rowCount());
    
    // Check if bet exists and belongs to the current user
    if ($stmt->rowCount() > 0) {
        $bet = $stmt->fetch(PDO::FETCH_ASSOC);
        error_log("Bet data fetched: " . print_r($bet, true));
        $response['success'] = true;
        $response['bet'] = $bet;
    } else {
        // If no row found, try a query without user_id to see if the bet exists at all
        $check_sql = "SELECT id, user_id FROM bet_records WHERE id = :bet_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':bet_id', $bet_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $check_bet = $check_stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Bet exists but belongs to user_id: " . $check_bet['user_id'] . " (current user: $user_id)");
            $response['message'] = 'You do not have permission to edit this bet';
        } else {
            error_log("No bet found with ID: $bet_id");
            $response['message'] = 'Bet not found';
        }
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    $response['message'] = 'Database error: ' . $e->getMessage();
}

// Add this right after the try block begins
$check_any_sql = "SELECT COUNT(*) FROM bet_records";
$check_any_stmt = $conn->query($check_any_sql);
$total_bets = $check_any_stmt->fetchColumn();
error_log("Total bets in database: $total_bets");

// Also check for this specific bet ID without user filtering
$check_id_sql = "SELECT COUNT(*) FROM bet_records WHERE id = :bet_id";
$check_id_stmt = $conn->prepare($check_id_sql);
$check_id_stmt->bindParam(':bet_id', $bet_id);
$check_id_stmt->execute();
$id_exists = $check_id_stmt->fetchColumn();
error_log("Bets with ID $bet_id: $id_exists");

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>