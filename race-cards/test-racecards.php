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
    <title>Horse Racing Cards - Redesigned</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto+Mono&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Link to main.css instead of horse_tracker.css -->
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <link rel="stylesheet" href="racecard2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css" />
       
</head>
<body>
    <?php include 'race-data.php'; ?>

    <?php include '../test/app-header.php'; ?>

    <!-- Date Navigation -->
    <div class="date-navigation">
        <div class="container">
            <div class="date-slider">
                <?php
                // Create date slider
                $today = strtotime('2025-04-23'); // Using the date from your content
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
                <span class="flag-icon flag-icon-gb"></span> UK
                </button>
                <button class="filter-button">
                    <i class="flag-icon flag-icon-ie"></i> Ireland
                </button>
                <button class="filter-button">
                    <i class="flag-icon flag-icon-fr"></i> France
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
                    <div class="course-header turf collapsed">
                        <h2><img class="course-logo"> <?php echo htmlspecialchars($courseName); ?></h2>
                        <div class="toggle-icon">▶</div>
                    </div>
                    <div class="races-container" style="display: none;">
                        <?php 
                        // Sort race times chronologically
                        $races = $courseData['races'];
                        ksort($races);
                        
                        foreach ($races as $time => $race): 
                            $raceId = str_replace(' ', '-', strtolower($courseName)) . '-' . str_replace(':', '', $time);
                        ?>
                            <!-- Race Card from JSON data -->
                            <div class="race-card" data-race-id="<?php echo $raceId; ?>">
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
                                
                                <!-- Race content will be loaded when the race is selected -->
                                <div class="race-content" data-loaded="false" style="display: none;">
                                    <!-- Content will be loaded here -->
                                    <div class="loading-indicator">
                                        <i class="fas fa-spinner fa-spin"></i> Loading race data...
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
                <div class="d-flex justify-between mt-20">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Templates for race content -->
    <template id="race-content-template">
        <div class="race-info">
            <div class="race-info-card">
                <div class="info-card-header">
                    <i class="fas fa-info-circle"></i> Race Details
                </div>
                <div class="info-card-content">
                    <strong>{{race_class}}</strong> - {{type}} | Ages: {{age_band}}<br>
                    Weather: {{weather}} | Going Detail: {{going_detailed}}
                </div>
            </div>
            <div class="race-info-card">
                <div class="info-card-header">
                    <i class="fas fa-trophy"></i> Prize Money
                </div>
                <div class="info-card-content">
                    Total: <span class="prize-money">{{prize}}</span><br>
                    Field Size: {{field_size}} runners
                </div>
            </div>
            <div class="race-info-card">
                <div class="info-card-header">
                    <i class="fas fa-user-friends"></i> Runners & Riders
                </div>
                <div class="info-card-content">
                    <strong>{{runner_count}} Declared Runners</strong><br>
                    Region: {{region}} | Stalls: {{stalls}}
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
            <!-- Runner cards will be inserted here dynamically -->
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
                    <!-- Runner rows will be inserted here dynamically -->
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
    </template>

    <template id="runner-card-template">
        <div class="runner-card">
            <div class="runner-header">
                <div class="runner-number">{{number}}</div>
                <div class="runner-name-container">
                    <h4 class="runner-name">{{name}}</h4>
                    <div class="form-figures">{{form}}</div>
                </div>
                <div class="silks-container">
                    <img src="{{silk_url}}" alt="Racing Silks" class="silks-image">
                </div>
            </div>
            <div class="runner-data">
                <div class="data-row">
                    <div class="data-label">Jockey:</div>
                    <div class="data-value jockey">{{jockey}}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Trainer:</div>
                    <div class="data-value">{{trainer}}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Age/Weight:</div>
                    <div class="data-value">{{age}}yrs | {{st}}-{{lb}}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Draw:</div>
                    <div class="data-value">{{draw}} of {{field_size}}</div>
                </div>
                <div class="data-row">
                    <div class="data-label">Horse ID:</div>
                    <div class="data-value">{{horse_id}}</div>
                </div>
            </div>
            <div class="runner-footer">
                <div class="odds-display">
                    <div class="data-value">{{comment}}</div>
                </div>
                <div class="action-buttons">
                    <button class="runner-action track-runner" data-name="{{name}}" data-jockey="{{jockey}}" data-trainer="{{trainer}}">
                        <i class="far fa-star"></i>
                    </button>
                    <button class="runner-action">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <template id="runner-row-template">
        <tr class="runner-row">
            <td class="number-col">{{number}}</td>
            <td class="draw-col">
                <div class="draw-indicator">{{draw}}</div>
            </td>
            <td class="silks-col">
                <img src="{{silk_url}}" alt="Racing Silks" class="table-silks">
            </td>
            <td class="horse-col">
                <div class="horse-name">{{name}}</div>
            </td>
            <td class="form-col">
                <div class="form-display">{{form}}</div>
            </td>
            <td class="jockey-col">{{jockey}}</td>
            <td class="trainer-col">{{trainer}}</td>
            <td class="age-col">{{age}}</td>
            <td class="weight-col">{{st}}-{{lb}}</td>
            <td class="odds-col">
                <div class="odds-display">5/1</div>
            </td>
            <td class="actions-col">
                <div class="table-actions">
                    <button class="table-action-btn track-runner" data-name="{{name}}" data-jockey="{{jockey}}" data-trainer="{{trainer}}">
                        <i class="far fa-star"></i>
                    </button>
                    <button class="table-action-btn" title="Runner Details">
                        <i class="fas fa-info-circle"></i>
                    </button>
                </div>
            </td>
        </tr>
    </template>

    <script src="quick_tracker.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Course header toggle
        document.querySelectorAll('.course-header').forEach(header => {
            header.addEventListener('click', function() {
                const racesContainer = this.nextElementSibling;
                const isCollapsed = this.classList.contains('collapsed');
                
                // Toggle collapsed class
                this.classList.toggle('collapsed');
                this.classList.toggle('expanded');
                
                // Update toggle icon
                const toggleIcon = this.querySelector('.toggle-icon');
                toggleIcon.textContent = isCollapsed ? '▼' : '▶';
                
                // Show/hide races container
                if (isCollapsed) {
                    racesContainer.style.display = 'block';
                } else {
                    racesContainer.style.display = 'none';
                }
            });
        });
        
        // Race card selection
        document.querySelectorAll('.race-card').forEach(raceCard => {
            raceCard.addEventListener('click', function() {
                const raceId = this.getAttribute('data-race-id');
                const raceContent = this.querySelector('.race-content');
                const isLoaded = raceContent.getAttribute('data-loaded') === 'true';
                
                // Toggle display of race content
                if (raceContent.style.display === 'none') {
                    raceContent.style.display = 'block';
                    
                    // Load content if not already loaded
                    if (!isLoaded) {
                        loadRaceContent(raceId, raceContent);
                    }
                } else {
                    raceContent.style.display = 'none';
                }
            });
        });
        
        // View toggle functionality
        document.addEventListener('click', function(e) {
            if (e.target.closest('.toggle-button')) {
                const button = e.target.closest('.toggle-button');
                const viewToggle = button.closest('.view-toggle');
                const isCardView = button.querySelector('i').classList.contains('fa-th');
                
                // Update active button
                viewToggle.querySelectorAll('.toggle-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                button.classList.add('active');
                
                // Show/hide the appropriate view
                const raceContent = viewToggle.closest('.race-content');
                const runnersGrid = raceContent.querySelector('.runners-grid');
                const runnersTable = raceContent.querySelector('.runners-table');
                
                if (isCardView) {
                    runnersGrid.style.display = 'grid';
                    runnersTable.style.display = 'none';
                } else {
                    runnersGrid.style.display = 'none';
                    runnersTable.style.display = 'block';
                }
            }
        });
        
        // Quick tracker functionality for dynamically loaded elements
        document.addEventListener('click', function(e) {
            if (e.target.closest('.track-runner')) {
                const button = e.target.closest('.track-runner');
                const horseName = button.getAttribute('data-name');
                const jockey = button.getAttribute('data-jockey');
                const trainer = button.getAttribute('data-trainer');
                
                openQuickTracker(horseName, jockey, trainer);
            }
        });
    });

    // Function to load race content
    function loadRaceContent(raceId, raceContent) {
    fetch(`./test-get-race-data.php?race_id=${raceId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Race data not found');
            }
            return response.json();
        })
        .then(raceData => {
            populateRaceContent(raceData, raceContent);
            raceContent.setAttribute('data-loaded', 'true');
        })
        .catch(error => {
            console.error('Error loading race data:', error);
            raceContent.innerHTML = '<div class="error-message">Unable to load race data.</div>';
        });
}


    // Function to populate race content with fetched data
    function populateRaceContent(raceData, raceContent) {
    const template = document.getElementById('race-content-template');
    const clone = template.content.cloneNode(true);

    // Fill in race info
    let html = clone.querySelector('.race-info').innerHTML;
    html = html.replace('{{race_class}}', raceData.race_class || 'N/A')
               .replace('{{type}}', raceData.type || 'N/A')
               .replace('{{age_band}}', raceData.age_band || 'N/A')
               .replace('{{weather}}', raceData.weather || 'N/A')
               .replace('{{going_detailed}}', raceData.going_detailed || 'N/A')
               .replace('{{prize}}', raceData.prize || 'N/A')
               .replace('{{field_size}}', raceData.runners.length || '0')
               .replace('{{runner_count}}', raceData.runners.length || '0')
               .replace('{{region}}', raceData.region || 'N/A')
               .replace('{{stalls}}', raceData.stalls_position || 'N/A');
    clone.querySelector('.race-info').innerHTML = html;

    // Fill in runners
    const runnersGrid = clone.querySelector('.runners-grid');
    const runnersTableBody = clone.querySelector('.runners-table tbody');
    
    const cardTemplate = document.getElementById('runner-card-template').innerHTML;
    const rowTemplate = document.getElementById('runner-row-template').innerHTML;

    raceData.runners.forEach(runner => {
        let cardHtml = cardTemplate
            .replace(/{{number}}/g, runner.number || '0')
            .replace(/{{name}}/g, runner.name || '')
            .replace(/{{form}}/g, runner.form || '-')
            .replace(/{{silk_url}}/g, runner.silk_url || '')
            .replace(/{{jockey}}/g, runner.jockey || '')
            .replace(/{{trainer}}/g, runner.trainer || '')
            .replace(/{{age}}/g, runner.age || '-')
            .replace(/{{st}}/g, runner.st || '-')
            .replace(/{{lb}}/g, runner.lb || '-')
            .replace(/{{draw}}/g, runner.draw || '-')
            .replace(/{{field_size}}/g, raceData.runners.length || '0')
            .replace(/{{horse_id}}/g, runner.horse_id || '')
            .replace(/{{comment}}/g, runner.comment || '');

        let rowHtml = rowTemplate
            .replace(/{{number}}/g, runner.number || '0')
            .replace(/{{draw}}/g, runner.draw || '-')
            .replace(/{{silk_url}}/g, runner.silk_url || '')
            .replace(/{{name}}/g, runner.name || '')
            .replace(/{{form}}/g, runner.form || '-')
            .replace(/{{jockey}}/g, runner.jockey || '')
            .replace(/{{trainer}}/g, runner.trainer || '')
            .replace(/{{age}}/g, runner.age || '-')
            .replace(/{{st}}/g, runner.st || '-')
            .replace(/{{lb}}/g, runner.lb || '-');

        runnersGrid.insertAdjacentHTML('beforeend', cardHtml);
        runnersTableBody.insertAdjacentHTML('beforeend', rowHtml);
    });

    raceContent.innerHTML = '';
    raceContent.appendChild(clone);
}

    // Function to open quick tracker
    function openQuickTracker(horseName, jockey, trainer) {
        const modal = document.getElementById('quick-tracker-modal');
        const horseNameField = document.getElementById('quick-horse-name');
        const trainerField = document.getElementById('quick-trainer');
        
        horseNameField.value = horseName || '';
        trainerField.value = trainer || '';
        
        modal.style.display = 'block';
    }
    </script>
    
    <!-- Implement the existing quick_tracker.js functionality -->
    <script>
    // Close modal functionality
    document.querySelector('.close-quick-tracker').addEventListener('click', function() {
        document.getElementById('quick-tracker-modal').style.display = 'none';
    });
    
    document.querySelector('.close-modal').addEventListener('click', function() {
        document.getElementById('quick-tracker-modal').style.display = 'none';
    });
    
    // Quick tracker form submission
    document.getElementById('quick-tracker-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Here you would handle saving the tracker data
        
        // Close the modal
        document.getElementById('quick-tracker-modal').style.display = 'none';
        
        // Show success notification
        alert('Horse added to tracker successfully!');
    });
    </script>
</body>
</html>