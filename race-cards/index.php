<?php
// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

/**
 * Normalizes region names to standardized values
 * @param string $region The region name to normalize
 * @return string The normalized region name
 */
function normalizeRegion($region) {
    if (empty($region)) {
        return '';
    }
    
    $region = trim(strtoupper($region));
    
    // Map common variants
    $regionMap = [
        // UK variants
        'UNITED KINGDOM' => 'UK',
        'GREAT BRITAIN' => 'UK',
        'ENGLAND' => 'UK',
        'SCOTLAND' => 'UK',
        'WALES' => 'UK',
        'NORTHERN IRELAND' => 'UK',
        'GB' => 'UK',
        'GBR' => 'UK',
        
        // Ireland variants
        'IRE' => 'IRELAND',
        'EIRE' => 'IRELAND',
        'IRL' => 'IRELAND',
        
        // France variants
        'FR' => 'FRANCE'
    ];
    
    return isset($regionMap[$region]) ? $regionMap[$region] : $region;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Cards</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto+Mono&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to main.css instead of horse_tracker.css -->
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <link rel="stylesheet" href="css/racecard2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" />
    <link rel="stylesheet" href="css/region-filter.css">
    <link rel="stylesheet" href="css/bet-slip.css">
    <link rel="stylesheet" href="../test/horse-tracker-modal.css">

    
    
    <style>
   
    </style>
       
</head>
<body>
    <?php include 'race-data.php'; ?>

    <?php include '../test/app-header.php'; ?>


    <!-- Dynamic Date Navigation -->
    <div class="date-navigation">
        <div class="container">
            <div class="date-slider" id="dateSlider">
                <!-- Dates will be inserted here by JavaScript -->
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
                <button class="filter-button" data-region="ALL">
                    <i class="fas fa-globe"></i> All
                </button>
                <button class="filter-button active" data-region="UK">
                    <span class="flag-icon flag-icon-gb"></span> UK
                </button>
                <button class="filter-button" data-region="IRELAND">
                    <i class="flag-icon flag-icon-ie"></i> Ireland
                </button>
                <button class="filter-button" data-region="FRANCE">
                    <i class="flag-icon flag-icon-fr"></i> France
                </button>
            </div>
        </div>
    </div>

    <div class="container">
        <div id="race-container">
            <?php 
            foreach ($allCourses as $courseName => $courseData):
                // Count the number of races at this course
                // $raceCount = count($courseData['races']);
                // Get the region for this course - use the first race's region if available
                $region = '';
                if (!empty($courseData['races'])) {
                    // Get the first race (any race) from this course
                    $firstRace = reset($courseData['races']);
                    if (isset($firstRace['region'])) {
                        $region = $firstRace['region'];
                    }
                }
                // If still empty, fall back to course-level region
                if (empty($region)) {
                    $region = $courseData['region'] ?? '';
                }
                
                // Normalize the region value
                $region = normalizeRegion($region);
                
            ?>

                <!-- Course Container with region data attribute -->
                <div class="course-container" data-region="<?php echo htmlspecialchars($region); ?>">
                    <div class="course-header turf" data-action="Click to expand">
                        <h2>
                            <img class="course-logo"> <?php echo htmlspecialchars($courseName); ?>
                            <!-- <span class="race-count"><?php echo $raceCount; ?> races</span> -->
                        </h2>
                        <div class="toggle-icon">▶</div>
                    </div>
                    <div class="races-container" data-loaded="false">
                        <?php 
                        // Sort race times chronologically
                        $races = $courseData['races'];
                        ksort($races);
                        
                        foreach ($races as $time => $race): ?>
                            <!-- Race Card from JSON data -->
                            <div class="race-card">
                                <div class="race-status-badge status-upcoming">
                                    <i class="fas fa-clock"></i> Upcoming - DEMO
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
                    <h4 class="runner-name" onclick="openTinderRacecard(<?php echo $runner['horse_id']; ?>, <?php echo $race['race_id']; ?>)"><?php echo htmlspecialchars($runner['name']); ?></h4>
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
                <div class="data-row">
                    <div class="data-label">Horse ID:</div>
                    <div class="data-value"><?php echo htmlspecialchars($runner['horse_id']); ?></div>
                </div>
            </div>
            <div class="runner-footer">
    <div class="odds-display">
        <div class="data-value"><?php echo htmlspecialchars($runner['comment']); ?></div>
    </div>
    <div class="action-buttons">
        <button class="runner-action" 
            onclick="openQuickTracker(
                '<?php echo addslashes($runner['name']); ?>', 
                '<?php echo addslashes($runner['jockey']); ?>', 
                '<?php echo addslashes($runner['trainer']); ?>',
                '<?php echo addslashes($runner['horse_id']); ?>',
                '<?php echo addslashes($runner['silk_url']); ?>'
            )"
        >
            <i class="far fa-star"></i>
        </button>
        <button class="runner-action">
            <i class="fas fa-circle-plus"></i>
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
                                                        <div class="odds-display">5/1 - DEMO</div>
                                                    </td>
                                                    <td class="actions-col">
                                                        <div class="table-actions">
                                                        <button class="table-action-btn" onclick="openQuickTracker(
                                                     '<?php echo addslashes($runner['name']); ?>', 
                                                     '<?php echo addslashes($runner['jockey']); ?>', 
                                                     '<?php echo addslashes($runner['trainer']); ?>',
                                                     '<?php echo addslashes($runner['horse_id']); ?>',
                                                     '<?php echo addslashes($runner['silk_url']); ?>'
                                                    )">
                                                     <i class="far fa-star"></i></button>                                               
                                                    <button class="table-action-btn" title="Runner Details">
                                                                <i class="fas fa-circle-plus"></i>
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
                                            <i class="fas fa-chart-bar"></i> Stats - DEMO
                                        </button>
                                        <button class="race-action-btn">
                                            <i class="fas fa-history"></i> Past Results - DEMO
                                        </button>
                                    </div>
                                    <div class="action-group">
                                        <button class="race-action-btn primary">
                                            <i class="fas fa-play"></i> Livestream - DEMO
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

    <?php include '../test/bottom-nav.php'; ?>

    


    <!-- Horse Modal HTML Structure -->
