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

    <style>
        :root {
            --primary-color: #3a7bd5;
            --primary-light: #6fa1ff;
            --primary-dark: #0d47a1;
            --secondary-color: #ff9800;
            --dark-color: #333;
            --light-color: #f4f7fc;
            --success-color: #4caf50;
            --danger-color: #f44336;
            --warning-color: #ff9800;
            --info-color: #2196f3;
            --border-color: #e0e0e0;
            --medium-bg: #f0f2f5;
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 16px;
            --space-xs: 4px;
            --space-sm: 8px;
            --space-md: 16px;
            --space-lg: 24px;
            --space-xl: 32px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--medium-bg);
            color: var(--dark-color);
            line-height: 1.6;
        }

        .app-header {
            background-color: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 0.5rem 1rem;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-icon {
            font-size: 1.2rem;
            color: var(--dark-color);
        }

        .avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-color: var(--primary-light);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .content {
            margin-top: 70px;
            padding-bottom: 70px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-20 {
            margin-bottom: 20px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .d-flex {
            display: flex;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .justify-between {
            justify-content: space-between;
        }

        .align-center {
            align-items: center;
        }

        .gap-20 {
            gap: 20px;
        }

        .gap-10 {
            gap: 10px;
        }

        .card {
            background-color: white;
            border-radius: var(--radius-md);
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
        }

        .card-body {
            padding: 15px;
        }

        .card-footer {
            padding: 10px 15px;
            border-top: 1px solid var(--border-color);
        }

        h2, h3, h4, h5, h6 {
            font-weight: 600;
            line-height: 1.3;
        }

        h2 {
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        h3 {
            font-size: 1.5rem;
        }

        h4 {
            font-size: 1.2rem;
        }

        h5 {
            font-size: 1.1rem;
            margin: 0;
        }

        h6 {
            font-size: 0.9rem;
            color: var(--dark-color);
            opacity: 0.8;
            margin-bottom: 5px;
            font-weight: 500;
        }

        p {
            margin-bottom: 10px;
        }

        small {
            font-size: 0.8rem;
            color: var(--dark-color);
            opacity: 0.7;
        }

        .text-success {
            color: var(--success-color);
        }

        .text-danger {
            color: var(--danger-color);
        }

        .text-center {
            text-align: center;
        }

        .btn {
            padding: 8px 16px;
            border-radius: var(--radius-sm);
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 0.8rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-secondary {
            background-color: var(--medium-bg);
            color: var(--dark-color);
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }

        .badge {
            padding: 4px 8px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }

        .badge-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }

        .badge-warning {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning-color);
        }

        .badge-info {
            background-color: rgba(33, 150, 243, 0.1);
            color: var(--info-color);
        }

        .bottom-nav {
            background-color: white;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            box-shadow: 0 -2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-around;
            padding: 10px 0;
            z-index: 1000;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--dark-color);
            text-decoration: none;
            font-size: 0.7rem;
        }

        .nav-item i {
            font-size: 1.2rem;
            margin-bottom: 2px;
        }

        .nav-item.active {
            color: var(--primary-color);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 20px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius-md);
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .icon-primary {
            background-color: rgba(58, 123, 213, 0.1);
            color: var(--primary-color);
        }

        .icon-success {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }

        .icon-warning {
            background-color: rgba(255, 152, 0, 0.1);
            color: var(--warning-color);
        }

        .icon-danger {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }

        .icon-info {
            background-color: rgba(33, 150, 243, 0.1);
            color: var(--info-color);
        }

        .trend-indicator {
            display: inline-flex;
            align-items: center;
            padding: 3px 6px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 5px;
        }

        .trend-up {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }

        .trend-down {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }

        .trend-neutral {
            background-color: rgba(158, 158, 158, 0.1);
            color: #757575;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .race-card {
            border-left: 4px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .race-card:hover {
            transform: translateX(5px);
        }

        .event-date {
            background-color: var(--medium-bg);
            width: 60px;
            height: 60px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-sm);
        }

        .event-date-month {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary-color);
            text-transform: uppercase;
        }

        .event-date-day {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .horse-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--medium-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark-color);
            font-size: 1.2rem;
        }

        .filter-pills {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-pill {
            background-color: white;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            padding: 6px 15px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-pill:hover, .filter-pill.active {
            background-color: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid var(--border-color);
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .tab.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: -2px;
        }

        .race-track-indicator {
            height: 8px;
            border-radius: 4px;
            background-color: var(--medium-bg);
            margin-top: 5px;
            overflow: hidden;
        }

        .race-track-progress {
            height: 100%;
            border-radius: 4px;
            background-color: var(--primary-color);
        }

        .progress-container {
            height: 8px;
            background-color: var(--medium-bg);
            border-radius: 4px;
            margin: 10px 0;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 4px;
        }

        .bg-success {
            background-color: var(--success-color);
        }

        .bg-danger {
            background-color: var(--danger-color);
        }

        .bg-warning {
            background-color: var(--warning-color);
        }

        .bg-info {
            background-color: var(--info-color);
        }

        .bg-primary {
            background-color: var(--primary-color);
        }

        .profit-loss-pill {
            font-size: 1.2rem;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: 50px;
            display: inline-block;
        }

        .profit-pill {
            background-color: rgba(76, 175, 80, 0.1);
            color: var(--success-color);
        }

        .loss-pill {
            background-color: rgba(244, 67, 54, 0.1);
            color: var(--danger-color);
        }

        .pie-stats {
            position: relative;
            width: 120px;
            height: 120px;
        }

        .donut-inner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        .donut-label {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark-color);
        }

        .donut-value {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(auto-fill, minmax(100%, 1fr));
            }
        }
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