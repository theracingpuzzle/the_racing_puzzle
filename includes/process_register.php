<?php
// process_register.php - Process registration form submission
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
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['register_error'] = 'Passwords do not match';
        header('Location: register.php');
        exit;
    }
    
    // Register the user
    $result = registerUser($username, $email, $password);
    
    if ($result['success']) {
        // Redirect to login page with success message
        header('Location: login.php?registered=true');
        exit;
    } else {
        // Store error message in session and redirect back to the form
        $_SESSION['register_error'] = $result['message'];
        header('Location: register.php');
        exit;
    }
}
?>