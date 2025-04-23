<?php
// get-horses.php
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

// Get parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Get single horse by ID
if ($id) {
    try {
        $db = getDbConnection();
        // Add user_id to the query to ensure the user only accesses their own horses
        $stmt = $db->prepare("SELECT * FROM horse_tracker WHERE id = :id AND user_id = :user_id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        
        $horse = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($horse) {
            // Transform to match JS expectations
            $horse = [
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
            
            echo json_encode(['success' => true, 'horse' => $horse]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Horse not found']);
        }
        exit;
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Error getting horse']);
        exit;
    }
}

// Get horses - use the updated functions with user_id parameter
if (!empty($search)) {
    $horses = searchHorses($user_id, $search);
} else {
    $horses = getAllHorses($user_id, $sort);
}

// Format dates for display
foreach ($horses as &$horse) {
    if (!empty($horse['next_race_date'])) {
        // Convert to user-friendly format
        $date = new DateTime($horse['next_race_date']);
        $horse['formatted_race_date'] = $date->format('M d, Y');
    } else {
        $horse['formatted_race_date'] = 'Not scheduled';
    }
}

echo json_encode([
    'horses' => $horses,
    'count' => count($horses),
    'status' => 'success'
]);
?>