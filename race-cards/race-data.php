<?php
// Full path to your JSON file
$jsonFilePath = '../racecards/assets/js/2025-04-26.json';

// Check if file exists
if (file_exists($jsonFilePath)) {
    // Read the entire JSON file into a string
    $jsonContent = file_get_contents($jsonFilePath);

    // Decode the JSON string into a PHP associative array
    $raceData = json_decode($jsonContent, true);

    // Optional: handle if JSON is invalid
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "Error parsing JSON: " . json_last_error_msg();
        $raceData = null;
    }
    
    // Sort race data - create a sorted structure
    $sortedRaceData = [];
    
    foreach ($raceData as $region => $courses) {
        // Sort courses alphabetically by course name
        ksort($courses);
        
        foreach ($courses as $courseName => $races) {
            // Sort races by time
            ksort($races);
            $sortedRaceData[$region][$courseName] = $races;
        }
    }
    
    // Replace unsorted data with sorted data
    $raceData = $sortedRaceData;
} else {
    echo "Race file not found.";
    $raceData = null;
}

// We need to reorder all courses alphabetically across regions
$allCourses = [];

foreach ($raceData as $region => $courses) {
    foreach ($courses as $courseName => $races) {
        $allCourses[$courseName] = [
            'region' => $region,
            'races' => $races
        ];
    }
}

// Sort courses alphabetically by name
ksort($allCourses);
?>