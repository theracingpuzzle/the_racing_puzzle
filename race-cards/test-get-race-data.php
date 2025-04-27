<?php
header('Content-Type: application/json');

// Get race ID from query parameter
$raceId = isset($_GET['race_id']) ? $_GET['race_id'] : '';

if (empty($raceId)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing race ID parameter']);
    exit;
}

// Path to the JSON file
$jsonFilePath = '../racecards/assets/js/2025-04-26.json';

// Check if the file exists
if (!file_exists($jsonFilePath)) {
    http_response_code(404);
    echo json_encode(['error' => 'Race data file not found']);
    exit;
}

// Read and parse JSON
$jsonData = file_get_contents($jsonFilePath);
$allRaceData = json_decode($jsonData, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(500);
    echo json_encode(['error' => 'Error parsing JSON data']);
    exit;
}

// Split race_id into parts
$parts = explode('-', $raceId);
$timePart = array_pop($parts);
$courseNameInput = implode(' ', array_map('ucfirst', $parts));

// Fix time parsing
if (strlen($timePart) === 3) {
    // Example: 135 => 1:35
    $time = substr($timePart, 0, 1) . ':' . substr($timePart, 1, 2);
} elseif (strlen($timePart) === 4) {
    // Example: 1430 => 14:30
    $time = substr($timePart, 0, 2) . ':' . substr($timePart, 2, 2);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid time format']);
    exit;
}

// Search inside regions
$raceData = null;
foreach ($allRaceData as $region => $courses) {
    foreach ($courses as $courseName => $races) {
        // Normalize course names to match better
        $normalizedCourse = strtolower(preg_replace('/[^a-z0-9]/', '', $courseName));
        $normalizedInput = strtolower(preg_replace('/[^a-z0-9]/', '', $courseNameInput));

        if (strpos($normalizedCourse, $normalizedInput) !== false || strpos($normalizedInput, $normalizedCourse) !== false) {
            // Match time
            if (isset($races[$time])) {
                $raceData = $races[$time];
                $raceData['region'] = $region;
                $raceData['course'] = $courseName;
                break 2; // Found
            }
        }
    }
}

if ($raceData) {
    echo json_encode($raceData);
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Race not found',
        'debug' => [
            'parsed_course' => $courseNameInput,
            'parsed_time' => $time,
            'available_courses' => array_keys($allRaceData['GB'] ?? []),
            'file_path' => $jsonFilePath
        ]
    ]);
}
?>
