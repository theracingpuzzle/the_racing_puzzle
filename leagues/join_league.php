<?php
// join_league.php - Handle a user joining a league with a PIN

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        'message' => 'You must be logged in to join a league'
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

// Get and validate the PIN
$pin = isset($_POST['pin']) ? trim($_POST['pin']) : '';

if (empty($pin) || strlen($pin) !== 6 || !ctype_digit($pin)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid PIN. Please enter a valid 6-digit PIN.'
    ]);
    exit;
}

try {
    // Include database connection file for $db_path
    require_once "../includes/db-connection.php";
    
    // Create a new connection
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Begin transaction
    $db->beginTransaction();
    
    // Find the league with the given PIN
    $league_stmt = $db->prepare("
        SELECT id, name, creator_id 
        FROM leagues 
        WHERE pin = ?
    ");
    $league_stmt->execute([$pin]);
    $league = $league_stmt->fetch();
    
    if (!$league) {
        echo json_encode([
            'success' => false,
            'message' => 'League not found. Please check the PIN and try again.'
        ]);
        exit;
    }
    
    $league_id = $league['id'];
    $league_name = $league['name'];
    
    // Check if user is already a member of this league
    $check_stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM league_members 
        WHERE league_id = ? AND user_id = ?
    ");
    $check_stmt->execute([$league_id, $user_id]);
    
    if ($check_stmt->fetchColumn() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You are already a member of this league'
        ]);
        exit;
    }
    
    // Add user to the league
    $join_stmt = $db->prepare("
        INSERT INTO league_members (league_id, user_id) 
        VALUES (?, ?)
    ");
    $join_stmt->execute([$league_id, $user_id]);
    
    // Add an activity entry if you have an activity log table
    // This is optional based on your schema
    if ($db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='league_activity'")->fetchColumn()) {
        $activity_stmt = $db->prepare("
            INSERT INTO league_activity (league_id, user_id, activity_type, description) 
            VALUES (?, ?, 'join', ?)
        ");
        
        // Get username for the activity description
        $user_stmt = $db->prepare("SELECT username FROM users WHERE user_id = ?");
        $user_stmt->execute([$user_id]);
        $username = $user_stmt->fetchColumn();
        
        $activity_stmt->execute([
            $league_id, 
            $user_id, 
            "$username joined the league"
        ]);
    }
    
    // Commit transaction
    $db->commit();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => "Successfully joined the league: $league_name",
        'league_id' => $league_id,
        'league_name' => $league_name
    ]);
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    // Log error
    error_log('Error joining league: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Log error
    error_log('Error joining league: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>