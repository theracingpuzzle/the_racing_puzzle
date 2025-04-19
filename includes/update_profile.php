<?php
// update_profile.php - Process profile update form
require_once 'auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

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
    
    // Get user ID from session
    $user_id = $_SESSION['user_id'];
    
    // Get form data
    $data = [
        'display_name' => trim($_POST['display_name']),
        'favorite_racing_type' => $_POST['favorite_racing_type'],
        'theme' => $_POST['theme'],
        'newsletter_subscription' => isset($_POST['newsletter_subscription']) ? 1 : 0
    ];
    
    // Update user profile
    $result = updateUserProfile($user_id, $data);
    
    if ($result['success']) {
        // Update session data
        $_SESSION['display_name'] = $data['display_name'];
        $_SESSION['theme'] = $data['theme'];
        
        // Set success message
        $_SESSION['profile_message'] = 'Profile updated successfully';
    } else {
        // Set error message
        $_SESSION['profile_error'] = $result['message'];
    }
    
    // Redirect back to dashboard
    header('Location: dashboard2.php#profile');
    exit;
}
?>