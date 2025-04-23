<?php
// horse-tracker-functions.php

/**
 * Connect to the SQLite database
 */
function getDbConnection() {
    try {
        // Use the correct path to the database file
        $db_path = __DIR__ . '/../racingpuzzle.db';
        
        $db = new PDO('sqlite:' . $db_path);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        // Log error instead of displaying it
        error_log("Database connection error: " . $e->getMessage());
        return null;
    }
}

/**
 * Get all horses from the database for a specific user, sorted as specified
 */
function getAllHorses($user_id, $sort = 'name') {
    try {
        $db = getDbConnection();
        if (!$db) return [];
        
        // Map sort parameters to actual column names
        $sortMap = [
            'name' => 'horse_name',
            'date' => 'next_race_date',
            'added' => 'date_added'
        ];
        
        // Use the mapped column name if available, otherwise use horse_name
        $sortColumn = isset($sortMap[$sort]) ? $sortMap[$sort] : 'horse_name';
        
        // Add user_id filter to the query
        $query = "SELECT * FROM horse_tracker WHERE user_id = :user_id ORDER BY $sortColumn";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $horses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Transform column names to match JavaScript expectations
        return array_map(function($horse) {
            return [
                'id' => $horse['id'],
                'name' => $horse['horse_name'], // Map horse_name to name
                'trainer' => $horse['trainer'],
                'jockey' => $horse['jockey'] ?? '',
                'next_race_date' => $horse['next_race_date'] ?? '',
                'last_run_notes' => $horse['notes'] ?? '', // Assuming notes maps to last_run_notes
                'notify' => $horse['notify'] ?? '1',
                'date_added' => $horse['date_added'] ?? '',
                'user_id' => $horse['user_id']
            ];
        }, $horses);
    } catch (PDOException $e) {
        error_log("Error getting horses: " . $e->getMessage());
        return [];
    }
}

/**
 * Search for horses by name or trainer for a specific user
 */
function searchHorses($user_id, $search) {
    try {
        $db = getDbConnection();
        if (!$db) return [];
        
        // Add user_id filter to the query
        $query = "SELECT * FROM horse_tracker WHERE user_id = :user_id AND (horse_name LIKE :search OR trainer LIKE :search)";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':search', "%$search%");
        $stmt->execute();
        
        $horses = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Transform column names to match JavaScript expectations
        return array_map(function($horse) {
            return [
                'id' => $horse['id'],
                'name' => $horse['horse_name'], // Map horse_name to name
                'trainer' => $horse['trainer'],
                'jockey' => $horse['jockey'] ?? '',
                'next_race_date' => $horse['next_race_date'] ?? '',
                'last_run_notes' => $horse['notes'] ?? '', // Assuming notes maps to last_run_notes
                'notify' => $horse['notify'] ?? '1',
                'date_added' => $horse['date_added'] ?? '',
                'user_id' => $horse['user_id']
            ];
        }, $horses);
    } catch (PDOException $e) {
        error_log("Error searching horses: " . $e->getMessage());
        return [];
    }
}

/**
 * Get a single horse by ID, ensuring it belongs to the specified user
 */
function getHorseById($user_id, $horse_id) {
    try {
        $db = getDbConnection();
        if (!$db) return null;
        
        $query = "SELECT * FROM horse_tracker WHERE id = :id AND user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':id', $horse_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $horse = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$horse) {
            return null;
        }
        
        // Transform column names to match JavaScript expectations
        return [
            'id' => $horse['id'],
            'name' => $horse['horse_name'],
            'trainer' => $horse['trainer'],
            'jockey' => $horse['jockey'] ?? '',
            'next_race_date' => $horse['next_race_date'] ?? '',
            'last_run_notes' => $horse['notes'] ?? '',
            'notify' => $horse['notify'] ?? '1',
            'date_added' => $horse['date_added'] ?? '',
            'user_id' => $horse['user_id']
        ];
    } catch (PDOException $e) {
        error_log("Error getting horse by ID: " . $e->getMessage());
        return null;
    }
}
?>