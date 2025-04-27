<?php

require_once __DIR__ . '/../includes/db-connection.php';
// Make sure session is started if it isn't already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    // Get the current user ID from session
    $user_id = $_SESSION['user_id']; // Define the user_id variable from session
    
    $stmt = $conn->prepare("
        SELECT jockey, COUNT(*) AS wins
        FROM bet_records
        WHERE outcome = 'Won' AND jockey IS NOT NULL AND user_id = :user_id
        GROUP BY jockey
        ORDER BY wins DESC
        LIMIT 3
    ");

    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $topJockeys = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>