<?php
// register.php - User registration page
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: dashboard2.php');
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
        input[type="text"],
        input[type="email"],
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
            margin-top: 10px;
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
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #4CAF50;
            text-decoration: none;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        
        <?php if ($register_error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($register_error); ?>
            </div>
        <?php endif; ?>
        
        <form action="process_register.php" method="post" id="register-form">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <div class="password-requirements">
                    Password must be at least 8 characters long
                </div>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <div class="links">
            <p>Already have an account? <a href="login.php">Login</a></p>
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