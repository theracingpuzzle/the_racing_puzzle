<?php

require_once __DIR__ . '/../includes/db-connection.php';

try {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM horse_tracker");
    $stmt->execute();
    $stats = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo "Total horses: " . $stats['total'];
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
