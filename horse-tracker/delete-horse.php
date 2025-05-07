<?php
// delete-horse.php
header('Content-Type: application/json');
require_once 'horse-tracker-functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log access attempt
error_log("Delete horse request received");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    error_log("User not authenticated");
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];
error_log("User ID: $user_id");

// Check if horse_id is set in POST or GET (for flexibility)
$horse_id = null;
if (isset($_POST['horse_id'])) {
    $horse_id = $_POST['horse_id'];
} elseif (isset($_GET['horse_id'])) {
    $horse_id = $_GET['horse_id'];
} elseif (isset($_POST['id'])) { // Also check for 'id' parameter
    $horse_id = $_POST['id'];
} elseif (isset($_GET['id'])) {
    $horse_id = $_GET['id'];
}

if (!$horse_id) {
    error_log("No horse ID provided");
    echo json_encode(['success' => false, 'message' => 'Horse ID is required']);
    exit;
}

error_log("Attempting to delete horse ID: $horse_id");

try {
    $db = getDbConnection();
    
    // First check if the horse exists at all
    $stmt = $db->prepare("SELECT id, user_id FROM horse_tracker WHERE id = :id");
    $stmt->bindParam(':id', $horse_id, PDO::PARAM_INT);
    $stmt->execute();
    
    $horse = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$horse) {
        error_log("Horse not found: $horse_id");
        echo json_encode(['success' => false, 'message' => 'Horse not found in database']);
        exit;
    }
    
    // Then check if it belongs to the user
    if ($horse['user_id'] != $user_id) {
        error_log("Access denied. Horse $horse_id belongs to user {$horse['user_id']}, not $user_id");
        echo json_encode(['success' => false, 'message' => 'Access denied. This horse does not belong to you.']);
        exit;
    }
    
    // Delete the horse
    $deleteStmt = $db->prepare("DELETE FROM horse_tracker WHERE id = :id AND user_id = :user_id");
    $deleteStmt->bindParam(':id', $horse_id, PDO::PARAM_INT);
    $deleteStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $result = $deleteStmt->execute();
    
    if ($result) {
        error_log("Horse $horse_id deleted successfully");
        echo json_encode(['success' => true, 'message' => 'Horse deleted successfully']);
    } else {
        error_log("Failed to delete horse $horse_id");
        echo json_encode(['success' => false, 'message' => 'Failed to delete horse: Database error']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>