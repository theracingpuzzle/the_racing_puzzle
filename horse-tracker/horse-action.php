<?php
// horse-actions.php
header('Content-Type: application/json');

// Include database functions
require_once 'horse-tracker-functions.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not authenticated']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if action is set
if (!isset($_POST['action'])) {
    echo json_encode(['success' => false, 'message' => 'No action specified']);
    exit;
}

$action = $_POST['action'];

// Handle Add Horse
if ($action === 'add') {
    // Validate required fields
    if (empty($_POST['name'])) {
        echo json_encode(['success' => false, 'message' => 'Horse name is required']);
        exit;
    }
    
    try {
        $db = getDbConnection();
        
        // Check if horse_id was provided
        $includeHorseId = !empty($_POST['horse_id']);
        
        // Prepare SQL statement based on whether horse_id was provided
        if ($includeHorseId) {
            $stmt = $db->prepare("
                INSERT INTO horse_tracker 
                (horse_name, trainer, notes, date_added, user_id, horse_id, silk_url) 
                VALUES 
                (:name, :trainer, :notes, datetime('now'), :user_id, :horse_id, :silk_url)
            ");
            $stmt->bindParam(':horse_id', $_POST['horse_id']);
        } else {
            $stmt = $db->prepare("
                INSERT INTO horse_tracker 
                (horse_name, trainer, notes, date_added, user_id, silk_url) 
                VALUES 
                (:name, :trainer, :notes, datetime('now'), :user_id, :silk_url)
            ");
        }
        
        // Bind other parameters
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':trainer', $_POST['trainer']);
        $stmt->bindParam(':notes', $_POST['last_run_notes']);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':silk_url', $_POST['silk_url']);
        
        // Execute the statement
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Horse added successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}

// Handle Update Horse
else if ($action === 'update') {
    // Check for horse ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Horse ID is required for updates']);
        exit;
    }
    
    try {
        $db = getDbConnection();
        
        // Cast both values to integers to avoid type comparison issues
        $horse_id = (int)$_POST['id'];
        $current_user = (int)$user_id;
        
        // First check if the horse exists at all - temporarily skip user check
        $horse_check = $db->prepare("SELECT id, user_id FROM horse_tracker WHERE id = :id");
        $horse_check->bindValue(':id', $horse_id, PDO::PARAM_INT);
        $horse_check->execute();
        $horse_data = $horse_check->fetch(PDO::FETCH_ASSOC);
        
        if (!$horse_data) {
            echo json_encode(['success' => false, 'message' => 'Horse ID ' . $horse_id . ' not found in database']);
            exit;
        }
        
        // Check if user IDs match
        $db_user_id = (int)$horse_data['user_id'];
        if ($db_user_id !== $current_user) {
            echo json_encode(['success' => false, 'message' => 'Access denied - Horse belongs to user ' . $db_user_id . ' but you are user ' . $current_user]);
            exit;
        }
        
        // Proceed with update now that we've verified ownership
        $stmt = $db->prepare("
            UPDATE horse_tracker SET
            horse_name = :name,
            trainer = :trainer,
            notes = :notes
            WHERE id = :id
        ");
        
        // Bind parameters - simplified to avoid user_id issues
        $stmt->bindValue(':id', $horse_id, PDO::PARAM_INT);
        $stmt->bindValue(':name', $_POST['name']);
        $stmt->bindValue(':trainer', $_POST['trainer']);
        $stmt->bindValue(':notes', $_POST['last_run_notes']);
        
        
        // Execute the statement
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Horse updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}


?>