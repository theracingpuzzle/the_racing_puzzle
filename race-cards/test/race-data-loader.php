<?php
// includes/race-data-loader.php
// Loads race data for a specific date

/**
 * Normalizes region names to standardized values
 * @param string $region The region name to normalize
 * @return string The normalized region name
 */
function normalizeRegion($region) {
    if (empty($region)) {
        return '';
    }
    
    $region = trim(strtoupper($region));
    
    // Map common variants
    $regionMap = [
        // UK variants
        'UNITED KINGDOM' => 'UK',
        'GREAT BRITAIN' => 'UK',
        'ENGLAND' => 'UK',
        'SCOTLAND' => 'UK',
        'WALES' => 'UK',
        'NORTHERN IRELAND' => 'UK',
        'GB' => 'UK',
        'GBR' => 'UK',
        
        // Ireland variants
        'IRE' => 'IRELAND',
        'EIRE' => 'IRELAND',
        'IRL' => 'IRELAND',
        
        // France variants
        'FRA' => 'FRANCE'
    ];
    
    return isset($regionMap[$region]) ? $regionMap[$region] : $region;
}

// Format the requested date for file naming
$formattedDate = date('Y-m-d', strtotime($requestedDate));

// Full path to the JSON file for the requested date
$jsonFilePath = "../racecards/assets/js/{$formattedDate}.json";

// Initialize allCourses array
$allCourses = [];

// Check if file exists
if (file_exists($jsonFilePath)) {
    // Read the entire JSON file into a string
    $jsonContent = file_get_contents($jsonFilePath);

    // Decode the JSON string into a PHP associative array
    $raceData = json_decode($jsonContent, true);

    // Handle if JSON is invalid
    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log("Error parsing JSON for date {$formattedDate}: " . json_last_error_msg());
        $raceData = [];
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
    
    // Reorder all courses alphabetically across regions
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
} else {
    // Log that the file was not found
    error_log("Race file not found for date: {$formattedDate}");
    
    // Set empty race data
    $raceData = [];
}

// Make data available for the page
$hasRaceData = !empty($allCourses);