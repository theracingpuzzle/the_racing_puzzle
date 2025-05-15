<?php
// includes/edit_bet.php
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

// Check for bet ID
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo json_encode(['success' => false, 'message' => 'Bet ID is required for updates']);
    exit;
}

try {
    // Validate that the bet belongs to the current user
    $bet_id = (int)$_POST['id'];
    
    // First check if the bet exists and belongs to the user
    $bet_check = $conn->prepare("SELECT id FROM bet_records WHERE id = ? AND user_id = ?");
    $bet_check->execute([$bet_id, $user_id]);
    $bet_data = $bet_check->fetch();
    
    if (!$bet_data) {
        echo json_encode(['success' => false, 'message' => 'Bet not found or you do not have permission to edit it']);
        exit;
    }
    
    // Proceed with update
    $stmt = $conn->prepare("
        UPDATE bet_records SET
        bet_type = ?,
        stake = ?,
        selection = ?,
        racecourse = ?,
        odds = ?,
        jockey = ?,
        trainer = ?,
        outcome = ?
        WHERE id = ? AND user_id = ?
    ");
    
    // Get form data with validation
    $bet_type = $_POST['bet_type'] ?? '';
    $stake = floatval($_POST['stake'] ?? 0);
    $selection = $_POST['selection'] ?? '';
    $racecourse = $_POST['racecourse'] ?? '';
    $odds = $_POST['odds'] ?? '';
    $jockey = $_POST['jockey'] ?? '';
    $trainer = $_POST['trainer'] ?? '';
    $outcome = $_POST['outcome'] ?? 'Pending';
    
    // Execute the update
    $stmt->execute([
        $bet_type,
        $stake,
        $selection,
        $racecourse,
        $odds,
        $jockey,
        $trainer,
        $outcome,
        $bet_id,
        $user_id
    ]);
    
    echo json_encode([
        'success' => true, 
        'message' => 'Bet updated successfully',
        'bet' => [
            'id' => $bet_id,
            'bet_type' => $bet_type,
            'stake' => $stake,
            'selection' => $selection,
            'racecourse' => $racecourse,
            'odds' => $odds,
            'jockey' => $jockey,
            'trainer' => $trainer,
            'outcome' => $outcome
        ]
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>