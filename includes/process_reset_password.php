<?php
// process_reset_password.php - Process password reset form submission
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
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate password match
    if ($password !== $confirm_password) {
        $_SESSION['reset_error'] = 'Passwords do not match';
        header('Location: reset_password.php?token=' . $token);
        exit;
    }
    
    // Reset the password
    $result = resetPassword($token, $password);
    
    if ($result['success']) {
        // Redirect to login page with success message
        $_SESSION['login_message'] = 'Your password has been reset successfully. You can now login with your new password.';
        header('Location: login.php');
        exit;
    } else {
        // Store error message in session and redirect back to the form
        $_SESSION['reset_error'] = $result['message'];
        header('Location: reset_password.php?token=' . $token);
        exit;
    }
}
?>