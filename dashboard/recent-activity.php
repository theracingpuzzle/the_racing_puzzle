<?php
require_once __DIR__ . '/../includes/db-connection.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    $user_id = $_SESSION['user_id'];
    
    // Get recent activity
    $stmt = $conn->prepare("
        SELECT 
            selection, 
            racecourse, 
            date_added, 
            outcome, 
            returns,
            stake,
            odds,
            jockey,
            trainer,
            id AS bet_id
        FROM 
            bet_records
        WHERE 
            user_id = :user_id
        ORDER BY 
            date_added DESC
        LIMIT 4
    ");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $recentActivity = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    $recentActivity = [];
    // Optionally log the error
    // error_log("Database error in recent-activity.php: " . $e->getMessage());
}
?>