<?php
// change_password.php - Process password change form
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
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_new_password = $_POST['confirm_new_password'];
    
    // Validate password match
    if ($new_password !== $confirm_new_password) {
        $_SESSION['password_error'] = 'New passwords do not match';
        header('Location: dashboard2.php#profile');
        exit;
    }
    
    // Password strength validation
    if (strlen($new_password) < 8) {
        $_SESSION['password_error'] = 'New password must be at least 8 characters long';
        header('Location: dashboard2.php#profile');
        exit;
    }
    
    // Verify current password and update to new password
    global $conn;
    
    try {
        // Get user's current password hash
        $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($current_password, $user['password_hash'])) {
            // Hash new password
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password in database
            $stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
            $stmt->execute([$new_password_hash, $user_id]);
            
            // Set success message
            $_SESSION['password_message'] = 'Password changed successfully';
        } else {
            // Set error message
            $_SESSION['password_error'] = 'Current password is incorrect';
        }
    } catch (PDOException $e) {
        $_SESSION['password_error'] = 'Password change failed: ' . $e->getMessage();
    }
    
    // Redirect back to dashboard
    header('Location: dashboard2.php#profile');
    exit;
}
?>