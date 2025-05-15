<?php
require_once __DIR__ . '/../includes/db-connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Calculate overall ROI
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
    
    // Avoid division by zero
    if ($totalStakes > 0) {
        $overallROI = round((($totalReturns - $totalStakes) / $totalStakes) * 100);
    } else {
        $overallROI = 0;
    }
    
    // Get date ranges for SQLite
    $oneMonthAgo = date('Y-m-d', strtotime('-1 month'));
    $twoMonthsAgo = date('Y-m-d', strtotime('-2 months'));
    
    // Current month's ROI
    $stmt = $conn->prepare("
        SELECT 
            SUM(returns) AS month_returns,
            SUM(stake) AS month_stakes
        FROM bet_records 
        WHERE user_id = :user_id 
        AND date_added >= :one_month_ago
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':one_month_ago', $oneMonthAgo);
    $stmt->execute();
    $current = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $currentReturns = $current['month_returns'] ?? 0;
    $currentStakes = $current['month_stakes'] ?? 0;
    
    // Calculate current month ROI
    if ($currentStakes > 0) {
        $currentMonthROI = round((($currentReturns - $currentStakes) / $currentStakes) * 100);
    } else {
        $currentMonthROI = 0;
    }
    
    // Previous month's ROI
    $stmt = $conn->prepare("
        SELECT 
            SUM(returns) AS month_returns,
            SUM(stake) AS month_stakes
        FROM bet_records 
        WHERE user_id = :user_id 
        AND date_added >= :two_months_ago 
        AND date_added < :one_month_ago
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':two_months_ago', $twoMonthsAgo);
    $stmt->bindParam(':one_month_ago', $oneMonthAgo);
    $stmt->execute();
    $previous = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $previousReturns = $previous['month_returns'] ?? 0;
    $previousStakes = $previous['month_stakes'] ?? 0;
    
    // Calculate previous month ROI
    if ($previousStakes > 0) {
        $previousMonthROI = round((($previousReturns - $previousStakes) / $previousStakes) * 100);
    } else {
        $previousMonthROI = 0;
    }
    
    // Calculate ROI trend
    $roiTrend = round($currentMonthROI - $previousMonthROI);

} catch (PDOException $e) {
    $overallROI = 0;
    $roiTrend = 0;
    // Optionally log the error
    // error_log("Database error in roi-stats.php: " . $e->getMessage());
}
?>