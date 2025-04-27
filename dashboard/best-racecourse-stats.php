<?php

require_once __DIR__ . '/../includes/db-connection.php';
// Make sure session is started if it isn't already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

try {
    // Get the current user ID from session
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    
    $stmt = $conn->prepare("
        SELECT racecourse, COUNT(*) AS wins
        FROM bet_records
        WHERE outcome = 'Won' AND user_id = :user_id
        GROUP BY racecourse
        ORDER BY wins DESC
        LIMIT 1
    ");
    
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $bestCourse = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}