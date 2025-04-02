<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle Dashboard</title>
    
    <!-- Add Bootstrap for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Link to Sidebar CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    
</head>
<body>



    <!-- Main Content -->
    <div id="mainContent" class="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white mb-4 shadow-sm">
            <div class="container-fluid">
                <button id="toggleSidebar" class="btn">
                    <i class="fas fa-bars"></i>
                </button>
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
                            <span class="d-none d-md-inline">Dan Hill</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user me-2"></i> Profile</a></li>
                            <li><a class="dropdown-item" href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="container-fluid px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-1">Dashboard Overview</h2>
                    <p class="text-muted">Welcome back, Dan! Here's your racing performance summary.</p>
                </div>
                <button class="btn btn-primary d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i> New Entry
                </button>
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
                                    <h3 class="mb-0">32%</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 5% from last month</small>
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
                                    <h6 class="text-muted mb-2">Earnings</h6>
                                    <h3 class="mb-0">1,287</h3>
                                    <small class="text-success"><i class="fas fa-arrow-up me-1"></i> 87 new points</small>
                                </div>
                                <div class="icon-box bg-warning-light">
                                    <i class="fas fa-star fa-lg text-warning"></i>
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
                                    <small class="text-success"><i class="fas fa-unlock me-1"></i> 2 new unlocked</small>
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
        </div>
    </div>
</div>

<?php include_once "../includes/sidebar.php"; ?>

<?php include_once "../includes/footer.php"; ?>

<!-- Link to sidebar JavaScript -->
<script src="../assets/js/sidebar.js"></script>

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>