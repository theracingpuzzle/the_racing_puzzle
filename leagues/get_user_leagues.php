<?php
// Enable detailed error reporting for debugging
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
        'message' => 'You must be logged in to view leagues'
    ]);
    exit;
}

// Get the current user's ID from session
$user_id = $_SESSION['user_id'];

try {
    // Include database connection file for $db_path
    require_once "../includes/db-connection.php";
    
    // Create a new connection
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Simplified query for testing
    $test_stmt = $db->prepare("SELECT count(*) FROM users WHERE user_id = ?");
    $test_stmt->execute([$user_id]);
    $user_exists = $test_stmt->fetchColumn();
    
    if (!$user_exists) {
        echo json_encode([
            'success' => false,
            'message' => "User with ID $user_id not found"
        ]);
        exit;
    }
    
    // Query to get leagues where the user is a member
    $leagues_stmt = $db->prepare("
        SELECT l.id, l.name, l.description, l.pin, l.ranking_type, l.created_at, l.creator_id,
               u.username as creator_name,
               CASE WHEN l.creator_id = ? THEN 1 ELSE 0 END as is_creator
        FROM leagues l
        JOIN league_members lm ON l.id = lm.league_id
        JOIN users u ON l.creator_id = u.user_id
        WHERE lm.user_id = ?
        ORDER BY lm.joined_at DESC
    ");
    
    $leagues_stmt->execute([$user_id, $user_id]);
    $user_leagues = $leagues_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Properly separate created leagues from joined leagues
    $created_leagues = [];
    $joined_leagues = [];
    
    foreach ($user_leagues as $league) {
        if ($league['creator_id'] == $user_id) {
            // This is a league the user created
            $created_leagues[] = $league;
        } else {
            // This is a league the user joined but didn't create
            $joined_leagues[] = $league;
        }
    }
    
    // Return the properly separated leagues
    echo json_encode([
        'success' => true,
        'created_leagues' => $created_leagues,
        'joined_leagues' => $joined_leagues
    ]);
    
} catch (Exception $e) {
    // Log error
    error_log('Error fetching leagues: ' . $e->getMessage());
    
    // Return detailed error response for debugging
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>