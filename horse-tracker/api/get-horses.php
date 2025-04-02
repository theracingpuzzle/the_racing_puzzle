<?php
// Include database connection and functions
require_once '../includes/db-connection.php';
require_once '../includes/functions.php';

// Set content type to JSON
header('Content-Type: application/json');

// Check if requesting a specific horse
if (isset($_GET['id'])) {
    $horseId = $_GET['id'];
    $horse = getHorseById($horseId);
    
    if ($horse) {
        $response = [
            'success' => true,
            'horse' => $horse
        ];
        
        // Include activity if requested
        if (isset($_GET['includeActivity']) && $_GET['includeActivity'] === 'true') {
            $response['activities'] = getHorseActivity($horseId);
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Horse not found'
        ];
    }
    
    echo json_encode($response);
    exit;
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Get horses with filters
    $horses = getAllHorses();
    
    // Apply filters if needed
    if (!empty($_GET['search']) || !empty($_GET['trainer']) || !empty($_GET['sort'])) {
        $horses = filterHorses($horses, $_GET);
    }
    
    // Generate CSV content
    $csvContent = "Horse Name,Trainer,Age,Status,Last Updated\n";
    
    foreach ($horses as $horse) {
        $csvContent .= sprintf(
            "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
            $horse['HorseName'],
            $horse['Trainer'],
            $horse['Age'] ?? '',
            getStatusLabel($horse['Status']),
            date('Y-m-d', strtotime($horse['LastUpdated']))
        );
    }
    
    $response = [
        'success' => true,