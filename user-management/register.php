<?php
// register.php - User registration page
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: ../dashboard');
    exit;
}

// Check if we need to setup user tables
if (!isset($_SESSION['tables_checked'])) {
    setupUserTables();
    $_SESSION['tables_checked'] = true;
}

// Check for registration errors
$register_error = isset($_SESSION['register_error']) ? $_SESSION['register_error'] : null;

// Clear session error after displaying
if (isset($_SESSION['register_error'])) {
    unset($_SESSION['register_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Racing Puzzle</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-20">
        <div class="card" style="max-width: 500px; margin: 50px auto;">
            <div class="card-header">
                <h3 class="text-center" style="margin-bottom: 0;">Create an Account</h3>
            </div>
            
            <div class="card-body">
                <?php if ($register_error): ?>
                    <div class="mb-20" style="background-color: rgba(158, 42, 43, 0.1); color: var(--feature-race-color); padding: 12px; border-radius: var(--radius-sm); border-left: 4px solid var(--feature-race-color);">
                        <?php echo htmlspecialchars($register_error); ?>
                    </div>
                <?php endif; ?>
                
                <form action="process_register.php" method="post" id="register-form">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-group mb-20">
                        <label for="username" style="font-weight: 600; display: block; margin-bottom: 5px;">Username:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group mb-20">
                        <label for="email" style="font-weight: 600; display: block; margin-bottom: 5px;">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group mb-20">
                        <label for="password" style="font-weight: 600; display: block; margin-bottom: 5px;">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <div style="font-size: 14px; color: var(--text-medium); margin-top: 5px;">
                            Password must be at least 8 characters long
                        </div>
                    </div>
                    
                    <div class="form-group mb-20">
                        <label for="confirm_password" style="font-weight: 600; display: block; margin-bottom: 5px;">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
                </form>
            </div>
            
            <div class="card-footer text-center">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Client-side form validation
        document.getElementById('register-form').addEventListener('submit', function(e) {
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