<?php
// login.php - User login page
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: ../dashboard');
    exit;
}

// Display message if redirected after registration
$registered = isset($_GET['registered']) && $_GET['registered'] == 'true';
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : null;

// Clear session error after displaying
if (isset($_SESSION['login_error'])) {
    unset($_SESSION['login_error']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Racing Puzzle</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-20">
        <div class="card" style="max-width: 500px; margin: 50px auto;">
            <div class="card-header">
                <h3 class="text-center" style="margin-bottom: 0;">Login to Racing Puzzle</h3>
            </div>
            
            <div class="card-body">
                <?php if ($registered): ?>
                    <div class="mb-20" style="background-color: rgba(30, 86, 49, 0.1); color: var(--primary-color); padding: 12px; border-radius: var(--radius-sm); border-left: 4px solid var(--primary-color);">
                        Registration successful! You can now login with your credentials.
                    </div>
                <?php endif; ?>
                
                <?php if ($login_error): ?>
                    <div class="mb-20" style="background-color: rgba(158, 42, 43, 0.1); color: var(--feature-race-color); padding: 12px; border-radius: var(--radius-sm); border-left: 4px solid var(--feature-race-color);">
                        <?php echo htmlspecialchars($login_error); ?>
                    </div>
                <?php endif; ?>
                
                <form action="process_login.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-group mb-20">
                        <label for="username" style="font-weight: 600; display: block; margin-bottom: 5px;">Username or Email:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group mb-20">
                        <label for="password" style="font-weight: 600; display: block; margin-bottom: 5px;">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                </form>
            </div>
            
            <div class="card-footer text-center">
                <div class="d-flex justify-between">
                    <a href="forgot_password.php">Forgot Password?</a>
                    <a href="register.php">Create an Account</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>