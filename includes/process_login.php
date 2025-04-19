<?php
// process_login.php - Process login form submission
require_once 'auth.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate CSRF token
    if (!validateCSRFToken($_POST['csrf_token'])) {
        die('CSRF token validation failed');
    }
    
    // Get form data
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    // Login the user
    $result = loginUser($username, $password);
    
    if ($result['success']) {
        // Redirect to dashboard or home page
        header('Location: dashboard2.php');
        exit;
    } else {
        // Store error message in session and redirect back to the form
        $_SESSION['login_error'] = $result['message'];
        header('Location: login.php');
        exit;
    }
}
?>
