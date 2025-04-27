<?php
// create_league.php - Handles league creation for SQLite database

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set content type to JSON for all responses
header('Content-Type: application/json');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to create a league'
    ]);
    exit;
}

// Get the current user's ID from session
$user_id = $_SESSION['user_id'];

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$description = isset($_POST['description']) ? trim($_POST['description']) : '';
$ranking_type = isset($_POST['ranking_type']) && in_array($_POST['ranking_type'], ['winners', 'returns']) 
    ? $_POST['ranking_type'] 
    : 'winners';

// Validate league name
if (empty($name)) {
    echo json_encode([
        'success' => false,
        'message' => 'League name is required'
    ]);
    exit;
}

try {
    require_once "../includes/db-connection.php"; // This will now use your SQLite connection
    
    // Create or open the database
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Generate a unique 6-digit PIN
    $pin = '';
    $unique = false;
    
    while (!$unique) {
        $pin = sprintf("%06d", mt_rand(0, 999999));
        
        // Check if PIN already exists
        $check = $db->prepare("SELECT COUNT(*) FROM leagues WHERE pin = ?");
        $check->execute([$pin]);
        
        if ($check->fetchColumn() == 0) {
            $unique = true;
        }
    }
    
    // Begin transaction
    $db->beginTransaction();
    
    // Insert new league
    $stmt = $db->prepare("
        INSERT INTO leagues (name, description, creator_id, ranking_type, pin)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([$name, $description, $user_id, $ranking_type, $pin]);
    
    // Get the ID of the newly created league
    $league_id = $db->lastInsertId();
    
    // Add the creator as the first member of the league
    $member_stmt = $db->prepare("
        INSERT INTO league_members (league_id, user_id)
        VALUES (?, ?)
    ");
    
    $member_stmt->execute([$league_id, $user_id]);
    
    // Commit the transaction
    $db->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'League created successfully',
        'league_id' => $league_id,
        'league_pin' => $pin
    ]);
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    // Log error
    error_log('Error creating league: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>