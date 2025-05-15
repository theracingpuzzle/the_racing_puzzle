<?php
// delete_bet.php
header('Content-Type: application/json');

// Include database connection
require_once "../includes/db-connection.php";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check for bet ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bet ID is required']);
    exit;
}

try {
    $bet_id = (int)$_GET['id'];
    
    // First check if the bet exists and belongs to the user
    $bet_check = $conn->prepare("SELECT id FROM bet_records WHERE id = ? AND user_id = ?");
    $bet_check->execute([$bet_id, $user_id]);
    $bet_data = $bet_check->fetch();
    
    if (!$bet_data) {
        echo json_encode(['success' => false, 'message' => 'Bet not found or you do not have permission to delete it']);
        exit;
    }
    
    // Delete the bet
    $stmt = $conn->prepare("DELETE FROM bet_records WHERE id = ? AND user_id = ?");
    $stmt->execute([$bet_id, $user_id]);
    
    // Check if the request was AJAX
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
    if ($isAjax) {
        echo json_encode(['success' => true, 'message' => 'Bet deleted successfully']);
    } else {
        // For non-AJAX requests, redirect back to the bet records page with a success message
        $_SESSION['success_message'] = 'Bet deleted successfully!';
        header('Location: index.php');
        exit;
    }
    
} catch (PDOException $e) {
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    
    if ($isAjax) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    } else {
        $_SESSION['error_message'] = 'Error deleting bet: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}
?>