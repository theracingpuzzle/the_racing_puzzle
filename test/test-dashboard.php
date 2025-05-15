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

    <link rel="stylesheet" href="test-dashboard.css">
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
                <div>
                    <button class="btn btn-primary"><i class="fas fa-plus"></i> Add New Bet</button>
                </div>
            </div>
            
            <!-- Quick Stats Summary -->
            <div class="stats-grid mb-20">
                <div class="stat-card">
                    <div class="d-flex justify-between">
                        <div class="stat-icon icon-primary">
                            <i class="fas fa-flag-checkered"></i>
                        </div>
                        <div class="trend-indicator trend-up">
                            <i class="fas fa-arrow-up"></i> 12%
                        </div>
                    </div>
                    <h6>Total Bets</h6>
                    <h3>124</h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-primary" style="width: 75%;"></div>
                    </div>
                    <small>75% of your monthly target</small>
                </div>
                
                <div class="stat-card">
                    <div class="d-flex justify-between">
                        <div class="stat-icon icon-success">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="trend-indicator trend-up">
                            <i class="fas fa-arrow-up"></i> 8%
                        </div>
                    </div>
                    <h6>Win Rate</h6>
                    <h3>32%</h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-success" style="width: 32%;"></div>
                    </div>
                    <small>42 winning bets out of 124</small>
                </div>
                
                <div class="stat-card">
                    <div class="d-flex justify-between">
                        <div class="stat-icon icon-warning">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="trend-indicator trend-up">
                            <i class="fas fa-arrow-up"></i> 5.2%
                        </div>
                    </div>
                    <h6>Overall ROI</h6>
                    <h3>18.3%</h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-warning" style="width: 68%;"></div>
                    </div>
                    <small>5.2% increase from last month</small>
                </div>
                
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
                    <h3>28</h3>
                    <div class="progress-container">
                        <div class="progress-bar bg-info" style="width: 100%;"></div>
                    </div>
                    <small>3 horses racing this week</small>
                </div>
            </div>
            
            <!-- Performance Tracking Section -->
            <div class="d-flex flex-wrap gap-20 mb-20">
                <!-- Performance Chart -->
                <div class="card" style="flex: 2; min-width: 300px;">
                    <div class="card-header">
                        <h5>Betting Performance</h5>
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
                                    <div class="donut-value">124</div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-10">
                                    <div class="d-flex align-center gap-10 mb-10">
                                        <div style="width: 10px; height: 10px; background-color: #4caf50; border-radius: 50%;"></div>
                                        <span>Won (42)</span>
                                    </div>
                                    <div class="d-flex align-center gap-10 mb-10">
                                        <div style="width: 10px; height: 10px; background-color: #ff9800; border-radius: 50%;"></div>
                                        <span>Placed (28)</span>
                                    </div>
                                    <div class="d-flex align-center gap-10 mb-10">
                                        <div style="width: 10px; height: 10px; background-color: #f44336; border-radius: 50%;"></div>
                                        <span>Lost (50)</span>
                                    </div>
                                    <div class="d-flex align-center gap-10">
                                        <div style="width: 10px; height: 10px; background-color: #2196f3; border-radius: 50%;"></div>
                                        <span>Void (4)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 20px;">
                            <h6>Net Profit/Loss</h6>
                            <div class="profit-loss-pill profit-pill">
                                +£1,245.80
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Betting Analysis Section -->
            <div class="tab-container">
                <div class="tabs">
                    <div class="tab active">Betting Analysis</div>
                    <div class="tab">Race Insights</div>
                    <div class="tab">Jockey Form</div>
                    <div class="tab">Tracked Horses</div>
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
                                    <h6>Race Type</h6>
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
                        
                        <div class="mb-10">
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Racecourse</h6>
                                    <h4>Cheltenham</h4>
                                </div>
                                <div>
                                    <span class="badge badge-success">52% ROI</span>
                                </div>
                            </div>
                            <div class="race-track-indicator">
                                <div class="race-track-progress" style="width: 52%;"></div>
                            </div>
                        </div>
                        
                        <div class="mb-10">
                            <div class="d-flex justify-between mb-10">
                                <div>
                                    <h6>Jockey</h6>
                                    <h4>Harry Skelton</h4>
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
                                    <h6>Distance</h6>
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
                        <h5>Betting Patterns</h5>
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
                <div style="flex: 2; min-width: 300px;">
                    <div class="card">
                        <div class="card-header">
                            <h5>Recent Activity</h5>
                            <button class="btn btn-secondary btn-sm">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="card-body">
                            <table style="width: 100%; border-collapse: collapse;">
                                <thead>
                                    <tr>
                                        <th style="text-align: left; padding: 10px;">Date</th>
                                        <th style="text-align: left; padding: 10px;">Racecourse</th>
                                        <th style="text-align: left; padding: 10px;">Outcome</th>
                                        <th style="text-align: left; padding: 10px;">Return</th>
                                        <th style="text-align: left; padding: 10px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#">View All Activity</a>
                        </div>
                    </div>
                </div>
                
                <!-- Upcoming Events and Recommendations -->
                <div style="flex: 1; min-width: 300px;">
                    <div class="card mb-20">
                        <div class="card-header">
                            <h5>Upcoming Events</h5>
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
            <div class="card mt-20">
                <div class="card-header">
                    <h5>Horse Form Tracker</h5>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Horse
                    </button>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-20">
                        <div class="card" style="flex: 1; min-width: 220px;">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center mb-10">
                                    <h5>Blue Serpent</h5>
                                    <i class="fas fa-star" style="color: var(--warning-color);"></i>
                                </div>
                                <p><small><i class="fas fa-user"></i> Trainer: N. Henderson</small></p>
                                <div style="margin: 15px 0;">
                                    <div class="d-flex gap-10 justify-between">
                                        <span>Last 5 runs:</span>
                                        <div>
                                            <span class="badge badge-success">1</span>
                                            <span class="badge badge-success">1</span>
                                            <span class="badge badge-warning">2</span>
                                            <span class="badge badge-danger">6</span>
                                            <span class="badge badge-success">1</span>
                                        </div>
                                    </div>
                                </div>
                                <p><small>Next race: Cheltenham, Apr 28</small></p>
                                <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">View Details</button>
                            </div>
                        </div>
                        
                        <div class="card" style="flex: 1; min-width: 220px;">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center mb-10">
                                    <h5>Thunder Strike</h5>
                                    <i class="fas fa-star" style="color: var(--warning-color);"></i>
                                </div>
                                <p><small><i class="fas fa-user"></i> Trainer: P. Nicholls</small></p>
                                <div style="margin: 15px 0;">
                                    <div class="d-flex gap-10 justify-between">
                                        <span>Last 5 runs:</span>
                                        <div>
                                            <span class="badge badge-success">1</span>
                                            <span class="badge badge-danger">4</span>
                                            <span class="badge badge-danger">5</span>
                                            <span class="badge badge-warning">3</span>
                                            <span class="badge badge-success">1</span>
                                        </div>
                                    </div>
                                </div>
                                <p><small>Next race: Ascot, May 02</small></p>
                                <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">View Details</button>
                            </div>
                        </div>
                        
                        <div class="card" style="flex: 1; min-width: 220px;">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center mb-10">
                                    <h5>Silver Storm</h5>
                                    <i class="fas fa-star" style="color: var(--warning-color);"></i>
                                </div>
                                <p><small><i class="fas fa-user"></i> Trainer: D. Skelton</small></p>
                                <div style="margin: 15px 0;">
                                    <div class="d-flex gap-10 justify-between">
                                        <span>Last 5 runs:</span>
                                        <div>
                                            <span class="badge badge-warning">2</span>
                                            <span class="badge badge-warning">2</span>
                                            <span class="badge badge-success">1</span>
                                            <span class="badge badge-warning">3</span>
                                            <span class="badge badge-danger">4</span>
                                        </div>
                                    </div>
                                </div>
                                <p><small>Next race: Cheltenham, Apr 28</small></p>
                                <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">View Details</button>
                            </div>
                        </div>
                        
                        <div class="card" style="flex: 1; min-width: 220px;">
                            <div class="card-body">
                                <div class="d-flex justify-between align-center mb-10">
                                    <h5>Northern Light</h5>
                                    <i class="fas fa-star" style="color: var(--warning-color);"></i>
                                </div>
                                <p><small><i class="fas fa-user"></i> Trainer: W. Mullins</small></p>
                                <div style="margin: 15px 0;">
                                    <div class="d-flex gap-10 justify-between">
                                        <span>Last 5 runs:</span>
                                        <div>
                                            <span class="badge badge-danger">5</span>
                                            <span class="badge badge-danger">6</span>
                                            <span class="badge badge-warning">3</span>
                                            <span class="badge badge-warning">2</span>
                                            <span class="badge badge-danger">4</span>
                                        </div>
                                    </div>
                                </div>
                                <p><small>Next race: Newmarket, May 08</small></p>
                                <button class="btn btn-outline" style="width: 100%; margin-top: 10px;">View Details</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-chart-line"></i>
            <span>Stats</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-horse-head"></i>
            <span>Races</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-star"></i>
            <span>Tracker</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-user"></i>
            <span>Profile</span>
        </a>
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
                    data: [42, 28, 50, 4],
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
                labels: ['H. Skelton', 'R. Johnson', 'T. Scudamore', 'N. de Boinville', 'B. Geraghty'],
                datasets: [{
                    label: 'Win Rate %',
                    data: [42, 35, 32, 28, 25],
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
</html>Horse</th>
                                        <th style="text-align: left; padding: 10px;">