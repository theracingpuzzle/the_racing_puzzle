<?php

require_once __DIR__ . '/../includes/db-connection.php';
// Make sure session is started if it isn't already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    // Get the current user ID from session
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    
    // Get total bets count for the user
    $stmt = $conn->prepare("
        SELECT COUNT(*) AS total_bets
        FROM bet_records
        WHERE user_id = :user_id
    ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $totalBets = $result['total_bets'];
    
    // Get previous month bets to calculate percentage change
    // $stmt = $conn->prepare("
    //     SELECT 
    //         (SELECT COUNT(*) FROM bet_records 
    //          WHERE user_id = :user_id 
    //          AND DATE(bet_date) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS current_month,
    //         (SELECT COUNT(*) FROM bet_records 
    //          WHERE user_id = :user_id 
    //          AND DATE(bet_date) >= DATE_SUB(CURDATE(), INTERVAL 2 MONTH)
    //          AND DATE(bet_date) < DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AS previous_month
    // ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $monthlyData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    $currentMonth = $monthlyData['current_month'];
    $previousMonth = $monthlyData['previous_month'];
    
    // Calculate percentage change
    if ($previousMonth > 0) {
        $percentChange = round((($currentMonth - $previousMonth) / $previousMonth) * 100);
        $isIncrease = $percentChange >= 0;
    } else {
        $percentChange = 0;
        $isIncrease = true;
    }

} catch (PDOException $e) {
    $totalBets = 0;
    $percentChange = 0;
    $isIncrease = true;
}
?>