<div id="horseModal" class="horse-modal">
        <div class="horse-modal-content">
            <div class="horse-modal-header">
                <h2><i class="fas fa-horse"></i> Horse Details</h2>
                <span class="horse-modal-close"><i class="fas fa-times"></i></span>
            </div>
            <div class="horse-modal-body">
                <form id="horseForm" method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="horse_name" class="required-field">Horse Name</label>
                            <input type="text" id="horse_name" name="horse_name" required placeholder="Enter horse name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="trainer" class="required-field">Trainer</label>
                            <input type="text" id="trainer" name="trainer" required placeholder="Enter trainer name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="4" placeholder="Enter any notes or comments about this horse"></textarea>
                        </div>
                    </div>

                    <!-- Additional Information (Optional Fields) -->
                    <div class="collapsible-section">
                        <div class="collapsible-header">
                            <h3>Additional Information</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapsible-content">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" id="age" name="age" min="1" max="30" placeholder="Horse age">
                                </div>
                                <div class="form-group">
                                    <label for="breed">Breed</label>
                                    <input type="text" id="breed" name="breed" placeholder="Horse breed">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <select id="color" name="color">
                                        <option value="">-- Select Color --</option>
                                        <option value="Bay">Bay</option>
                                        <option value="Black">Black</option>
                                        <option value="Chestnut">Chestnut</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Brown">Brown</option>
                                        <option value="White">White</option>
                                        <option value="Palomino">Palomino</option>
                                        <option value="Roan">Roan</option>
                                        <option value="Dun">Dun</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender">
                                        <option value="">-- Select Gender --</option>
                                        <option value="Colt">Colt</option>
                                        <option value="Filly">Filly</option>
                                        <option value="Gelding">Gelding</option>
                                        <option value="Mare">Mare</option>
                                        <option value="Stallion">Stallion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Comments Section -->
                    <div class="quick-comments-box">
                        <h3 class="quick-comments-title">Quick Notes</h3>
                        <div class="quick-comment-buttons">
                            <button type="button" class="quick-comment-btn" data-comment="Strong finish, showed good potential">Strong finish</button>
                            <button type="button" class="quick-comment-btn" data-comment="Started well but faded in the final stretch">Started well but faded</button>
                            <button type="button" class="quick-comment-btn" data-comment="Struggled with the going, consider different ground next time">Struggled with going</button>
                            <button type="button" class="quick-comment-btn" data-comment="Traveled well throughout the race">Traveled well</button>
                            <button type="button" class="quick-comment-btn" data-comment="Needs more distance, consider longer races">Needs more distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Better suited to shorter distances">Better at shorter distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Watch for next time, showed improvement">Watch for next time</button>
                            <button type="button" class="quick-comment-btn" data-comment="Add blinkers/headgear next time">Add blinkers next time</button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" id="cancelButton" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="submit" class="btn btn-submit">
                            <i class="fas fa-check"></i> Save Horse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    


