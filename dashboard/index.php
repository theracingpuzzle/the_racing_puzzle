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

            <?php include 'total-bets-stats.php'; ?>

            <div class="d-flex flex-wrap gap-20 mb-20">
                <div class="card" style="flex: 1; min-width: 250px;">
                <div class="card-body">
        <div class="d-flex justify-between align-center">
            <div>
                <h6>Total Bets</h6>
                <h3><?php echo $totalBets; ?></h3>
                <small class="<?php echo $isIncrease ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas <?php echo $isIncrease ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                    <?php echo abs($percentChange); ?>% from last month
                </small>
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

            <!-- ROI and Performance Card -->

            <?php include 'roi-stats.php'; ?>

<div class="card" style="flex: 1; min-width: 250px;">
    <div class="card-body">
        <div class="d-flex justify-between align-center">
            <div>
                <h6>Overall ROI</h6>
                <h3><?php echo number_format($overallROI, 1); ?>%</h3>
                <small class="<?php echo $roiTrend >= 0 ? 'text-success' : 'text-danger'; ?>">
                    <i class="fas <?php echo $roiTrend >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> 
                    <?php echo abs($roiTrend); ?>% from last month
                </small>
            </div>
            <div>
                <i class="fas fa-chart-line fa-lg"></i>
            </div>
        </div>
    </div>
</div>
            
            <!-- Main Dashboard Content -->
            <div class="d-flex flex-wrap gap-20">
            <?php include 'recent-activity.php'; ?>

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
                            <th style="text-align: left; padding: 10px;">Return</th>
                            <th style="text-align: left; padding: 10px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
    <?php if (!empty($recentActivity)): ?>
        <?php foreach ($recentActivity as $activity): ?>
            <tr>
                <td style="padding: 10px;">
                    <div class="d-flex align-center">
                        <div style="width: 32px; height: 32px; background-color: var(--border-color); border-radius: 4px; margin-right: 10px;"></div>
                        <div>
                            <h6 style="margin: 0;"><?= htmlspecialchars($activity['selection']) ?></h6>
                            <small><?= htmlspecialchars($activity['racecourse']) ?></small>
                        </div>
                    </div>
                </td>
                <td style="padding: 10px;"><?= date('M d, Y', strtotime($activity['date_added'])) ?></td>
                <td style="padding: 10px;">
                    <?php
                    $badgeClass = 'badge-danger';
                    if ($activity['outcome'] == 'Won') $badgeClass = 'badge-success';
                    else if ($activity['outcome'] == 'Placed') $badgeClass = 'badge-warning';
                    else if ($activity['outcome'] == 'Void') $badgeClass = 'badge-info';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($activity['outcome']) ?></span>
                </td>
                <td style="padding: 10px;">£<?= number_format($activity['returns'], 2) ?></td>
                <td style="padding: 10px;">
                    <a href="view-bet.php?id=<?= $activity['bet_id'] ?>" class="btn btn-primary">View</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" style="text-align: center; padding: 20px;">No recent activity found.</td>
        </tr>
    <?php endif; ?>
</tbody>
                </table>
            </div>
        </div>
        <div class="card-footer text-center">
            <a href="activity-history.php">View All Activities</a>
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