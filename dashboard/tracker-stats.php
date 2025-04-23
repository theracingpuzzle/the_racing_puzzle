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
        // Modified query to only count horses belonging to the current user
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM horse_tracker WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // echo "Total horses: " . $stats['total'];
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
} else {
    // User not logged in, set total to 0
    $stats = ['total' => 0];
    // Alternatively, you could redirect to a login page or show an error message
}
?>