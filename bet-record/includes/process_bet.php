<?php
// Add these at the top of process_bet.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start a debugging output
echo "<h1>Processing Bet</h1>";
echo "<pre>";

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
echo "Session started\n";

// Require authentication
require_once __DIR__ . '/../../user-management/auth.php';
requireLogin();
echo "User authenticated\n";

// Database connection
require_once __DIR__ . "/../../includes/db-connection.php";
echo "Database connected\n";

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

// Process form submission
echo "POST data: " . print_r($_POST, true) . "\n";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "Processing form submission\n";
    
    try {
        // Sanitize input data
        $bet_type = htmlspecialchars($_POST['bet_type']);
        $stake = floatval($_POST['stake']);
        $selection = htmlspecialchars($_POST['selection']);
        $odds = htmlspecialchars($_POST['odds']);
        $jockey = htmlspecialchars($_POST['jockey'] ?? '');
        $trainer = htmlspecialchars($_POST['trainer'] ?? '');
        $outcome = htmlspecialchars($_POST['outcome']);
        $racecourse = htmlspecialchars($_POST['racecourse']);
        
        echo "Sanitized input data\n";
        echo "Bet Type: $bet_type\n";
        echo "Stake: $stake\n";
        echo "Selection: $selection\n";
        echo "Odds: $odds\n";
        echo "Outcome: $outcome\n";
        echo "Racecourse: $racecourse\n";
        
        // Get the current user ID from session
        $user_id = $_SESSION['user_id'];
        echo "User ID: $user_id\n";
        
        // Calculate returns if bet won
$returns = 0;
if ($outcome == 'Won') {
    if (strpos($odds, '/') !== false) {
        list($numerator, $denominator) = explode('/', $odds);
        $returns = $stake + ($stake * $numerator / $denominator);
    } else {
        $returns = $stake * floatval($odds);
    }
}
echo "Calculated returns: $returns\n";

// Calculate profit (new code)
$profit = $returns - $stake;
echo "Calculated profit: $profit\n";

// Insert into database (updated SQL)
$sql = "INSERT INTO bet_records (bet_type, stake, selection, racecourse, odds, jockey, trainer, outcome, returns, user_id, profit) 
        VALUES (:bet_type, :stake, :selection, :racecourse, :odds, :jockey, :trainer, :outcome, :returns, :user_id, :profit)";
echo "SQL: $sql\n";
        
$stmt = $conn->prepare($sql);
echo "Statement prepared\n";

$stmt->bindParam(':bet_type', $bet_type);
$stmt->bindParam(':stake', $stake);
$stmt->bindParam(':selection', $selection);
$stmt->bindParam(':racecourse', $racecourse);
$stmt->bindParam(':odds', $odds);
$stmt->bindParam(':jockey', $jockey);
$stmt->bindParam(':trainer', $trainer);
$stmt->bindParam(':outcome', $outcome);
$stmt->bindParam(':returns', $returns);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':profit', $profit); // New parameter binding
        
        // Execute and check success
        echo "About to execute statement\n";
        if ($stmt->execute()) {
            echo "Statement executed successfully\n";
            
            // Get the ID of the last inserted record
            $lastId = $conn->lastInsertId();
            echo "Last insert ID: $lastId\n";
            
            $response['success'] = true;
            $response['message'] = "New bet record added successfully!";
            $response['redirect'] = "index.php?success=1#new-record";
            
            echo "Response: " . print_r($response, true) . "\n";
            
            // For regular form submissions
            $_SESSION['success_message'] = "New bet record added successfully!";
            echo "Set success message in session\n";
            
            echo "About to redirect to ../index.php?success=1#new-record\n";
            echo "</pre>";
            
            header("Location: ../index.php?success=1#new-record");
            exit();
        } else {
            echo "Statement execution failed\n";
            echo "Error info: " . print_r($stmt->errorInfo(), true) . "\n";
            
            $response['message'] = "Error adding record: " . implode(", ", $stmt->errorInfo());
        }
    } catch(PDOException $e) {
        echo "PDO Exception: " . $e->getMessage() . "\n";
        $response['message'] = "Database error: " . $e->getMessage();
    } catch(Exception $e) {
        echo "General Exception: " . $e->getMessage() . "\n";
        $response['message'] = "Error: " . $e->getMessage();
    }
    
    echo "If we got here, something went wrong\n";
    
    // For regular form submissions that had an error
    $_SESSION['error_message'] = $response['message'];
    echo "Set error message in session: " . $response['message'] . "\n";
    
    echo "About to redirect to ../index.php?error=1#new-record\n";
    echo "</pre>";
    
    header("Location: ../index.php?error=1#new-record");
    exit();
} else {
    echo "Not a valid form submission\n";
    echo "REQUEST_METHOD: " . $_SERVER["REQUEST_METHOD"] . "\n";
    echo "submitBtn present: " . (isset($_POST['submitBtn']) ? 'Yes' : 'No') . "\n";
    
    // For regular form submissions that had an error
    $_SESSION['error_message'] = "Invalid form submission";
    
    echo "About to redirect to ../index.php?error=2\n";
    echo "</pre>";
    
    header("Location: ../index.php?error=2");
    exit();
}

echo "End of script (should not reach here)\n";
echo "</pre>";
?>