<?php
// get_bet.php
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
    
    // Get the bet details
    $stmt = $conn->prepare("
        SELECT * FROM bet_records 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$bet_id, $user_id]);
    $bet = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$bet) {
        echo json_encode(['success' => false, 'message' => 'Bet not found or you do not have permission to view it']);
        exit;
    }
    
    // Format the data for the response
$formattedBet = [
    'id' => $bet['id'],
    'bet_type' => $bet['bet_type'],
    'stake' => $bet['stake'],
    'selection' => $bet['selection'],
    'racecourse' => $bet['racecourse'],
    'odds' => $bet['odds'],
    'jockey' => $bet['jockey'],
    'trainer' => $bet['trainer'], 
    'outcome' => $bet['outcome'],
    'returns' => $bet['returns'],
    'profit' => $bet['profit'], // Add profit to the response
    'date' => $bet['date'],
    'formatted_date' => date('d/m/Y H:i', strtotime($bet['date']))
];
    
    echo json_encode(['success' => true, 'bet' => $formattedBet]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}
?>