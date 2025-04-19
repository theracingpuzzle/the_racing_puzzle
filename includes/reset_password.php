<?php
// reset_password.php - Password reset page with token
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: dashboard2.php');
    exit;
}

// Check if token is provided
if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('Location: forgot_password.php');
    exit;
}

$token = $_GET['token'];

// Validate token format (basic check)
if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
    $_SESSION['forgot_error'] = 'Invalid reset token format';
    header('Location: forgot_password.php');
    exit;
}

// Check if token is valid
global $conn;
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE password_reset_token = ? AND datetime(password_reset_expires) > datetime('now')");
$stmt->execute([$token]);
$valid_token = ($stmt->fetchColumn() > 0);

if (!$valid_token) {
    $_SESSION['forgot_error'] = 'The password reset link is invalid or has expired';
    header('Location: forgot_password.php');
    exit;
}

// Get reset error
$reset_error = isset($_SESSION['reset_error']) ? $_SESSION['reset_error'] : null;

// Clear session error after displaying
if (isset($_SESSION['reset_error'])) {
    unset($_SESSION['reset_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Racing Puzzle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }
        button:hover {
            background-color: #45a049;
        }
        .alert {
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .password-requirements {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Reset Your Password</h1>
        
        <?php if ($reset_error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($reset_error); ?>
            </div>
        <?php endif; ?>
        
        <form action="process_reset_password.php" method="post" id="reset-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="password-requirements">
                    Password must be at least 8 characters long
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Reset Password</button>
        </form>
    </div>
    
    <script>
        // Client-side form validation
        document.getElementById('reset-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            // Password length check
            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long');
                return;
            }
            
            // Password match check
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match');
                return;
            }
        });
    </script>
</body>
</html>