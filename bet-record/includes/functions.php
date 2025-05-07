<?php
// bet-record/includes/functions.php

/**
 * Fetch racecourses from the database
 * 
 * @param PDO $conn Database connection
 * @return array List of racecourses
 */
function fetchRacecourses($conn) {
    try {
        $sql = "SELECT * FROM racecourses ORDER BY name ASC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Simple fallback to just get distinct values from existing records
        $sql = "SELECT DISTINCT racecourse FROM bet_records ORDER BY racecourse ASC";
        $stmt = $conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

/**
 * Fetch user's bet records
 * 
 * @param PDO $conn Database connection
 * @param int $user_id User ID
 * @return array User's bet records
 */
function fetchUserBetRecords($conn, $user_id) {
    try {
        // SQLite uses different date functions than MySQL
        $sql = "SELECT *, datetime(date_added) as formatted_date FROM bet_records 
                WHERE user_id = :user_id 
                ORDER BY date_added DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        error_log("Error fetching records: " . $e->getMessage());
        return [];
    }
}

/**
 * Calculate betting statistics
 * 
 * @param array $records Betting records
 * @return array Statistics
 */
function calculateStats($records) {
    $stats = [
        'total' => 0, 
        'won' => 0, 
        'lost' => 0, 
        'pending' => 0, 
        'total_stake' => 0, 
        'total_returns' => 0
    ];
    
    if (!$records) {
        return $stats;
    }
    
    $stats['total'] = count($records);
    
    foreach ($records as $row) {
        if ($row['outcome'] == 'Won') {
            $stats['won']++;
            $stats['total_returns'] += floatval($row['returns']);
        } elseif ($row['outcome'] == 'Lost') {
            $stats['lost']++;
        } elseif ($row['outcome'] == 'Pending') {
            $stats['pending']++;
        }
        $stats['total_stake'] += floatval($row['stake']);
    }
    
    return $stats;
}
?>