<!-- Bet Slip Modal -->
<div id="bet-slip-modal" class="modal bet-slip-modal">
    <div class="modal-content bet-slip-content">
        <div class="modal-header">
            <h2>Bet Slip</h2>
            <span class="close-bet-slip">&times;</span>
        </div>
        <div class="bet-slip-details">
            <div class="bet-slip-race">
                <div class="bet-slip-label">Race:</div>
                <div id="bet-slip-course-time" class="bet-slip-value"></div>
            </div>
            <div class="bet-slip-selection">
                <div class="bet-slip-horse-container">
                    <div class="bet-slip-label">Horse:</div>
                    <div id="bet-slip-horse-name" class="bet-slip-value horse-name"></div>
                </div>
                <div class="bet-slip-jockey-container">
                    <div class="bet-slip-label">Jockey:</div>
                    <div id="bet-slip-jockey" class="bet-slip-value"></div>
                </div>
                <div class="bet-slip-trainer-container">
                    <div class="bet-slip-label">Trainer:</div>
                    <div id="bet-slip-trainer" class="bet-slip-value"></div>
                </div>
            </div>
            <div class="bet-slip-odds">
            <div class="bet-slip-label">Odds:</div>
            <input type="text" class="bet-slip-odds-input" placeholder="Enter odds">
            </div>
            <div class="bet-slip-stake">
                <div class="bet-slip-label">Stake (£):</div>
                <input type="number" class="bet-slip-stake-input" value="5" min="1">
            </div>
            <div class="bet-slip-returns">
           <div class="bet-slip-label">Returns:</div>
           <div class="bet-slip-value">£0.00</div>
            </div>
        </div>
        <div class="bet-slip-actions">
            <button type="button" class="btn btn-secondary close-modal">Cancel</button>
            <button type="button" class="btn btn-primary">Place Bet</button>
        </div>
    </div>
</div>

<!-- Add horse to tracker modal -->
<script src="../test/horse-tracker-modal.js"></script>


<script src="js/race-card.js"></script>


<script src="js/bet-slip.js"></script>

<!-- Region filter -->
<script src="js/region-filter.js"></script>


<script>
    function openTinderRacecard(runnerData, raceData) {
        console.log("Horse ID:", runnerData);
        console.log("Race ID:", raceData);
        
        // Open tinder-racecard.php with the runner and race data
        window.location.href = `puzzle-piece.php?runner=${runnerData}&race=${raceData}`;
    }
    
    // Update data-action attribute based on expanded state
    document.addEventListener('DOMContentLoaded', function() {
        const courseHeaders = document.querySelectorAll('.course-header');
        courseHeaders.forEach(header => {
            header.addEventListener('click', function() {
                // Update the tooltip text based on current state
                if (this.classList.contains('expanded')) {
                    this.setAttribute('data-action', 'Click to expand');
                } else {
                    this.setAttribute('data-action', 'Click to collapse');
                }
            });
        });
    });
   
    // Dynamic date 
    document.addEventListener('DOMContentLoaded', function() {
            const dateSlider = document.getElementById('dateSlider');
            
            // Get current date
            const today = new Date();
            
            // Create date items for 3 days before, today, and 3 days after
            for (let i = -3; i <= 3; i++) {
                const currentDate = new Date(today);
                currentDate.setDate(today.getDate() + i);
                
                const dateItem = document.createElement('div');
                dateItem.className = 'date-item' + (i === 0 ? ' active' : '');
                
                // Get day name (Mon, Tue, etc.)
                const dayName = document.createElement('div');
                dayName.className = 'day-name';
                dayName.textContent = currentDate.toLocaleDateString('en-US', { weekday: 'short' });
                
                // Get day number (1-31)
                const dayNumber = document.createElement('div');
                dayNumber.className = 'day-number';
                dayNumber.textContent = currentDate.getDate();
                
                // Get month name (Jan, Feb, etc.)
                const dayMonth = document.createElement('div');
                dayMonth.className = 'day-month';
                dayMonth.textContent = currentDate.toLocaleDateString('en-US', { month: 'short' });
                
                // Append all elements to the date item
                dateItem.appendChild(dayName);
                dateItem.appendChild(dayNumber);
                dateItem.appendChild(dayMonth);
                
                // Add click event to make date active
                dateItem.addEventListener('click', function() {
                    // Remove active class from all date items
                    document.querySelectorAll('.date-item').forEach(item => {
                        item.classList.remove('active');
                    });
                    
                    // Add active class to clicked date item
                    this.classList.add('active');
                });
                
                // Append date item to date slider
                dateSlider.appendChild(dateItem);
            }
        });
</script>



</body>
</html>