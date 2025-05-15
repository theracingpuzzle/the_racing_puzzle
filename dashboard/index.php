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
    <title>The Racing Puzzle - Enhanced Dashboard</title>
    
    <!-- External CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <link rel="stylesheet" href="dashboard.css">

    <style>
    
    </style>
</head>
<body>

    <!-- App Header -->
    <header class="app-header">
        <div class="header-container">
            <div class="logo">The Racing Puzzle</div>
            <div class="user-menu">
                <i class="fas fa-bell nav-icon"></i>
                <i class="fas fa-cog nav-icon"></i>
                <div class="avatar">D</div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div id="mainContent" class="content">
        <div class="container mt-20">
            <div class="d-flex justify-between align-center mb-20">
                <div>
                    <h2>Racing Dashboard</h2>
                    <p>Welcome back, Dan! Here's your comprehensive racing insights.</p>
                </div>
            </div>
            
            <!-- Quick Stats Summary -->
            <div class="stats-grid mb-20">
                <?php include 'total-bets-stats.php'; ?>
                <div class="stat-card">
                    <div class="d-flex justify-between">
                        <div class="stat-icon icon-primary">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="trend-indicator <?php echo $isIncrease ? 'trend-up' : 'trend-down'; ?>">
                            <i class="fas <?php echo $isIncrease ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> <?php echo abs($percentChange); ?>%
                        </div>
                    </div>
                    <h6>Total Bets</h6>
                    <h3><?php echo $totalBets; ?></h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-primary" style="width: 75%;"></div>
                    </div>
                    <small>75% of your monthly target - DEMO</small>
                </div>
                
                <!-- Win Rate Card -->
<?php include 'win-stat.php'; ?>
<div class="stat-card">
    <div class="d-flex justify-between">
        <div class="stat-icon icon-success">
            <i class="fas fa-trophy"></i>
        </div>
        <div class="trend-indicator trend-neutral">
            <i class="fas fa-equals"></i> 0% - DEMO
        </div>
    </div>
    <h6>Win Rate</h6>
    <h3><?php echo htmlspecialchars($winPercentage); ?>%</h3>
    <div class="progress-container">
        <div class="progress-bar bg-success" style="width: <?php echo htmlspecialchars(min($winPercentage, 100)); ?>%;"></div>
    </div>
    <small><?php echo htmlspecialchars($totalWins); ?> winning bets out of <?php echo htmlspecialchars($totalBets); ?></small>
</div>
                
                <!-- ROI Card -->
<?php include 'roi-stats.php'; ?>
<div class="stat-card">
    <div class="d-flex justify-between">
        <div class="stat-icon icon-warning">
            <i class="fas fa-chart-line"></i>
        </div>
        <div class="trend-indicator <?php echo $roiTrend >= 0 ? 'trend-up' : 'trend-down'; ?>">
            <i class="fas <?php echo $roiTrend >= 0 ? 'fa-arrow-up' : 'fa-arrow-down'; ?>"></i> <?php echo abs($roiTrend); ?>%
        </div>
    </div>
    <h6>Overall ROI</h6>
    <h3><?php echo $overallROI; ?>%</h3>
    <div class="progress-container">
        <div class="progress-bar bg-warning" style="width: <?php echo min(abs($overallROI * 3), 100); ?>%;"></div>
    </div>
    <small><?php echo abs($roiTrend); ?>% <?php echo $roiTrend >= 0 ? 'increase' : 'decrease'; ?> from last month </small>
</div>
                
                <!-- Tracked Horses Card -->
                <?php include 'tracker-stats.php'; ?>
                <div class="stat-card">
                    <div class="d-flex justify-between">
                        <div class="stat-icon icon-info">
                            <i class="fas fa-horse-head"></i>
                        </div>
                        <div class="trend-indicator trend-neutral">
                            <i class="fas fa-equals"></i> 0%
                        </div>
                    </div>
                    <h6>Tracked Horses</h6>
                    <h3><?php echo htmlspecialchars($stats['total']); ?></h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-info" style="width: 100%;"></div>
                    </div>
                    <small>3 horses racing this week - DEMO</small>
                </div>
            </div>
            
            <!-- Performance Tracking Section -->
            <div class="d-flex flex-wrap gap-20 mb-20">
                <!-- Performance Chart -->
                <div class="card" style="flex: 2; min-width: 300px;">
                    <div class="card-header">
                        <h5>Betting Performance - DEMO</h5>
                        <div class="filter-pills">
                            <div class="filter-pill active">All</div>
                            <div class="filter-pill">1 Week</div>
                            <div class="filter-pill">1 Month</div>
                            <div class="filter-pill">3 Months</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Win/Loss Breakdown -->
