<?php
// horse-actions.php
header('Content-Type: application/json');

// Include database functions
require_once 'horse-tracker-functions.php';

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
        
        // Prepare SQL statement
        $stmt = $db->prepare("
            INSERT INTO horse_tracker 
            (horse_name, trainer, notes, date_added) 
            VALUES 
            (:name, :trainer, :notes, datetime('now'))
        ");
        
        // Bind parameters
        $stmt->bindParam(':name', $_POST['name']);
        
        $stmt->bindParam(':trainer', $_POST['trainer']);
        $stmt->bindParam(':notes', $_POST['last_run_notes']);
        
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
        
        // Prepare SQL statement
        $stmt = $db->prepare("
            UPDATE horse_tracker SET
            horse_name = :name,
            trainer = :trainer,
            notes = :notes,
            WHERE id = :id
        ");
        
        // Bind parameters
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':name', $_POST['name']);
        
        $stmt->bindParam(':trainer', $_POST['trainer']);
        $stmt->bindParam(':notes', $_POST['last_run_notes']);
        
        
        // Execute the statement
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Horse updated successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
// Handle Delete Horse
else if ($action === 'delete') {
    // Check for horse ID
    if (!isset($_POST['id']) || empty($_POST['id'])) {
        echo json_encode(['success' => false, 'message' => 'Horse ID is required for deletion']);
        exit;
    }
    
    try {
        $db = getDbConnection();
        
        // Prepare SQL statement
        $stmt = $db->prepare("DELETE FROM horse_tracker WHERE id = :id");
        
        // Bind parameter
        $stmt->bindParam(':id', $_POST['id']);
        
        // Execute the statement
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Horse deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
// Unknown action
else {
    echo json_encode(['success' => false, 'message' => 'Unknown action: ' . $action]);
}
?>