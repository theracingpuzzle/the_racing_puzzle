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
        $overallROI = (($totalReturns - $totalStakes) / $totalStakes) * 100;
    } else {
        $overallROI = 0;
    }
    
    // Get ROI trend
    $stmt = $conn->prepare("
        SELECT 
            (SELECT CASE WHEN SUM(stake) > 0 THEN (SUM(returns) - SUM(stake)) / SUM(stake) * 100 ELSE 0 END
             FROM bet_records 
             WHERE user_id = :user_id 
             AND DATE(date_added) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS current_month_roi,
            (SELECT CASE WHEN SUM(stake) > 0 THEN (SUM(returns) - SUM(stake)) / SUM(stake) * 100 ELSE 0 END
             FROM bet_records 
             WHERE user_id = :user_id 
             AND DATE(date_added) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
             AND DATE(date_added) < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS previous_month_roi
    ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $roiData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $currentMonthROI = $roiData['current_month_roi'] ?? 0;
    $previousMonthROI = $roiData['previous_month_roi'] ?? 0;
    
    $roiTrend = $currentMonthROI - $previousMonthROI;

} catch (PDOException $e) {
    $overallROI = 0;
    $roiTrend = 0;
    // Optionally log the error
    // error_log("Database error in roi-stats.php: " . $e->getMessage());
}
?>