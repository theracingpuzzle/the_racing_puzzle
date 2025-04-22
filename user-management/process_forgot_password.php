<?php
// process_forgot_password.php - Process forgot password form submission
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
    $email = trim($_POST['email']);
    
    // Request password reset
    $result = requestPasswordReset($email);
    
    if ($result['success']) {
        // Store reset link in session (for demo purposes)
        // In a real app, you would send an email
        $_SESSION['reset_link'] = $result['link'];
        $_SESSION['reset_success'] = 'Password reset instructions sent to your email';
        header('Location: forgot_password.php');
        exit;
    } else {
        // Store error message in session and redirect back to the form
        $_SESSION['forgot_error'] = $result['message'];
        header('Location: forgot_password.php');
        exit;
    }
}
?>
