<?php
// dashboard.php - User dashboard after login
require_once 'auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

// Get user data from session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$display_name = isset($_SESSION['display_name']) ? $_SESSION['display_name'] : $username;

// Get additional user data from database if needed
global $conn;
$stmt = $conn->prepare("SELECT * FROM user_profiles WHERE user_id = ?");
$stmt->execute([$user_id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Racing Puzzle</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        header {
            background-color: #333;
            color: white;
            padding: 10px 0;
        }
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .user-nav {
            display: flex;
            align-items: center;
        }
        .user-nav .welcome {
            margin-right: 20px;
        }
        .btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #45a049;
        }
        .btn-logout {
            background-color: #f44336;
        }
        .btn-logout:hover {
            background-color: #d32f2f;
        }
        .dashboard-content {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .stat-card {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .stat-card h3 {
            margin-top: 0;
            color: #4CAF50;
        }
        .profile-section {
            margin-top: 30px;
        }
        .profile-section h2 {
            border-bottom: 1px solid #ddd;
            padding-bottom: 10px;
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
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }
        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }
        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #f1f1f1;
            margin-right: 5px;
            border-radius: 5px 5px 0 0;
        }
        .tab.active {
            background-color: #4CAF50;
            color: white;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">Racing Puzzle</div>
                <div class="user-nav">
                    <div class="welcome">Welcome, <?php echo htmlspecialchars($display_name); ?>!</div>
                    <a href="logout.php" class="btn btn-logout">Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="dashboard-content">
            <h1>Dashboard</h1>
            
            <div class="tabs">
                <div class="tab active" data-tab="overview">Overview</div>
                <div class="tab" data-tab="profile">My Profile</div>
                <div class="tab" data-tab="races">My Races</div>
            </div>
            
            <div id="overview" class="tab-content active">
                <h2>Racing Statistics</h2>
                <div class="stats-container">
                    <div class="stat-card">
                        <h3>Profile Summary</h3>
                        <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                        <p><strong>Member Since:</strong> <?php echo date('F j, Y'); ?></p>
                        <p><strong>Racing Type:</strong> <?php echo htmlspecialchars($profile['favorite_racing_type'] ?? 'Not set'); ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Recent Activity</h3>
                        <p>No recent activity to display.</p>
                        <p>Start racing to see your activity here!</p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Quick Links</h3>
                        <p><a href="#" class="btn">Start a New Race</a></p>
                        <p><a href="#" class="btn">Join Existing Race</a></p>
                        <p><a href="#" class="btn">View Leaderboards</a></p>
                    </div>
                </div>
            </div>
            
            <div id="profile" class="tab-content">
                <div class="profile-section">
                    <h2>Edit Profile</h2>
                    <form action="update_profile.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="display_name">Display Name:</label>
                            <input type="text" id="display_name" name="display_name" value="<?php echo htmlspecialchars($profile['display_name'] ?? $username); ?>">
                        </div>
                        
                        <div class="form-group">
    <label for="favorite_racing_type">Favorite Racing Type:</label>
    <select id="favorite_racing_type" name="favorite_racing_type">
        <option value="">-- Select --</option>
        <option value="Flat (Turf)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (Turf)') ? 'selected' : ''; ?>>Flat (Turf)</option>
        <option value="Flat (AW)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (AW)') ? 'selected' : ''; ?>>Flat (AW)</option>
        <option value="Hurdles" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Hurdles') ? 'selected' : ''; ?>>Hurdles</option>
        <option value="Chase" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Chase') ? 'selected' : ''; ?>>Chase</option>
    </select>
</div>

                        <div class="form-group">
                            <label for="theme">Theme:</label>
                            <select id="theme" name="theme">
                                <option value="light" <?php echo (isset($profile['theme']) && $profile['theme'] == 'light') ? 'selected' : ''; ?>>Light</option>
                                <option value="dark" <?php echo (isset($profile['theme']) && $profile['theme'] == 'dark') ? 'selected' : ''; ?>>Dark</option>
                                <option value="racing" <?php echo (isset($profile['theme']) && $profile['theme'] == 'racing') ? 'selected' : ''; ?>>Racing</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="newsletter_subscription" value="1" <?php echo (isset($profile['newsletter_subscription']) && $profile['newsletter_subscription'] == 1) ? 'checked' : ''; ?>>
                                Subscribe to newsletter
                            </label>
                        </div>
                        
                        <button type="submit" class="btn">Update Profile</button>
                    </form>
                </div>
                
                <div class="profile-section">
                    <h2>Change Password</h2>
                    <form action="change_password.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="form-group">
                            <label for="current_password">Current Password:</label>
                            <input type="password" id="current_password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="new_password">New Password:</label>
                            <input type="password" id="new_password" name="new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_new_password">Confirm New Password:</label>
                            <input type="password" id="confirm_new_password" name="confirm_new_password" required>
                        </div>
                        
                        <button type="submit" class="btn">Change Password</button>
                    </form>
                </div>
            </div>
            
            <div id="races" class="tab-content">
                <h2>My Racing History</h2>
                <p>You haven't participated in any races yet.</p>
                <p><a href="#" class="btn">Find a Race</a></p>
            </div>
        </div>
    </div>
    
    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and content
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });
    </script>
</body>
</html>