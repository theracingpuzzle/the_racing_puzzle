<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Cards - Redesigned</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto+Mono&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="racecard2.css">
       
</head>
<body>
    <?php include 'race-data.php'; ?>

    <!-- New App Header -->
    <header class="app-header">
        <div class="container">
            <div class="header-content">
            <div class="logo-container">
    <img src="../assets/images/logo.png" alt="Race Cards Logo">
    <h1>Race Cards</h1>
</div>

                <div class="header-actions">
                    <button class="action-button" title="Notifications">
                        <i class="fas fa-bell"></i>
                    </button>
                    <button class="action-button" title="My Tracker">
                        <i class="fas fa-star"></i>
                    </button>
                    <button class="action-button primary" title="Quick Add to Tracker">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Date Navigation -->
    <div class="date-navigation">
        <div class="container">
            <div class="date-slider">
                <?php
                // Create date slider
                $today = strtotime('2025-04-20'); // Using the date from your content
                for ($i = -3; $i <= 3; $i++) {
                    $date = strtotime("$i days", $today);
                    $isActive = $i === 0 ? 'active' : '';
                    
                    echo '<div class="date-item ' . $isActive . '">';
                    echo '<div class="day-name">' . date('D', $date) . '</div>';
                    echo '<div class="day-number">' . date('j', $date) . '</div>';
                    echo '<div class="day-month">' . date('M', $date) . '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Quick Filters -->
    <div class="quick-filters">
        <div class="container">
            <div class="filter-container">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search horses, jockeys, courses...">
                </div>
                <button class="filter-button active">
                    <i class="fas fa-globe"></i> All
                </button>
                <button class="filter-button">
                    <i class="fas fa-leaf"></i> Flat
                </button>
                <button class="filter-button">
                    <i class="fas fa-mountain"></i> All Weather
                </button>
                <button class="filter-button">
                    <i class="fas fa-leaf"></i> Jumps
                </button>
                <button class="filter-button">
                    <i class="fas fa-star"></i> Featured
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div id="race-container">
            <?php 
            foreach ($allCourses as $courseName => $courseData): ?>
                <!-- Course Container -->
                <div class="course-container">
                    <div class="course-header turf expanded">
                        <h2><img class="course-logo"> <?php echo htmlspecialchars($courseName); ?></h2>
                        <div class="toggle-icon">▼</div>
                    </div>
                    <div class="races-container">
                        <?php 
                        // Sort race times chronologically
                        $races = $courseData['races'];
                        ksort($races);
                        
                        foreach ($races as $time => $race): ?>
                            <!-- Race Card from JSON data -->
                            <div class="race-card">
                                <div class="race-status-badge status-upcoming">
                                    <i class="fas fa-clock"></i> Upcoming
                                </div>
                                <div class="race-header">
                                    <div class="race-header-left">
                                        <div class="race-time"><?php echo htmlspecialchars($time); ?></div>
                                        <h3><?php echo htmlspecialchars($race['race_name']); ?></h3>
                                    </div>
                                    <div class="race-header-details">
                                        <span><i class="fas fa-ruler-horizontal"></i> <?php echo htmlspecialchars($race['distance']); ?></span>
                                        <span><i class="fas fa-leaf"></i> <?php echo htmlspecialchars($race['surface']); ?></span>
                                        <span class="going-indicator going-good"><?php echo htmlspecialchars($race['going']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="race-info">
                                    <div class="race-info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-info-circle"></i> Race Details
                                        </div>
                                        <div class="info-card-content">
                                            <strong><?php echo htmlspecialchars($race['race_class']); ?></strong> - <?php echo htmlspecialchars($race['type']); ?> | Ages: <?php echo htmlspecialchars($race['age_band']); ?><br>
                                            Weather: <?php echo htmlspecialchars($race['weather']); ?> | Going Detail: <?php echo htmlspecialchars($race['going_detailed']); ?>
                                        </div>
                                    </div>
                                    <div class="race-info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-trophy"></i> Prize Money
                                        </div>
                                        <div class="info-card-content">
                                            Total: <span class="prize-money"><?php echo htmlspecialchars($race['prize']); ?></span><br>
                                            Field Size: <?php echo htmlspecialchars($race['field_size']); ?> runners
                                        </div>
                                    </div>
                                    <div class="race-info-card">
                                        <div class="info-card-header">
                                            <i class="fas fa-user-friends"></i> Runners & Riders
                                        </div>
                                        <div class="info-card-content">
                                            <strong><?php echo count($race['runners']); ?> Declared Runners</strong><br>
                                            Region: <?php echo htmlspecialchars($race['region']); ?> | Stalls: <?php echo htmlspecialchars($race['stalls']); ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- View Toggle -->
                                <div class="view-toggle">
                                    <button class="toggle-button active">
                                        <i class="fas fa-th"></i> Card View
                                    </button>
                                    <button class="toggle-button">
                                        <i class="fas fa-list"></i> Table View
                                    </button>
                                </div>

                                <!-- Runner Cards -->
                                <div class="runners-grid">
                                    <?php foreach ($race['runners'] as $runner): ?>
                                        <div class="runner-card">
                                            <div class="runner-header">
                                                <div class="runner-number"><?php echo htmlspecialchars($runner['number']); ?></div>
                                                <div class="runner-name-container">
                                                    <h4 class="runner-name"><?php echo htmlspecialchars($runner['name']); ?></h4>
                                                    <div class="form-figures"><?php echo htmlspecialchars($runner['form']); ?></div>
                                                </div>
                                                <div class="silks-container">
                                                    <img src="<?php echo htmlspecialchars($runner['silk_url']); ?>" alt="Racing Silks" class="silks-image">
                                                </div>
                                            </div>
                                            <div class="runner-data">
                                                <div class="data-row">
                                                    <div class="data-label">Jockey:</div>
                                                    <div class="data-value jockey"><?php echo htmlspecialchars($runner['jockey']); ?></div>
                                                </div>
                                                <div class="data-row">
                                                    <div class="data-label">Trainer:</div>
                                                    <div class="data-value"><?php echo htmlspecialchars($runner['trainer']); ?></div>
                                                </div>
                                                <div class="data-row">
                                                    <div class="data-label">Age/Weight:</div>
                                                    <div class="data-value"><?php echo htmlspecialchars($runner['age']); ?>yrs | <?php echo floor($runner['lbs']/14); ?>-<?php echo $runner['lbs'] % 14; ?></div>
                                                </div>
                                                <div class="data-row">
                                                    <div class="data-label">Draw:</div>
                                                    <div class="data-value"><?php echo htmlspecialchars($runner['draw']); ?> of <?php echo $race['field_size']; ?></div>
                                                </div>
                                            </div>
                                            <div class="runner-footer">
                                                <div class="odds-display">
                                                    <!-- Placeholder for odds - not in JSON -->
                                                    5/1
                                                </div>
                                                <div class="action-buttons">
    <button class="runner-action" 
        onclick="openQuickTracker(
            '<?php echo addslashes($runner['name']); ?>', 
            '<?php echo addslashes($runner['jockey']); ?>', 
            '<?php echo addslashes($runner['trainer']); ?>'
        )"
    >
        <i class="far fa-star"></i>
    </button>
    <button class="runner-action">
        <i class="fas fa-info-circle"></i>
    </button>
</div>

                                            
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Runner Table -->
                                <div class="runners-table" style="display: none;">
                                    <table class="race-table">
                                        <thead>
                                            <tr>
                                                <th class="number-col">No.</th>
                                                <th class="draw-col">Draw</th>
                                                <th class="silks-col">Silks</th>
                                                <th class="horse-col">Horse</th>
                                                <th class="form-col">Form</th>
                                                <th class="jockey-col">Jockey</th>
                                                <th class="trainer-col">Trainer</th>
                                                <th class="age-col">Age</th>
                                                <th class="weight-col">Weight</th>
                                                <th class="odds-col">Odds</th>
                                                <th class="actions-col">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($race['runners'] as $runner): ?>
                                                <tr class="runner-row">
                                                    <td class="number-col"><?php echo htmlspecialchars($runner['number']); ?></td>
                                                    <td class="draw-col">
                                                        <div class="draw-indicator"><?php echo htmlspecialchars($runner['draw']); ?></div>
                                                    </td>
                                                    <td class="silks-col">
                                                        <img src="<?php echo htmlspecialchars($runner['silk_url']); ?>" alt="Racing Silks" class="table-silks">
                                                    </td>
                                                    <td class="horse-col">
                                                        <div class="horse-name"><?php echo htmlspecialchars($runner['name']); ?></div>
                                                    </td>
                                                    <td class="form-col">
                                                        <div class="form-display"><?php echo htmlspecialchars($runner['form']); ?></div>
                                                    </td>
                                                    <td class="jockey-col"><?php echo htmlspecialchars($runner['jockey']); ?></td>
                                                    <td class="trainer-col"><?php echo htmlspecialchars($runner['trainer']); ?></td>
                                                    <td class="age-col"><?php echo htmlspecialchars($runner['age']); ?></td>
                                                    <td class="weight-col"><?php echo floor($runner['lbs']/14); ?>-<?php echo $runner['lbs'] % 14; ?></td>
                                                    <td class="odds-col">
                                                        <div class="odds-display">5/1</div>
                                                    </td>
                                                    <td class="actions-col">
                                                        <div class="table-actions">
                                                            <button class="table-action-btn" title="Add to Tracker">
                                                                <i class="far fa-star"></i>
                                                            </button>
                                                            <button class="table-action-btn" title="Runner Details">
                                                                <i class="fas fa-info-circle"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Race Actions -->
                                <div class="race-actions">
                                    <div class="action-group">
                                        <button class="race-action-btn">
                                            <i class="fas fa-chart-bar"></i> Stats
                                        </button>
                                        <button class="race-action-btn">
                                            <i class="fas fa-history"></i> Past Results
                                        </button>
                                    </div>
                                    <div class="action-group">
                                        <button class="race-action-btn primary">
                                            <i class="fas fa-play"></i> Livestream
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <nav class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-calendar-day nav-icon"></i>
            <span>Today</span>
        </a>
        <a href="../horse-tracker" class="nav-item">
            <i class="fas fa-horse nav-icon"></i>
            <span>My Tracker</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-chart-line nav-icon"></i>
            <span>Results</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-list-ul nav-icon"></i>
            <span>Meetings</span>
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-user nav-icon"></i>
            <span>Profile</span>
        </a>
    </nav>

    <!-- Quick Tracker Modal -->
<div id="quick-tracker-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add Horse to Tracker</h2>
            <span class="close-quick-tracker">&times;</span>
        </div>
        <form id="quick-tracker-form">
            <div class="form-group">
                <label for="quick-horse-name">Horse Name</label>
                <input type="text" id="quick-horse-name" name="quick-horse-name" required>
            </div>
            <div class="form-group">
                <label for="quick-trainer">Trainer</label>
                <input type="text" id="quick-trainer" name="quick-trainer">
            </div>
            <div class="form-group">
                <label for="quick-notes">Notes</label>
                <textarea id="quick-notes" name="quick-notes" rows="4"></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add to Tracker</button>
                <button type="button" class="btn btn-secondary close-quick-tracker">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="quick_tracker.js"></script>

<script src="race-card.js"></script>

    
</body>
</html>