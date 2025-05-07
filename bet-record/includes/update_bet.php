<?php
// bet-record/includes/update_bet.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require authentication
require_once __DIR__ . '/../../user-management/auth.php';
requireLogin();

// Database connection
require_once __DIR__ . "/../../includes/db-connection.php";

// Initialize response
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Verify that bet_id is provided
        if (!isset($_POST['bet_id']) || empty($_POST['bet_id'])) {
            throw new Exception('Bet ID is required');
        }
        
        // Sanitize input data
        $bet_id = filter_var($_POST['bet_id'], FILTER_SANITIZE_NUMBER_INT);
        $bet_type = htmlspecialchars($_POST['bet_type']);
        $stake = floatval($_POST['stake']);
        $selection = htmlspecialchars($_POST['selection']);
        $odds = htmlspecialchars($_POST['odds']);
        $jockey = isset($_POST['jockey']) ? htmlspecialchars($_POST['jockey']) : '';
        $trainer = isset($_POST['trainer']) ? htmlspecialchars($_POST['trainer']) : '';
        $outcome = htmlspecialchars($_POST['outcome']);
        $racecourse = htmlspecialchars($_POST['racecourse']);
        
        // Get the current user ID from session
        $user_id = $_SESSION['user_id'];
        
        // First verify that the bet belongs to the current user
        $check_sql = "SELECT id FROM bet_records WHERE id = :bet_id AND user_id = :user_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':bet_id', $bet_id);
        $check_stmt->bindParam(':user_id', $user_id);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() == 0) {
            throw new Exception('Bet not found or you do not have permission to edit it');
        }
        
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
        
        // Update bet record in database
        $sql = "UPDATE bet_records SET 
                bet_type = :bet_type, 
                stake = :stake, 
                selection = :selection, 
                racecourse = :racecourse, 
                odds = :odds, 
                jockey = :jockey, 
                trainer = :trainer, 
                outcome = :outcome, 
                returns = :returns 
                WHERE id = :bet_id AND user_id = :user_id";
                
        $stmt = $conn->prepare($sql);
        
        $stmt->bindParam(':bet_type', $bet_type);
        $stmt->bindParam(':stake', $stake);
        $stmt->bindParam(':selection', $selection);
        $stmt->bindParam(':racecourse', $racecourse);
        $stmt->bindParam(':odds', $odds);
        $stmt->bindParam(':jockey', $jockey);
        $stmt->bindParam(':trainer', $trainer);
        $stmt->bindParam(':outcome', $outcome);
        $stmt->bindParam(':returns', $returns);
        $stmt->bindParam(':bet_id', $bet_id);
        $stmt->bindParam(':user_id', $user_id);
        
        // Execute and check success
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Bet record updated successfully!";
            $response['redirect'] = "../index.php?success=2#updated-record-" . $bet_id;
            
            // For regular form submissions
            $_SESSION['success_message'] = "Bet record updated successfully!";
            
            header("Location: ../index.php?success=2#updated-record-" . $bet_id);
            exit();
        } else {
            $response['message'] = "Error updating record: " . implode(", ", $stmt->errorInfo());
        }
    } catch(PDOException $e) {
        $response['message'] = "Database error: " . $e->getMessage();
    } catch(Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
    
    // For regular form submissions that had an error
    $_SESSION['error_message'] = $response['message'];
    
    header("Location: ../index.php?error=1#edit-failed");
    exit();
} else {
    // For regular form submissions that had an error
    $_SESSION['error_message'] = "Invalid form submission";
    
    header("Location: ../index.php?error=2");
    exit();
}
?>