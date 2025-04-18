<?php

require_once __DIR__ . '/../includes/db-connection.php';

try {
    $stmt = $conn->prepare("
        SELECT racecourse, COUNT(*) AS wins
        FROM bet_records
        WHERE outcome = 'Won'
        GROUP BY racecourse
        ORDER BY wins DESC
        LIMIT 1
    ");
    $stmt->execute();
    $bestCourse = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