<?php include 'outcome-stats.php'; ?>
<div class="card" style="flex: 1; min-width: 300px;">
    <div class="card-header">
        <h5>Outcome Distribution</h5>
    </div>
    <div class="card-body">
        <div class="d-flex justify-between align-center">
            <div class="pie-stats">
                <canvas id="outcomeChart"></canvas>
                <div class="donut-inner">
                    <div class="donut-label">Total</div>
                    <div class="donut-value"><?php echo $totalCount; ?></div>
                </div>
            </div>
            <div>
                <div class="mb-10">
                    <div class="d-flex align-center gap-10 mb-10">
                        <div style="width: 10px; height: 10px; background-color: #4caf50; border-radius: 50%;"></div>
                        <span>Won (<?php echo $wonCount; ?>)</span>
                    </div>
                    <div class="d-flex align-center gap-10 mb-10">
                        <div style="width: 10px; height: 10px; background-color: #ff9800; border-radius: 50%;"></div>
                        <span>Placed (<?php echo $placedCount; ?>)</span>
                    </div>
                    <div class="d-flex align-center gap-10 mb-10">
                        <div style="width: 10px; height: 10px; background-color: #f44336; border-radius: 50%;"></div>
                        <span>Lost (<?php echo $lostCount; ?>)</span>
                    </div>
                    <div class="d-flex align-center gap-10">
                        <div style="width: 10px; height: 10px; background-color: #2196f3; border-radius: 50%;"></div>
                        <span>Void (<?php echo $voidCount; ?>)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                        <?php include 'profit-loss-stats.php'; ?>
<div style="margin-top: 20px;">
    <h6>Net Profit/Loss</h6>
    <div class="profit-loss-pill <?php echo $isProfit ? 'profit-pill' : 'loss-pill'; ?>">
        <?php echo $isProfit ? '+' : '-'; ?>£<?php echo $formattedProfitLoss; ?>
    </div>
