<?php
/**
 * Get all horses from the database
 * 
 * @return array Array of horse data
 */
function getAllHorses() {
    global $conn;
    
    $query = "SELECT * FROM horses ORDER BY LastUpdated DESC";
    $result = mysqli_query($conn, $query);
    
    $horses = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $horses[] = $row;
    }
    
    return $horses;
}

/**
 * Get all unique trainers for filtering
 * 
 * @return array Array of trainer names
 */
function getAllTrainers() {
    global $conn;
    
    $query = "SELECT DISTINCT Trainer FROM horses ORDER BY Trainer ASC";
    $result = mysqli_query($conn, $query);
    
    $trainers = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $trainers[] = $row['Trainer'];
    }
    
    return $trainers;
}

/**
 * Get a single horse by ID
 * 
 * @param int $horseId The horse ID
 * @return array|null Horse data or null if not found
 */
function getHorseById($horseId) {
    global $conn;
    
    $horseId = mysqli_real_escape_string($conn, $horseId);
    $query = "SELECT * FROM horses WHERE Horse_ID = '$horseId'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

/**
 * Add a new horse to the database
 * 
 * @param array $horseData Horse data to insert
 * @return int|bool The new horse ID or false on failure
 */
function addHorse($horseData) {
    global $conn;
    
    // Sanitize inputs
    $horseName = mysqli_real_escape_string($conn, $horseData['horseName']);
    $trainerName = mysqli_real_escape_string($conn, $horseData['trainerName']);
    $horseAge = !empty($horseData['horseAge']) ? 
                mysqli_real_escape_string($conn, $horseData['horseAge']) : 'NULL';
    $horseStatus = mysqli_real_escape_string($conn, $horseData['horseStatus']);
    $horseNotes = mysqli_real_escape_string($conn, $horseData['horseNotes']);
    
    $query = "INSERT INTO horses (HorseName, Trainer, Age, Status, Notes, LastUpdated) 
              VALUES ('$horseName', '$trainerName', $horseAge, '$horseStatus', '$horseNotes', NOW())";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    
    return false;
}

/**
 * Update an existing horse
 * 
 * @param int $horseId The horse ID to update
 * @param array $horseData Updated horse data
 * @return bool Success or failure
 */
function updateHorse($horseId, $horseData) {
    global $conn;
    
    // Sanitize inputs
    $horseId = mysqli_real_escape_string($conn, $horseId);
    $horseName = mysqli_real_escape_string($conn, $horseData['horseName']);
    $trainerName = mysqli_real_escape_string($conn, $horseData['trainerName']);
    $horseAge = !empty($horseData['horseAge']) ? 
                mysqli_real_escape_string($conn, $horseData['horseAge']) : 'NULL';
    $horseStatus = mysqli_real_escape_string($conn, $horseData['horseStatus']);
    $horseNotes = mysqli_real_escape_string($conn, $horseData['horseNotes']);
    
    $query = "UPDATE horses 
              SET HorseName = '$horseName', 
                  Trainer = '$trainerName', 
                  Age = $horseAge, 
                  Status = '$horseStatus', 
                  Notes = '$horseNotes', 
                  LastUpdated = NOW()
              WHERE Horse_ID = '$horseId'";
    
    return mysqli_query($conn, $query);
}

/**
 * Delete a horse by ID
 * 
 * @param int $horseId The horse ID to delete
 * @return bool Success or failure
 */
function deleteHorse($horseId) {
    global $conn;
    
    $horseId = mysqli_real_escape_string($conn, $horseId);
    $query = "DELETE FROM horses WHERE Horse_ID = '$horseId'";
    
    return mysqli_query($conn, $query);
}

/**
 * Get activity history for a horse
 * 
 * @param int $horseId The horse ID
 * @return array Activity data
 */
function getHorseActivity($horseId) {
    global $conn;
    
    $horseId = mysqli_real_escape_string($conn, $horseId);
    $query = "SELECT * FROM horse_activity 
              WHERE Horse_ID = '$horseId' 
              ORDER BY ActivityDate DESC 
              LIMIT 10";
    
    $result = mysqli_query($conn, $query);
    
    $activities = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $activities[] = $row;
    }
    
    return $activities;
}