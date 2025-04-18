<?php
// get-horses.php
header('Content-Type: application/json');

// Include database functions
require_once 'horse-tracker-functions.php';

// Get parameters
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'name';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

// Get single horse by ID
if ($id) {
    try {
        $db = getDbConnection();
        $stmt = $db->prepare("SELECT * FROM horse_tracker WHERE id = :id");
        $stmt->execute([':id' => $id]);
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
                'date_added' => $horse['date_added'] ?? ''
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

// Get horses
if (!empty($search)) {
    $horses = searchHorses($search);
} else {
    $horses = getAllHorses($sort);
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