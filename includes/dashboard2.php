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
    
    <!-- Add Bootstrap for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Link to Sidebar CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    
    <style>
        .stats-card .icon-box {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .bg-primary-light {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .bg-success-light {
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        .bg-info-light {
            background-color: rgba(13, 202, 240, 0.1);
        }
        
        .notification-dropdown {
            width: 300px;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <div id="mainContent" class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm">
            <div class="container-fluid">
                <span class="navbar-brand ms-3">The Racing Puzzle</span>
                <div class="ms-auto d-flex align-items-center">
                    <div class="dropdown me-3">
                        <button class="btn position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-bell"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                3
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end notification-dropdown p-0" aria-labelledby="notificationsDropdown">
                            <li class="p-2 border-bottom bg-light">
                                <h6 class="mb-0">Notifications</h6>
                            </li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-flag-checkered text-primary me-2"></i> New race scheduled</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-trophy text-success me-2"></i> You earned a new achievement</a></li>
                            <li><a class="dropdown-item py-2" href="#"><i class="fas fa-star text-warning me-2"></i> Points updated</a></li>
                            <li><hr class="dropdown-divider m-0"></li>
                            <li><a class="dropdown-item text-center py-2" href="#">View all notifications</a></li>
                        </ul>
                    </div>
                    <div class="dropdown d-inline-block">
                        <a class="dropdown-toggle text-decoration-none text-dark d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="https://via.placeholder.com/36" alt="User" class="rounded-circle me-2" width="36" height="36">
                            <span class="d-none d-md-inline"><?php echo htmlspecialchars($display_name); ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../index.html"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Dashboard Overview</h2>
                    <p class="text-muted">Welcome back, <?php echo htmlspecialchars($display_name); ?>! Here's your racing performance summary.</p>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Bets</h6>
                                    <h3 class="mb-0">254</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 12% from last month</small>
                                </div>
                                <div class="icon-box bg-primary-light">
                                    <i class="fas fa-flag-checkered fa-lg text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Win Rate</h6>
                                    <h3 class="mb-0">42%</h3>
                                    <small class="text-danger"><i class="fas fa-arrow-down me-1"></i> 3% from last month</small>
                                </div>
                                <div class="icon-box bg-success-light">
                                    <i class="fas fa-percentage fa-lg text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Top Racecourse</h6>
                                    <h4 class="mb-0">Cheltenham</h4>
                                    <small class="text-muted">12 wins</small>
                                </div>
                                <div class="icon-box bg-success-light">
                                    <i class="fas fa-trophy fa-lg text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3 mb-3">
                    <div class="card h-100 border-0 shadow-sm stats-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Tracker</h6>
                                    <h3 class="mb-0">15</h3>
                                    <small class="text-muted">Total tracked horses</small>
                                </div>
                                <div class="icon-box bg-info-light">
                                    <i class="fas fa-medal fa-lg text-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Content -->
            <div class="row">
                <!-- Recent Activity Table -->
                <div class="col-lg-8 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0">Recent Activity</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-sync-alt me-2"></i> Refresh</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Export</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Settings</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Race</th>
                                            <th>Date</th>
                                            <th>Position</th>
                                            <th>Prize Money</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/32" class="rounded me-2" width="32" height="32" alt="Race">
                                                    <div>
                                                        <h6 class="mb-0">Cheltenham Gold Cup</h6>
                                                        <small class="text-muted">Cheltenham</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Mar 25, 2025</td>
                                            <td><span class="badge bg-success">1st</span></td>
                                            <td>£250,000</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/32" class="rounded me-2" width="32" height="32" alt="Race">
                                                    <div>
                                                        <h6 class="mb-0">Grand National</h6>
                                                        <small class="text-muted">Aintree</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Mar 22, 2025</td>
                                            <td><span class="badge bg-secondary">4th</span></td>
                                            <td>£50,000</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/32" class="rounded me-2" width="32" height="32" alt="Race">
                                                    <div>
                                                        <h6 class="mb-0">Royal Ascot</h6>
                                                        <small class="text-muted">Ascot</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Mar 19, 2025</td>
                                            <td><span class="badge bg-warning text-dark">2nd</span></td>
                                            <td>£120,000</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="https://via.placeholder.com/32" class="rounded me-2" width="32" height="32" alt="Race">
                                                    <div>
                                                        <h6 class="mb-0">Epsom Derby</h6>
                                                        <small class="text-muted">Epsom</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>Mar 15, 2025</td>
                                            <td><span class="badge bg-danger">DNF</span></td>
                                            <td>£0</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center bg-white">
                            <a href="#" class="btn btn-link text-decoration-none">View All Activities</a>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events -->
                <div class="col-lg-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="card-title mb-0">Upcoming Events</h5>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton2">
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-plus me-2"></i> Add Event</a></li>
                                    <li><a class="dropdown-item" href="#"><i class="fas fa-calendar me-2"></i> View Calendar</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="event-item d-flex mb-3 pb-3 border-bottom">
                                <div class="event-date me-3 bg-light rounded p-2 text-center shadow-sm" style="min-width: 70px;">
                                    <span class="d-block small text-uppercase fw-bold text-primary">MAR</span>
                                    <span class="d-block fw-bold h4 mb-0">30</span>
                                </div>
                                <div>
                                    <h6 class="mb-1">Grand National</h6>
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-clock me-1"></i> 14:00 PM
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i> Aintree
                                    </p>
                                </div>
                            </div>
                            <div class="event-item d-flex mb-3 pb-3 border-bottom">
                                <div class="event-date me-3 bg-light rounded p-2 text-center shadow-sm" style="min-width: 70px;">
                                    <span class="d-block small text-uppercase fw-bold text-primary">APR</span>
                                    <span class="d-block fw-bold h4 mb-0">05</span>
                                </div>
                                <div>
                                    <h6 class="mb-1">2000 Guineas</h6>
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-clock me-1"></i> 14:30 PM
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i> Newmarket
                                    </p>
                                </div>
                            </div>
                            <div class="event-item d-flex">
                                <div class="event-date me-3 bg-light rounded p-2 text-center shadow-sm" style="min-width: 70px;">
                                    <span class="d-block small text-uppercase fw-bold text-primary">APR</span>
                                    <span class="d-block fw-bold h4 mb-0">12</span>
                                </div>
                                <div>
                                    <h6 class="mb-1">Derby</h6>
                                    <p class="mb-1 small text-muted">
                                        <i class="fas fa-clock me-1"></i> 15:15 PM
                                    </p>
                                    <p class="mb-0 small text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i> Epsom
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center bg-white">
                            <a href="#" class="btn btn-link text-decoration-none">View All Events</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- User Profile/Settings Section (Tabbed) -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">My Profile</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Change Password</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">Preferences</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form action="update_profile.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="display_name" class="form-label">Display Name</label>
                                                <input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo htmlspecialchars($profile['display_name'] ?? $username); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="favorite_racing_type" class="form-label">Favorite Racing Type</label>
                                                <select class="form-select" id="favorite_racing_type" name="favorite_racing_type">
                                                    <option value="">-- Select --</option>
                                                    <option value="Flat (Turf)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (Turf)') ? 'selected' : ''; ?>>Flat (Turf)</option>
                                                    <option value="Flat (AW)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (AW)') ? 'selected' : ''; ?>>Flat (AW)</option>
                                                    <option value="Hurdles" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Hurdles') ? 'selected' : ''; ?>>Hurdles</option>
                                                    <option value="Chase" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Chase') ? 'selected' : ''; ?>>Chase</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="theme" class="form-label">Theme</label>
                                                <select class="form-select" id="theme" name="theme">
                                                    <option value="light" <?php echo (isset($profile['theme']) && $profile['theme'] == 'light') ? 'selected' : ''; ?>>Light</option>
                                                    <option value="dark" <?php echo (isset($profile['theme']) && $profile['theme'] == 'dark') ? 'selected' : ''; ?>>Dark</option>
                                                    <option value="racing" <?php echo (isset($profile['theme']) && $profile['theme'] == 'racing') ? 'selected' : ''; ?>>Racing</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="newsletter_subscription" name="newsletter_subscription" value="1" <?php echo (isset($profile['newsletter_subscription']) && $profile['newsletter_subscription'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="newsletter_subscription">Subscribe to newsletter</label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </form>
                                </div>
                                
                                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form action="change_password.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </form>
                                </div>
                                
                                <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                                    <form action="update_preferences.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Notification Preferences</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_race" name="notify_race" value="1" checked>
                                                <label class="form-check-label" for="notify_race">New races</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_results" name="notify_results" value="1" checked>
                                                <label class="form-check-label" for="notify_results">Race results</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_news" name="notify_news" value="1">
                                                <label class="form-check-label" for="notify_news">Racing news</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "../includes/sidebar.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebar.js"></script>
</body>
</html>