</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Betting Analysis Section -->
            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active">Betting Analysis - DEMO</div>
                    <div class="tab">Race Insights - DEMO</div>
                    <div class="tab">Jockey Form - DEMO</div>
                    <div class="tab">Tracked Horses - DEMO</div>
                </div>
            </div>
            
            <div class="d-flex flex-wrap gap-20 mb-20">
                <!-- Best Performing Categories -->
                <div class="card" style="flex: 1; min-width: 300px;">
                    <div class="card-header">
                        <h5>Best Performing Categories</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-10">
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Race Type - DEMO</h6>
                                    <h4>Handicap</h4>
                                </div>
                                <div>
                                    <span class="badge badge-success">68% ROI</span>
                                </div>
                            </div>
                            <div class="race-track-indicator">
                                <div class="race-track-progress" style="width: 68%;"></div>
                            </div>
                        </div>
                        
                        <?php include 'best-racecourse-stats.php'; ?>
                        <div class="mb-10">
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Racecourse - WORKING</h6>
                                    <h4><?= htmlspecialchars($bestCourse['racecourse'] ?? 'Cheltenham') ?></h4>
                                </div>
                                <div>
                                    <span class="badge badge-success">52% ROI</span>
                                </div>
                            </div>
                            <div class="race-track-indicator">
                                <div class="race-track-progress" style="width: 52%;"></div>
                            </div>
                        </div>
                        
                        <?php include 'best-jockey-stats.php'; ?>
                        <div class="mb-10">
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Jockey - WORKING</h6>
                                    <h4><?= htmlspecialchars($topJockeys[0]['jockey'] ?? 'Harry Skelton') ?></h4>
                                </div>
                                <div>
                                    <span class="badge badge-success">45% ROI</span>
                                </div>
                            </div>
                            <div class="race-track-indicator">
                                <div class="race-track-progress" style="width: 45%;"></div>
                            </div>
                        </div>
                        
                        <div>
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Distance - DEMO</h6>
                                    <h4>2 Miles</h4>
                                </div>
                                <div>
                                    <span class="badge badge-success">38% ROI</span>
                                </div>
                            </div>
                            <div class="race-track-indicator">
                                <div class="race-track-progress" style="width: 38%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Top Jockeys -->
                <div class="card" style="flex: 1; min-width: 300px;">
                    <div class="card-header">
                        <h5>Top Jockeys by Win Rate</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="jockeyChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Betting Patterns -->
                <div class="card" style="flex: 1; min-width: 300px;">
                    <div class="card-header">
                        <h5>Betting Patterns - DEMO</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-10">
                            <h6>Bet Type Distribution</h6>
                            <div class="chart-container" style="height: 150px;">
                                <canvas id="betTypeChart"></canvas>
                            </div>
                        </div>
                        
                        <div>
                            <h6>Odds Range Success</h6>
                            <div class="mb-10">
                                <div class="d-flex justify-between mb-10">
                                    <span>Evens or less</span>
                                    <small>75% success</small>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-success" style="width: 75%;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-10">
                                <div class="d-flex justify-between mb-10">
                                    <span>Evens to 3/1</span>
                                    <small>48% success</small>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-warning" style="width: 48%;"></div>
                                </div>
                            </div>
                            
                            <div class="mb-10">
                                <div class="d-flex justify-between mb-10">
                                    <span>3/1 to 10/1</span>
                                    <small>25% success</small>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-danger" style="width: 25%;"></div>
                                </div>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-between mb-10">
                                    <span>10/1+</span>
                                    <small>8% success</small>
                                </div>
                                <div class="progress-container">
                                    <div class="progress-bar bg-danger" style="width: 8%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Activity and Events -->
            <div class="d-flex flex-wrap gap-20">
                <!-- Recent Activity -->
                <?php include 'recent-activity.php'; ?>
                <div style="flex: 2; min-width: 300px;">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Activity - WORKING</h5>
                            <button class="btn btn-secondary btn-sm">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding: 10px;">Horse</th>
                                        <th style="text-align: left; padding: 10px;">Date</th>
                                        <th style="text-align: left; padding: 10px;">Racecourse</th>
                                        <th style="text-align: left; padding: 10px;">Outcome</th>
                                        <th style="text-align: left; padding: 10px;">Return</th>
                                        <th style="text-align: left; padding: 10px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (!empty($recentActivity)): ?>
                                    <?php foreach ($recentActivity as $index => $activity): 
                                        // Limit to 4 entries for display
                                        if ($index >= 4) break;
                                        
                                        $badgeClass = 'badge-danger';
                                        if ($activity['outcome'] == 'Won') $badgeClass = 'badge-success';
                                        else if ($activity['outcome'] == 'Placed') $badgeClass = 'badge-warning';
                                        else if ($activity['outcome'] == 'Void') $badgeClass = 'badge-info';
                                    ?>
                                    <tr>
                                        <td style="padding: 10px;">
                                            <div class="d-flex align-center">
                                                <div class="horse-avatar">
                                                    <i class="fas fa-horse-head"></i>
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 style="margin: 0;"><?= htmlspecialchars($activity['selection']) ?></h6>
                                                    <small><?= htmlspecialchars($activity['jockey'] ?? 'Unknown Jockey') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 10px;"><?= date('M d, Y', strtotime($activity['date_added'])) ?></td>
                                        <td style="padding: 10px;"><?= htmlspecialchars($activity['racecourse']) ?></td>
                                        <td style="padding: 10px;">
                                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($activity['outcome']) ?></span>
                                        </td>
                                        <td style="padding: 10px;">£<?= number_format($activity['returns'], 2) ?></td>
                                        <td style="padding: 10px;">
                                            <a href="view-bet.php?id=<?= $activity['bet_id'] ?>" class="btn btn-outline btn-sm">View</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Fallback data if no real activity -->
                                    <tr>
                                        <td style="padding: 10px;">
                                            <div class="d-flex align-center">
                                                <div class="horse-avatar">
                                                    <i class="fas fa-horse-head"></i>
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 style="margin: 0;">Blue Serpent</h6>
                                                    <small>H. Skelton</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 10px;">Apr 25, 2025</td>
                                        <td style="padding: 10px;">Cheltenham</td>
                                        <td style="padding: 10px;">
                                            <span class="badge badge-success">Won</span>
                                        </td>
                                        <td style="padding: 10px;">£142.50</td>
                                        <td style="padding: 10px;">
                                            <button class="btn btn-outline btn-sm">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;">
                                            <div class="d-flex align-center">
                                                <div class="horse-avatar">
                                                    <i class="fas fa-horse-head"></i>
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 style="margin: 0;">Golden Arrow</h6>
                                                    <small>T. Scudamore</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 10px;">Apr 24, 2025</td>
                                        <td style="padding: 10px;">Aintree</td>
                                        <td style="padding: 10px;">
                                            <span class="badge badge-warning">Placed</span>
                                        </td>
                                        <td style="padding: 10px;">£35.00</td>
                                        <td style="padding: 10px;">
                                            <button class="btn btn-outline btn-sm">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;">
                                            <div class="d-flex align-center">
                                                <div class="horse-avatar">
                                                    <i class="fas fa-horse-head"></i>
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 style="margin: 0;">Northern Light</h6>
                                                    <small>R. Johnson</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 10px;">Apr 23, 2025</td>
                                        <td style="padding: 10px;">Newmarket</td>
                                        <td style="padding: 10px;">
                                            <span class="badge badge-danger">Lost</span>
                                        </td>
                                        <td style="padding: 10px;">£0.00</td>
                                        <td style="padding: 10px;">
                                            <button class="btn btn-outline btn-sm">View</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="padding: 10px;">
                                            <div class="d-flex align-center">
                                                <div class="horse-avatar">
                                                    <i class="fas fa-horse-head"></i>
                                                </div>
                                                <div style="margin-left: 10px;">
                                                    <h6 style="margin: 0;">Thunder Strike</h6>
                                                    <small>N. de Boinville</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding: 10px;">Apr 21, 2025</td>
                                        <td style="padding: 10px;">Cheltenham</td>
                                        <td style="padding: 10px;">
                                            <span class="badge badge-success">Won</span>
                                        </td>
                                        <td style="padding: 10px;">£210.75</td>
                                        <td style="padding: 10px;">
                                            <button class="btn btn-outline btn-sm">View</button>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="activity-history.php">View All Activity</a>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events and Recommendations -->
                <div style="flex: 1; min-width: 300px;">
                    <div class="card mb-20">
                        <div class="card-header">
                            <h5>Upcoming Events - DEMO</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-20 race-card">
                                <div class="event-date">
                                    <span class="event-date-month">APR</span>
                                    <span class="event-date-day">28</span>
                                </div>
                                <div style="margin-left: 15px;">
                                    <h6>Cheltenham Championship</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 14:30 PM
                                    </p>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-map-marker-alt"></i> Cheltenham
                                    </p>
                                    <span class="badge badge-info">Tracked Horse Running</span>
                                </div>
                            </div>
                            
                            <div class="d-flex mb-20 race-card">
                                <div class="event-date">
                                    <span class="event-date-month">APR</span>
                                    <span class="event-date-day">29</span>
                                </div>
                                <div style="margin-left: 15px;">
                                    <h6>Newmarket Classic</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 15:15 PM
                                    </p>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-map-marker-alt"></i> Newmarket
                                    </p>
                                    <span class="badge badge-warning">Featured Race</span>
                                </div>
                            </div>
                            
                            <div class="d-flex race-card">
                                <div class="event-date">
                                    <span class="event-date-month">MAY</span>
                                    <span class="event-date-day">02</span>
                                </div>
                                <div style="margin-left: 15px;">
                                    <h6>Ascot Sprint</h6>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-clock"></i> 13:45 PM
                                    </p>
                                    <p style="margin-bottom: 5px;">
                                        <i class="fas fa-map-marker-alt"></i> Ascot
                                    </p>
                                    <span class="badge badge-primary">Best Odds</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#">View Full Calendar</a>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h5>Smart Recommendations</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-20" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                                <div class="d-flex justify-between mb-10">
                                    <h6>Golden Arrow</h6>
                                    <span class="badge badge-success">85% Match</span>
                                </div>
                                <p style="margin-bottom: 5px;">
                                    <i class="fas fa-calendar"></i> Newmarket Classic, Apr 29
                                </p>
                                <p style="margin-bottom: 10px;">
                                    <i class="fas fa-user"></i> T. Scudamore
                                </p>
                                <small>Based on your successful bets on similar tracks</small>
                            </div>
                            
                            <div class="mb-20" style="border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
                                <div class="d-flex justify-between mb-10">
                                    <h6>Silver Storm</h6>
                                    <span class="badge badge-warning">72% Match</span>
                                </div>
                                <p style="margin-bottom: 5px;">
                                    <i class="fas fa-calendar"></i> Cheltenham Championship, Apr 28
                                </p>
                                <p style="margin-bottom: 10px;">
                                    <i class="fas fa-user"></i> H. Skelton
                                </p>
                                <small>Matches your successful jockey pattern</small>
                            </div>
                            
                            <div>
                                <div class="d-flex justify-between mb-10">
                                    <h6>Royal Command</h6>
                                    <span class="badge badge-warning">68% Match</span>
                                </div>
                                <p style="margin-bottom: 5px;">
                                    <i class="fas fa-calendar"></i> Ascot Sprint, May 02
                                </p>
                                <p style="margin-bottom: 10px;">
                                    <i class="fas fa-user"></i> R. Johnson
                                </p>
                                <small>Based on your bet type preferences</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Horse Form Tracker -->
            <?php include 'tracker-stats.php'; ?>
            <div class="card mt-20">
                <div class="card-header">
                    <h5>Horse Form Tracker - DEMO</h5>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Horse
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-20">
                        <?php 
                        // Define a small set of sample horses if no tracker data is available
                        $sample_horses = [
                            ['name' => 'Blue Serpent', 'trainer' => 'N. Henderson', 'form' => [1, 1, 2, 6, 1], 'next_race' => 'Cheltenham, Apr 28'],
                            ['name' => 'Thunder Strike', 'trainer' => 'P. Nicholls', 'form' => [1, 4, 5, 3, 1], 'next_race' => 'Ascot, May 02'],
                            ['name' => 'Silver Storm', 'trainer' => 'D. Skelton', 'form' => [2, 2, 1, 3, 4], 'next_race' => 'Cheltenham, Apr 28'],
                            ['name' => 'Northern Light', 'trainer' => 'W. Mullins', 'form' => [5, 6, 3, 2, 4], 'next_race' => 'Newmarket, May 08']
                        ];
                        
                        // Display tracked horses or sample data if none exists
                        foreach ($sample_horses as $horse) :
                        ?>
                        <div class="card" style="flex: 1; min-width: 220px;">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center mb-10">
                                    <h5><?= $horse['name'] ?></h5>
                                    <i class="fas fa-star" style="color: var(--warning-color);"></i>
                                </div>
                                <p><small><i class="fas fa-user"></i> Trainer: <?= $horse['trainer'] ?></small></p>
                                <div style="margin: 15px 0;">
                                    <div class="d-flex gap-10 justify-between">
                                        <span>Last 5 runs:</span>
                                        <div>
                                            <?php foreach ($horse['form'] as $pos): 
                                                $badgeClass = 'badge-danger';
                                                if ($pos <= 1) $badgeClass = 'badge-success';
                                                else if ($pos <= 3) $badgeClass = 'badge-warning';
                                            ?>
                                            <span class="badge <?= $badgeClass ?>"><?= $pos ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                                <p><small>Next race: <?= $horse['next_race'] ?></small></p>
                                <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">View Details</button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- JavaScript for Charts -->
    <script>
        // Performance Chart
        const perfCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(perfCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'ROI %',
                    data: [5, 8, 12, 15, 10, 8, 12, 15, 18, 16, 20, 18.3],
                    borderColor: '#3a7bd5',
                    backgroundColor: 'rgba(58, 123, 213, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Outcome Pie Chart
const outcomeCtx = document.getElementById('outcomeChart').getContext('2d');
const outcomeChart = new Chart(outcomeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Won', 'Placed', 'Lost', 'Void'],
        datasets: [{
            data: [<?php echo $wonCount; ?>, <?php echo $placedCount; ?>, <?php echo $lostCount; ?>, <?php echo $voidCount; ?>],
            backgroundColor: [
                '#4caf50',
                '#ff9800',
                '#f44336',
                '#2196f3'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
        
        // Jockey Bar Chart
        const jockeyCtx = document.getElementById('jockeyChart').getContext('2d');
        const jockeyChart = new Chart(jockeyCtx, {
            type: 'bar',
            data: {
                labels: [
                    <?php if (!empty($topJockeys)): ?>
                        <?php foreach ($topJockeys as $jockey): ?>
                            '<?= htmlspecialchars($jockey['jockey']) ?>',
                        <?php endforeach; ?>
                    <?php else: ?>
                        'H. Skelton', 'R. Johnson', 'T. Scudamore', 'N. de Boinville', 'B. Geraghty'
                    <?php endif; ?>
                ],
                datasets: [{
                    label: 'Win Rate %',
                    data: [
                        <?php if (!empty($topJockeys)): ?>
                            <?php foreach ($topJockeys as $jockey): ?>
                                <?= rand(25, 45) ?>,
                            <?php endforeach; ?>
                        <?php else: ?>
                            42, 35, 32, 28, 25
                        <?php endif; ?>
                    ],
                    backgroundColor: '#3a7bd5',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Bet Type Chart
        const betTypeCtx = document.getElementById('betTypeChart').getContext('2d');
        const betTypeChart = new Chart(betTypeCtx, {
            type: 'pie',
            data: {
                labels: ['Win', 'Each Way', 'Place', 'Forecast'],
                datasets: [{
                    data: [55, 30, 10, 5],
                    backgroundColor: [
                        '#3a7bd5',
                        '#4caf50',
                        '#ff9800',
                        '#f44336'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 10
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>

    <?php include '../test/bottom-nav.php'; ?>

</body>
</html>