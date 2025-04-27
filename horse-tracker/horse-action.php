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
        
        // First check if the horse belongs to the user
        $checkStmt = $db->prepare("SELECT id FROM horse_tracker WHERE id = :id AND user_id = :user_id");
        $checkStmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Horse not found or access denied']);
            exit;
        }
        
        // Prepare SQL statement
        $stmt = $db->prepare("
            UPDATE horse_tracker SET
            horse_name = :name,
            trainer = :trainer,
            notes = :notes,
            silk_url = :silk_url
            WHERE id = :id AND user_id = :user_id
        ");
        
        // Bind parameters
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':name', $_POST['name']);
        $stmt->bindParam(':trainer', $_POST['trainer']);
        $stmt->bindParam(':notes', $_POST['last_run_notes']);
        $stmt->bindParam(':silk_url', $_POST['silk_url']);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
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
        
        // First check if the horse belongs to the user
        $checkStmt = $db->prepare("SELECT id FROM horse_tracker WHERE id = :id AND user_id = :user_id");
        $checkStmt->bindParam(':id', $_POST['id'], PDO::PARAM_INT);
        $checkStmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            echo json_encode(['success' => false, 'message' => 'Horse not found or access denied']);
            exit;
        }
        
        // Prepare SQL statement
        $stmt = $db->prepare("DELETE FROM horse_tracker WHERE id = :id AND user_id = :user_id");
        
        // Bind parameters
        $stmt->bindParam(':id', $_POST['id']);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        
        // Execute the statement
        $stmt->execute();
        
        echo json_encode(['success' => true, 'message' => 'Horse deleted successfully']);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
}
// Get horses
else if ($action === 'get') {
    $sort = isset($_POST['sort']) ? $_POST['sort'] : 'name';
    $horses = getAllHorses($user_id, $sort);
    echo json_encode(['success' => true, 'horses' => $horses]);
}
// Search horses
else if ($action === 'search') {
    if (!isset($_POST['search']) || empty($_POST['search'])) {
        echo json_encode(['success' => false, 'message' => 'Search term is required']);
        exit;
    }
    
    $horses = searchHorses($user_id, $_POST['search']);
    echo json_encode(['success' => true, 'horses' => $horses]);
}
// Unknown action
else {
    echo json_encode(['success' => false, 'message' => 'Unknown action: ' . $action]);
}
?>