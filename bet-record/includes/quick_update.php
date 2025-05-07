<?php
// bet-record/includes/quick_update.php

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
    'returns' => 0
];

// Process quick update request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quick_update'])) {
    try {
        // Verify required parameters
        if (!isset($_POST['bet_id']) || empty($_POST['bet_id']) || !isset($_POST['outcome']) || empty($_POST['outcome'])) {
            throw new Exception('Bet ID and outcome are required');
        }
        
        // Sanitize input data
        $bet_id = filter_var($_POST['bet_id'], FILTER_SANITIZE_NUMBER_INT);
        $outcome = htmlspecialchars($_POST['outcome']);
        
        // Validate outcome
        $valid_outcomes = ['Won', 'Lost', 'Pending', 'Void'];
        if (!in_array($outcome, $valid_outcomes)) {
            throw new Exception('Invalid outcome value');
        }
        
        // Get the current user ID from session
        $user_id = $_SESSION['user_id'];
        
        // Get current bet details to calculate returns if needed
        $get_sql = "SELECT stake, odds FROM bet_records WHERE id = :bet_id AND user_id = :user_id";
        $get_stmt = $conn->prepare($get_sql);
        $get_stmt->bindParam(':bet_id', $bet_id);
        $get_stmt->bindParam(':user_id', $user_id);
        $get_stmt->execute();
        
        if ($get_stmt->rowCount() == 0) {
            throw new Exception('Bet not found or you do not have permission to update it');
        }
        
        $bet_details = $get_stmt->fetch(PDO::FETCH_ASSOC);
        $stake = floatval($bet_details['stake']);
        $odds = $bet_details['odds'];
        
        // Calculate returns based on outcome
        $returns = 0;
        if ($outcome == 'Won') {
            if (strpos($odds, '/') !== false) {
                list($numerator, $denominator) = explode('/', $odds);
                $returns = $stake + ($stake * $numerator / $denominator);
            } else {
                $returns = $stake * floatval($odds);
            }
        }
        
        // Update bet outcome in database
        $update_sql = "UPDATE bet_records SET 
                      outcome = :outcome, 
                      returns = :returns 
                      WHERE id = :bet_id AND user_id = :user_id";
                
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':outcome', $outcome);
        $update_stmt->bindParam(':returns', $returns);
        $update_stmt->bindParam(':bet_id', $bet_id);
        $update_stmt->bindParam(':user_id', $user_id);
        
        // Execute and check success
        if ($update_stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Bet outcome updated successfully";
            $response['returns'] = $returns;
        } else {
            $response['message'] = "Error updating bet outcome: " . implode(", ", $update_stmt->errorInfo());
        }
    } catch(PDOException $e) {
        $response['message'] = "Database error: " . $e->getMessage();
    } catch(Exception $e) {
        $response['message'] = "Error: " . $e->getMessage();
    }
} else {
    $response['message'] = "Invalid request";
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>