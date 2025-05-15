<?php
require_once __DIR__ . '/../includes/db-connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Calculate Net Profit/Loss
    $stmt = $conn->prepare("
        SELECT 
            SUM(returns) AS total_returns,
            SUM(stake) AS total_stakes
        FROM bet_records
        WHERE user_id = :user_id
    ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $totalReturns = $result['total_returns'] ?? 0;
    $totalStakes = $result['total_stakes'] ?? 0;
    
    // Calculate net profit or loss
    $netProfitLoss = $totalReturns - $totalStakes;
    
    // Determine if it's profit or loss
    $isProfit = ($netProfitLoss >= 0);
    
    // Format the number with 2 decimal places
    $formattedProfitLoss = number_format(abs($netProfitLoss), 2);
    
} catch (PDOException $e) {
    $netProfitLoss = 0;
    $isProfit = true;
    $formattedProfitLoss = '0.00';
    // Optionally log the error
    // error_log("Database error in profit-loss-stats.php: " . $e->getMessage());
}
?>