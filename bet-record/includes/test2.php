<?php
// Enable error display
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Include Test</h1>";

// Try to include just the database connection
try {
    echo "<p>Attempting to include db-connection.php...</p>";
    require_once '../../includes/db-connection.php';
    echo "<p>Database connection included successfully!</p>";
    
    // If we made it here, try to verify the connection
    echo "<p>Testing connection variable...</p>";
    if (isset($conn)) {
        echo "<p>Connection variable exists!</p>";
        
        // Simple test query
        try {
            $test = $conn->query("SELECT 1");
            echo "<p>Test query executed successfully!</p>";
        } catch (Exception $e) {
            echo "<p>Error with test query: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p>Connection variable doesn't exist!</p>";
    }
} catch (Exception $e) {
    echo "<p>Error including db-connection.php: " . $e->getMessage() . "</p>";
}

echo "<hr>";

// Now try auth.php separately
try {
    echo "<p>Attempting to include auth.php...</p>";
    require_once '../../user-management/auth.php';
    echo "<p>Auth file included successfully!</p>";
} catch (Exception $e) {
    echo "<p>Error including auth.php: " . $e->getMessage() . "</p>";
}
?>