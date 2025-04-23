<?php
// forgot_password.php - Forgot password page
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: ../dashboard');
    exit;
}

// Get messages from session
$forgot_error = isset($_SESSION['forgot_error']) ? $_SESSION['forgot_error'] : null;
$reset_success = isset($_SESSION['reset_success']) ? $_SESSION['reset_success'] : null;
$reset_link = isset($_SESSION['reset_link']) ? $_SESSION['reset_link'] : null;

// Clear session messages after displaying
if (isset($_SESSION['forgot_error'])) unset($_SESSION['forgot_error']);
if (isset($_SESSION['reset_success'])) unset($_SESSION['reset_success']);
// Don't unset reset_link until user navigates away or completes process
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Racing Puzzle</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container mt-20">
        <div class="card" style="max-width: 500px; margin: 50px auto;">
            <div class="card-header">
                <h3 class="text-center" style="margin-bottom: 0;">Forgot Password</h3>
            </div>
            
            <div class="card-body">
                <?php if ($reset_success): ?>
                    <div class="mb-20" style="background-color: rgba(30, 86, 49, 0.1); color: var(--primary-color); padding: 12px; border-radius: var(--radius-sm); border-left: 4px solid var(--primary-color);">
                        <?php echo htmlspecialchars($reset_success); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($forgot_error): ?>
                    <div class="mb-20" style="background-color: rgba(158, 42, 43, 0.1); color: var(--feature-race-color); padding: 12px; border-radius: var(--radius-sm); border-left: 4px solid var(--feature-race-color);">
                        <?php echo htmlspecialchars($forgot_error); ?>
                    </div>
                <?php endif; ?>
                
                <form action="process_forgot_password.php" method="post">
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                    
                    <div class="form-group mb-20">
                        <label for="email" style="font-weight: 600; display: block; margin-bottom: 5px;">Email Address:</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Request Password Reset</button>
                </form>
                
                <?php if ($reset_link): ?>
                    <div class="mt-20" style="padding: 15px; background-color: var(--medium-bg); border: 1px solid var(--border-color); border-radius: var(--radius-sm); word-break: break-all;">
                        <p><strong>Demo Reset Link:</strong></p>
                        <p><?php echo htmlspecialchars($reset_link); ?></p>
                        <p><small>Note: In a real application, this link would be sent to the user's email.</small></p>
                    </div>
                <?php endif; ?>  
            </div>
            
            <div class="card-footer text-center">
                <p><a href="login.php">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>