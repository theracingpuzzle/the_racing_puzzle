<?php
// login.php - User login page
require_once 'auth.php';

// Check if already logged in
if (isLoggedIn()) {
    header('Location: dashboard2.php');
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
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            margin: 0 10px;
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
        <h1>Login to Racing Puzzle</h1>
        
        <?php if ($registered): ?>
            <div class="alert alert-success">
                Registration successful! You can now login with your credentials.
            </div>
        <?php endif; ?>
        
        <?php if ($login_error): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($login_error); ?>
            </div>
        <?php endif; ?>
        
        <form action="process_login.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label for="username">Username or Email:</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="links">
            <a href="forgot_password.php">Forgot Password?</a>
            <a href="register.php">Create an Account</a>
        </div>
    </div>
</body>
</html>