<?php
require_once __DIR__ . '/../includes/db-connection.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    
    try {
        // Get total number of bets
        $totalStmt = $conn->prepare("SELECT COUNT(*) as total FROM bet_records WHERE user_id = :user_id");
        $totalStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $totalStmt->execute();
        $totalData = $totalStmt->fetch(PDO::FETCH_ASSOC);
        $totalBets = $totalData['total'];
        
        // Get total number of wins
        $winStmt = $conn->prepare("SELECT COUNT(*) as wins FROM bet_records WHERE user_id = :user_id AND outcome = 'Won'");
        $winStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $winStmt->execute();
        $winData = $winStmt->fetch(PDO::FETCH_ASSOC);
        $totalWins = $winData['wins'];
        
        // Calculate win percentage
        $winPercentage = ($totalBets > 0) ? round(($totalWins / $totalBets) * 100) : 0;
        
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
        $totalBets = 0;
        $totalWins = 0;
        $winPercentage = 0;
    }
} else {
    // User not logged in, set defaults
    $totalBets = 0;
    $totalWins = 0;
    $winPercentage = 0;
}
?>