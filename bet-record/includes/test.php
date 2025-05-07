<?php
// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Path Test</h1>";

// Output current directory information
echo "<p>Current file: " . __FILE__ . "</p>";
echo "<p>Current directory: " . __DIR__ . "</p>";

// Check if we can access auth.php
$auth_path = '../../user-management/auth.php';
echo "<p>Trying to access: " . $auth_path . "</p>";
echo "<p>Full path: " . __DIR__ . '/' . $auth_path . "</p>";
echo "<p>File exists: " . (file_exists(__DIR__ . '/' . $auth_path) ? 'Yes' : 'No') . "</p>";

// Check if we can access db-connection.php directly
$db_path = '../../includes/db-connection.php';
echo "<p>Trying to access: " . $db_path . "</p>";
echo "<p>Full path: " . __DIR__ . '/' . $db_path . "</p>";
echo "<p>File exists: " . (file_exists(__DIR__ . '/' . $db_path) ? 'Yes' : 'No') . "</p>";

// Try to identify actual paths
echo "<h2>Directory Contents:</h2>";
echo "<pre>";
print_r(scandir(__DIR__ . '/../../'));
echo "</pre>";
?>