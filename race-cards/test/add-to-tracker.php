<?php
// actions/add-to-tracker.php
// Handles adding horses to the user's tracker

// Require authentication
require_once '../../user-management/auth.php';
requireLogin();

// Initialize response array
$response = [
    'success' => false,
    'message' => ''
];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    $required_fields = ['horse_name', 'trainer'];
    $missing_fields = [];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing_fields[] = $field;
        }
    }
    
    if (!empty($missing_fields)) {
        $response['message'] = 'Missing required fields: ' . implode(', ', $missing_fields);
        echo json_encode($response);
        exit;
    }
    
    // Get form data
    $horse_name = trim($_POST['horse_name']);
    $trainer = trim($_POST['trainer']);
    $jockey = isset($_POST['jockey']) ? trim($_POST['jockey']) : '';
    $comments = isset($_POST['comments']) ? trim($_POST['comments']) : '';
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Database connection
    require_once '../../includes/db-connect.php';
    
    try {
        // Check if horse already exists in tracker
        $stmt = $pdo->prepare("SELECT id FROM horse_tracker WHERE user_id = ? AND name = ? AND trainer = ?");
        $stmt->execute([$user_id, $horse_name, $trainer]);
        $existing_horse = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($existing_horse) {
            // Update existing horse
            $stmt = $pdo->prepare("
                UPDATE horse_tracker 
                SET jockey = ?, last_run_notes = ?, date_updated = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$jockey, $comments, $existing_horse['id']]);
            
            $response['success'] = true;
            $response['message'] = 'Horse updated in tracker';
            $response['id'] = $existing_horse['id'];
        } else {
            // Add new horse
            $stmt = $pdo->prepare("
                INSERT INTO horse_tracker (user_id, name, trainer, jockey, last_run_notes, date_added) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            $stmt->execute([$user_id, $horse_name, $trainer, $jockey, $comments]);
            
            $response['success'] = true;
            $response['message'] = 'Horse added to tracker';
            $response['id'] = $pdo->lastInsertId();
        }
    } catch (PDOException $e) {
        $response['message'] = 'Database error: ' . $e->getMessage();
        
        // Log error
        error_log('Horse tracker error: ' . $e->getMessage());
    }
} else {
    $response['message'] = 'Invalid request method';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
exit;