<?php
// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Continue with the rest of your dashboard code
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Racing Puzzle Dashboard</title>
    
    <!-- External CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Main CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">  
   
</head>
<body>

<?php include '../test/app-header.php'; ?>

    <!-- Main Content -->
    <div id="mainContent" class="content">
        <div class="container mt-20">
            <div class="d-flex justify-between align-center mb-20">
                <div>
                    <h2>Dashboard Overview</h2>
                    <p>Welcome back, Dan! Here's your racing performance summary.</p>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="d-flex flex-wrap gap-20 mb-20">
                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center">
                            <div>
                                <h6>Total Bets</h6>
                                <h3>254</h3>
                                <small><i class="fas fa-arrow-up"></i> 12% from last month</small>
                            </div>
                            <div>
                                <i class="fas fa-flag-checkered fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include 'best-jockey-stats.php'; ?>

                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-body">
                        <h6>Top Jockeys</h6>
                        <?php if (!empty($topJockeys)): ?>
                            <ol>
                                <?php foreach ($topJockeys as $jockey): ?>
                                    <li class="d-flex justify-between mb-10">
                                        <span><?= htmlspecialchars($jockey['jockey']) ?></span>
                                        <small><?= $jockey['wins'] ?> wins</small>
                                    </li>
                                <?php endforeach; ?>
                            </ol>
                        <?php else: ?>
                            <p>No data available.</p>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php include 'best-racecourse-stats.php'; ?>

                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center">
                            <div>
                                <h6>Top Racecourse</h6>
                                <h4>
                                    <?= htmlspecialchars($bestCourse['racecourse'] ?? 'N/A') ?>
                                </h4>
                                <small><?= $bestCourse['wins'] ?? 0 ?> wins</small>
                            </div>
                            <div>
                                <i class="fas fa-trophy fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <?php include 'tracker-stats.php'; ?>

                <div class="card" style="flex: 1; min-width: 250px;">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center">
                            <div>
                                <h6>Tracker</h6>
                                <h3><?= htmlspecialchars($stats['total']) ?></h3>
                                <small>Total tracked horses</small>
                            </div>
                            <div>
                                <i class="fas fa-star fa-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Dashboard Content -->
            <div class="d-flex flex-wrap gap-20">
                <!-- Recent Activity Table -->
                <div style="flex: 2; min-width: 300px;">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Activity</h5>
                            <div>
                                <button class="btn btn-secondary">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div>
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left; padding: 10px;">Race</th>
                                            <th style="text-align: left; padding: 10px;">Date</th>
                                            <th style="text-align: left; padding: 10px;">Position</th>
                                            <th style="text-align: left; padding: 10px;">Prize Money</th>
                                            <th style="text-align: left; padding: 10px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="padding: 10px;">
                                                <div class="d-flex align-center">
                                                    <div style="width: 32px; height: 32px; background-color: var(--border-color); border-radius: 4px; margin-right: 10px;"></div>
                                                    <div>
                                                        <h6 style="margin: 0;">Cheltenham Gold Cup</h6>
                                                        <small>Cheltenham</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 10px;">Mar 25, 2025</td>
                                            <td style="padding: 10px;"><span class="badge badge-success">1st</span></td>
                                            <td style="padding: 10px;">£250,000</td>
                                            <td style="padding: 10px;">
                                                <button class="btn btn-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px;">
                                                <div class="d-flex align-center">
                                                    <div style="width: 32px; height: 32px; background-color: var(--border-color); border-radius: 4px; margin-right: 10px;"></div>
                                                    <div>
                                                        <h6 style="margin: 0;">Grand National</h6>
                                                        <small>Aintree</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 10px;">Mar 22, 2025</td>
                                            <td style="padding: 10px;"><span class="badge badge-info">4th</span></td>
                                            <td style="padding: 10px;">£50,000</td>
                                            <td style="padding: 10px;">
                                                <button class="btn btn-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px;">
                                                <div class="d-flex align-center">
                                                    <div style="width: 32px; height: 32px; background-color: var(--border-color); border-radius: 4px; margin-right: 10px;"></div>
                                                    <div>
                                                        <h6 style="margin: 0;">Royal Ascot</h6>
                                                        <small>Ascot</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 10px;">Mar 19, 2025</td>
                                            <td style="padding: 10px;"><span class="badge badge-warning">2nd</span></td>
                                            <td style="padding: 10px;">£120,000</td>
                                            <td style="padding: 10px;">
                                                <button class="btn btn-primary">View</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 10px;">
                                                <div class="d-flex align-center">
                                                    <div style="width: 32px; height: 32px; background-color: var(--border-color); border-radius: 4px; margin-right: 10px;"></div>
                                                    <div>
                                                        <h6 style="margin: 0;">Epsom Derby</h6>
                                                        <small>Epsom</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td style="padding: 10px;">Mar 15, 2025</td>
                                            <td style="padding: 10px;"><span class="badge badge-danger">DNF</span></td>
                                            <td style="padding: 10px;">£0</td>
                                            <td style="padding: 10px;">
                                                <button class="btn btn-primary">View</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#">View All Activities</a>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events -->
                <div style="flex: 1; min-width: 300px;">
                    <div class="card">
                        <div class="card-header">
                            <h5>Upcoming Events</h5>
                            <div>
                                <button class="btn btn-secondary">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-20" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                                <div style="background-color: var(--medium-bg); padding: 10px; border-radius: var(--radius-sm); text-align: center; min-width: 70px; margin-right: 15px;">
                                    <span style="display: block; font-weight: bold; color: var(--primary-color);">MAR</span>
                                    <span style="display: block; font-size: 1.5rem; font-weight: bold;">30</span>
                                </div>
                                <div>
                                    <h6>Grand National</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 14:00 PM
                                    </p>
                                    <p style="margin-bottom: 0;">
                                        <i class="fas fa-map-marker-alt"></i> Aintree
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex mb-20" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                                <div style="background-color: var(--medium-bg); padding: 10px; border-radius: var(--radius-sm); text-align: center; min-width: 70px; margin-right: 15px;">
                                    <span style="display: block; font-weight: bold; color: var(--primary-color);">APR</span>
                                    <span style="display: block; font-size: 1.5rem; font-weight: bold;">05</span>
                                </div>
                                <div>
                                    <h6>2000 Guineas</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 14:30 PM
                                    </p>
                                    <p style="margin-bottom: 0;">
                                        <i class="fas fa-map-marker-alt"></i> Newmarket
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div style="background-color: var(--medium-bg); padding: 10px; border-radius: var(--radius-sm); text-align: center; min-width: 70px; margin-right: 15px;">
                                    <span style="display: block; font-weight: bold; color: var(--primary-color);">APR</span>
                                    <span style="display: block; font-size: 1.5rem; font-weight: bold;">12</span>
                                </div>
                                <div>
                                    <h6>Derby</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 15:15 PM
                                    </p>
                                    <p style="margin-bottom: 0;">
                                        <i class="fas fa-map-marker-alt"></i> Epsom
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#">View All Events</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../test/bottom-nav.php'; ?>

</body>
</html>