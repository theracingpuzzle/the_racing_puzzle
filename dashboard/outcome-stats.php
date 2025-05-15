<?php
require_once __DIR__ . '/../includes/db-connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Query to get all outcome types and counts
    $stmt = $conn->prepare("
        SELECT 
            outcome,
            COUNT(*) as count
        FROM bet_records
        WHERE user_id = :user_id
        GROUP BY outcome
    ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $outcomes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Initialize counters for each outcome type
    $wonCount = 0;
    $placedCount = 0;
    $lostCount = 0;
    $voidCount = 0;
    $totalCount = 0;
    
    // Process the results
    foreach ($outcomes as $outcome) {
        $outcomeType = $outcome['outcome'];
        $count = $outcome['count'];
        
        $totalCount += $count;
        
        // Map database outcomes to our categories
        if ($outcomeType === 'Won') {
            $wonCount = $count;
        } elseif ($outcomeType === 'Placed' || $outcomeType === 'Place') {
            $placedCount = $count;
        } elseif ($outcomeType === 'Lost' || $outcomeType === 'Loss') {
            $lostCount = $count;
        } elseif ($outcomeType === 'Void' || $outcomeType === 'Voided') {
            $voidCount = $count;
        }
        // You can add more conditions if you have other outcome types
    }
    
} catch (PDOException $e) {
    // Handle errors
    $wonCount = 0;
    $placedCount = 0;
    $lostCount = 0;
    $voidCount = 0;
    $totalCount = 0;
    // error_log("Database error in outcome-stats.php: " . $e->getMessage());
}
?>