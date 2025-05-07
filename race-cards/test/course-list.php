<?php
// includes/course-list.php
// Displays the list of courses for the current date

// Check if we have race data
if (!$hasRaceData): ?>
    <div class="no-races-container">
        <div class="no-races-message">
            <i class="fas fa-calendar-times"></i>
            <h3>No races available for <?php echo date('l, j F Y', strtotime($requestedDate)); ?></h3>
            <p>Please select another date or check back later.</p>
        </div>
    </div>
<?php else: ?>

<div id="course-list-container">
    <?php foreach ($allCourses as $courseName => $courseData):
        // Count the number of races at this course
        $raceCount = count($courseData['races']);
        
        // Get the region for this course
        $region = normalizeRegion($courseData['region'] ?? '');
        
        // Get surface type (default to 'turf')
        $surfaceType = 'turf'; // You could extract this from the data if available
        
        // Get first race time and last race time
        $raceTimes = array_keys($courseData['races']);
        $firstRaceTime = reset($raceTimes);
        $lastRaceTime = end($raceTimes);
    ?>
    <div class="course-container" data-region="<?php echo htmlspecialchars($region); ?>">
        <div class="course-header <?php echo htmlspecialchars($surfaceType); ?>">
            <div class="course-header-main">
                <h2 class="course-name">
                    <?php echo htmlspecialchars($courseName); ?>
                </h2>
                <div class="course-meta">
                    <span class="race-count"><?php echo $raceCount; ?> races</span>
                    <span class="course-schedule"><?php echo htmlspecialchars($firstRaceTime); ?> - <?php echo htmlspecialchars($lastRaceTime); ?></span>
                </div>
            </div>
            <div class="toggle-icon" aria-label="Toggle course details">
                <i class="fas fa-chevron-down"></i>
            </div>
        </div>
        
        <div class="course-content" style="display: none;">
            <!-- Race times slider will be inserted here -->
            <div class="race-times-slider">
                <div class="race-times-container">
                    <?php foreach ($courseData['races'] as $time => $race): ?>
                        <div class="race-time-item" data-time="<?php echo htmlspecialchars($time); ?>">
                            <div class="race-time"><?php echo htmlspecialchars($time); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Race details container -->
            <div class="races-container" data-loaded="false">
                <?php foreach ($courseData['races'] as $time => $race): ?>
                    <div class="race-details" data-time="<?php echo htmlspecialchars($time); ?>" style="display: none;">
                        <!-- Race card header -->
                        <div class="race-card-header">
                            <div class="race-header-left">
                                <h3 class="race-name"><?php echo htmlspecialchars($race['race_name']); ?></h3>
                                <div class="race-classifications">
                                    <span class="race-class"><?php echo htmlspecialchars($race['race_class']); ?></span>
                                    <span class="race-type"><?php echo htmlspecialchars($race['type']); ?></span>
                                    <span class="race-age-band"><?php echo htmlspecialchars($race['age_band']); ?></span>
                                </div>
                            </div>
                            <div class="race-header-right">
                                <div class="race-conditions">
                                    <span class="race-distance"><i class="fas fa-ruler-horizontal"></i> <?php echo htmlspecialchars($race['distance']); ?></span>
                                    <span class="race-surface"><i class="fas fa-leaf"></i> <?php echo htmlspecialchars($race['surface']); ?></span>
                                    <span class="race-going"><i class="fas fa-water"></i> <?php echo htmlspecialchars($race['going']); ?></span>
                                </div>
                                <div class="race-prize">
                                    <i class="fas fa-trophy"></i> <?php echo htmlspecialchars($race['prize']); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Toggle for runners -->
                        <div class="show-runners-toggle">
                            <button class="show-runners-btn" data-time="<?php echo htmlspecialchars($time); ?>">
                                <i class="fas fa-horse"></i> 
                                <span class="toggle-text">Show Runners</span>
                                <span class="runner-count">(<?php echo count($race['runners']); ?>)</span>
                            </button>
                        </div>
                        
                        <!-- Runners grid (hidden by default) -->
                        <div class="runners-container" data-time="<?php echo htmlspecialchars($time); ?>" style="display: none;">
                            <!-- Will be populated by AJAX or shown/hidden by JavaScript -->
                            <div class="runners-grid">
                                <?php foreach ($race['runners'] as $runner): ?>
                                    <div class="runner-card" data-horse-id="<?php echo htmlspecialchars($runner['horse_id']); ?>">
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
                                            <div class="runner-comment">
                                                <?php echo htmlspecialchars($runner['comment']); ?>
                                            </div>
                                            <div class="action-buttons">
                                                <button class="runner-action track-horse-btn" 
                                                    data-horse-name="<?php echo htmlspecialchars($runner['name']); ?>"
                                                    data-jockey="<?php echo htmlspecialchars($runner['jockey']); ?>"
                                                    data-trainer="<?php echo htmlspecialchars($runner['trainer']); ?>">
                                                    <i class="far fa-star"></i> Track
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php endif; ?>