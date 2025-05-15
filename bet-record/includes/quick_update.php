<?php
// includes/quick_update.php
header('Content-Type: application/json');

// Include database connection
require_once "../../includes/db-connection.php";

// Include helper functions
require_once "functions.php";

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

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Check for required parameters
if (!isset($_POST['id']) || empty($_POST['id']) || !isset($_POST['outcome'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
    exit;
}

try {
    $bet_id = (int)$_POST['id'];
    $outcome = $_POST['outcome'];
    
    // Validate outcome
    if (!in_array($outcome, ['Won', 'Lost', 'Pending', 'Void'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid outcome value']);
        exit;
    }
    
    // First check if the bet exists and belongs to the user
    $bet_check = $conn->prepare("SELECT id FROM bet_records WHERE id = ? AND user_id = ?");
    $bet_check->execute([$bet_id, $user_id]);
    $bet_data = $bet_check->fetch();
    
    if (!$bet_data) {
        echo json_encode(['success' => false, 'message' => 'Bet not found or you do not have permission to update it']);
        exit;
    }
    
    // Update the outcome
    $stmt = $conn->prepare("UPDATE bet_records SET outcome = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$outcome, $bet_id, $user_id]);
    
    echo json_encode(['success' => true, 'message' => 'Bet outcome updated to ' . $outcome]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>