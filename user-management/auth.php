<?php
// auth.php - Authentication system for racing website

// Include your existing database connection
require_once '../includes/db-connection.php';

// Setup the users tables if they don't exist
function setupUserTables() {
    global $conn;
    
    // Create users table
    $conn->exec('CREATE TABLE IF NOT EXISTS users (
        user_id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        email TEXT UNIQUE NOT NULL,
        password_hash TEXT NOT NULL,
        password_reset_token TEXT,
        password_reset_expires DATETIME,
        registration_date DATETIME DEFAULT CURRENT_TIMESTAMP,
        is_active INTEGER DEFAULT 1
    )');
    
    // Create user preferences/racing profile table
    $conn->exec('CREATE TABLE IF NOT EXISTS user_profiles (
        profile_id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        display_name TEXT,
        favorite_racing_type TEXT,
        theme TEXT DEFAULT "light",
        newsletter_subscription INTEGER DEFAULT 0,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    )');
    
    return true;
}

// User registration
function registerUser($username, $email, $password) {
    global $conn;
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        return ["success" => false, "message" => "All fields are required"];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["success" => false, "message" => "Invalid email format"];
    }
    
    // Password strength validation
    if (strlen($password) < 8) {
        return ["success" => false, "message" => "Password must be at least 8 characters long"];
    }
    
    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetchColumn() > 0) {
            $conn->rollBack();
            return ["success" => false, "message" => "Username or email already exists"];
        }
        
        // Hash password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $password_hash]);
        
        $user_id = $conn->lastInsertId();
        
        // Create user profile record
        $stmt = $conn->prepare("INSERT INTO user_profiles (user_id, display_name) VALUES (?, ?)");
        $stmt->execute([$user_id, $username]);
        
        // Commit transaction
        $conn->commit();
        
        return ["success" => true, "message" => "Registration successful", "user_id" => $user_id];
    } catch (PDOException $e) {
        $conn->rollBack();
        return ["success" => false, "message" => "Registration failed: " . $e->getMessage()];
    }
}

// User login
function loginUser($username, $password) {
    global $conn;
    
    try {
        // Get user from database (check by username or email)
        $stmt = $conn->prepare("SELECT * FROM users WHERE (username = ? OR email = ?) AND is_active = 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Verify password
            if (password_verify($password, $user['password_hash'])) {
                // Start session if not already started
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                
                // Store user data in session
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                
                // Get user profile data
                $stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
                $stmt->execute([$user['user_id']]);
                $profile = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($profile) {
                    $_SESSION['display_name'] = $profile['display_name'];
                    $_SESSION['theme'] = $profile['theme'];
                }
                
                return ["success" => true, "message" => "Login successful", "user" => $user];
            } else {
                return ["success" => false, "message" => "Invalid password"];
            }
        } else {
            return ["success" => false, "message" => "User not found or account inactive"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Login failed: " . $e->getMessage()];
    }
}

// Check if user is logged in
function isLoggedIn() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if user is logged in and session hasn't expired
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        // Session timeout after 30 minutes of inactivity
        $timeout = 30 * 60; // 30 minutes in seconds
        
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
            // Session has expired
            session_unset();
            session_destroy();
            return false;
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    return false;
}

// Check if user is logged in and redirect to login if not
function requireLogin() {
    if (!isLoggedIn()) {
        // Store the requested URL to redirect back after login
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // Redirect to login page
        header('Location: ../user-management/login.php');
        exit;
    }
}

// User logout
function logoutUser() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    // Unset all session variables
    $_SESSION = [];
    
    // Destroy the session
    session_destroy();
    
    return ["success" => true, "message" => "Logout successful"];
}

// Password reset request
function requestPasswordReset($email) {
    global $conn;
    
    try {
        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND is_active = 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Generate token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token and expiry to database
            $stmt = $conn->prepare("UPDATE users SET password_reset_token = ?, password_reset_expires = ? WHERE user_id = ?");
            $stmt->execute([$token, $expires, $user['user_id']]);
            
            // In a real application, you would send an email with the reset link
            $reset_link = "https://yourracewebsite.com/reset-password.php?token=" . $token;
            
            return [
                "success" => true, 
                "message" => "Password reset email sent", 
                "link" => $reset_link,
                "note" => "In a production environment, an email would be sent to the user"
            ];
        } else {
            return ["success" => false, "message" => "Email not found"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Password reset request failed: " . $e->getMessage()];
    }
}

// Reset password with token
function resetPassword($token, $new_password) {
    global $conn;
    
    try {
        // Check if token exists and is valid
        $stmt = $conn->prepare("SELECT * FROM users WHERE password_reset_token = ? AND datetime(password_reset_expires) > datetime('now')");
        $stmt->execute([$token]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Password strength validation
            if (strlen($new_password) < 8) {
                return ["success" => false, "message" => "Password must be at least 8 characters long"];
            }
            
            $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            
            // Update password and remove token
            $stmt = $conn->prepare("UPDATE users SET password_hash = ?, password_reset_token = NULL, password_reset_expires = NULL WHERE user_id = ?");
            $stmt->execute([$password_hash, $user['user_id']]);
            
            return ["success" => true, "message" => "Password reset successful"];
        } else {
            return ["success" => false, "message" => "Invalid or expired token"];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Password reset failed: " . $e->getMessage()];
    }
}

// Update user profile
function updateUserProfile($user_id, $data) {
    global $conn;
    
    try {
        // Begin transaction
        $conn->beginTransaction();
        
        // Check if user exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->fetchColumn() == 0) {
            $conn->rollBack();
            return ["success" => false, "message" => "User not found"];
        }
        
        // Build update query based on provided data
        $fields = [];
        $values = [];
        
        if (isset($data['display_name'])) {
            $fields[] = "display_name = ?";
            $values[] = $data['display_name'];
        }
        
        if (isset($data['favorite_racing_type'])) {
            $fields[] = "favorite_racing_type = ?";
            $values[] = $data['favorite_racing_type'];
        }
        
        if (isset($data['theme'])) {
            $fields[] = "theme = ?";
            $values[] = $data['theme'];
        }
        
        if (isset($data['newsletter_subscription'])) {
            $fields[] = "newsletter_subscription = ?";
            $values[] = $data['newsletter_subscription'] ? 1 : 0;
        }
        
        if (empty($fields)) {
            $conn->rollBack();
            return ["success" => false, "message" => "No data provided for update"];
        }
        
        // Append user_id to values array
        $values[] = $user_id;
        
        // Update user profile
        $sql = "UPDATE user_profiles SET " . implode(", ", $fields) . " WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute($values);
        
        // Commit transaction
        $conn->commit();
        
        return ["success" => true, "message" => "Profile updated successfully"];
    } catch (PDOException $e) {
        $conn->rollBack();
        return ["success" => false, "message" => "Profile update failed: " . $e->getMessage()];
    }
}

// CSRF protection functions
function generateCSRFToken() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
?>