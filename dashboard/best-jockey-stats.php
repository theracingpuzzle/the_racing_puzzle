<?php

require_once __DIR__ . '/../includes/db-connection.php';

try {
    $stmt = $conn->prepare("
        SELECT jockey, COUNT(*) AS wins
        FROM bet_records
        WHERE outcome = 'Won' AND jockey IS NOT NULL
        GROUP BY jockey
        ORDER BY wins DESC
        LIMIT 3
    ");
    $stmt->execute();
    $topJockeys = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
