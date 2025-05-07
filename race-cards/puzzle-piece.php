<?php
// Get horse_id and race_id from URL
$horseId = isset($_GET['runner']) ? $_GET['runner'] : null;
$raceId = isset($_GET['race']) ? $_GET['race'] : null;

// Initialize variables
$runnerData = null;
$raceData = null;
$errorMessage = null;
$successMessage = null;
$prevHorseId = null;
$nextHorseId = null;
$horseIndex = -1;

// Path to JSON file
$jsonFilePath = '../racecards/assets/js/2025-05-07.json';

// Set error handling
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors from users but log them
set_time_limit(30); // Set a reasonable time limit

try {
    if (file_exists($jsonFilePath)) {
        // Read and decode the JSON file
        $jsonData = file_get_contents($jsonFilePath);
        $racingData = json_decode($jsonData, true);
        
        if ($racingData === null) {
            throw new Exception("Error decoding JSON data: " . json_last_error_msg());
        }
        
        if ($horseId && $raceId) {
            // First priority: Find the race by ID
            foreach ($racingData as $region => $courses) {
                if (!is_array($courses)) continue;
                
                foreach ($courses as $course => $races) {
                    if (!is_array($races)) continue;
                    
                    foreach ($races as $time => $race) {
                        if (!is_array($race) || !isset($race['runners']) || !is_array($race['runners'])) continue;
                        
                        if (isset($race['race_id']) && (string)$race['race_id'] === (string)$raceId) {
                            $raceData = $race;
                            $raceData['time'] = $time; // Save the race time
                            $raceData['course'] = $course; // Save the course name
                            
                            // Now find the current horse and determine prev/next
                            $runnersCount = count($race['runners']);
                            
                            for ($i = 0; $i < $runnersCount; $i++) {
                                $runner = $race['runners'][$i];
                                
                                if (isset($runner['horse_id']) && (string)$runner['horse_id'] === (string)$horseId) {
                                    $runnerData = $runner;
                                    $horseIndex = $i;
                                    
                                    // Determine previous horse
                                    if ($i > 0) {
                                        $prevHorseId = $race['runners'][$i - 1]['horse_id'];
                                    } else {
                                        $prevHorseId = $race['runners'][$runnersCount - 1]['horse_id']; // Loop to last
                                    }
                                    
                                    // Determine next horse
                                    if ($i < $runnersCount - 1) {
                                        $nextHorseId = $race['runners'][$i + 1]['horse_id'];
                                    } else {
                                        $nextHorseId = $race['runners'][0]['horse_id']; // Loop to first
                                    }
                                    
                                    break;
                                }
                            }
                            
                            // If we found the race but not the horse, use the first horse
                            if (!$runnerData && !empty($race['runners'])) {
                                $runnerData = $race['runners'][0];
                                $horseIndex = 0;
                                
                                if ($runnersCount > 1) {
                                    $prevHorseId = $race['runners'][$runnersCount - 1]['horse_id']; // Last horse
                                    $nextHorseId = $race['runners'][1]['horse_id']; // Second horse
                                } else {
                                    $prevHorseId = $nextHorseId = $runnerData['horse_id']; // Only one horse
                                }
                            }
                            
                            break 3; // Exit all loops once race is found
                        }
                    }
                }
            }
            
            // If we didn't find the race or horse, search for the horse in any race
            if (!$runnerData) {
                foreach ($racingData as $region => $courses) {
                    if (!is_array($courses)) continue;
                    
                    foreach ($courses as $course => $races) {
                        if (!is_array($races)) continue;
                        
                        foreach ($races as $time => $race) {
                            if (!is_array($race) || !isset($race['runners']) || !is_array($race['runners'])) continue;
                            
                            for ($i = 0; $i < count($race['runners']); $i++) {
                                $runner = $race['runners'][$i];
                                
                                if (isset($runner['horse_id']) && (string)$runner['horse_id'] === (string)$horseId) {
                                    $runnerData = $runner;
                                    $raceData = $race;
                                    $raceData['time'] = $time; // Save the race time
                                    $raceData['course'] = $course; // Save the course name
                                    $horseIndex = $i;
                                    
                                    // Determine prev/next horses
                                    $runnersCount = count($race['runners']);
                                    
                                    if ($i > 0) {
                                        $prevHorseId = $race['runners'][$i - 1]['horse_id'];
                                    } else {
                                        $prevHorseId = $race['runners'][$runnersCount - 1]['horse_id'];
                                    }
                                    
                                    if ($i < $runnersCount - 1) {
                                        $nextHorseId = $race['runners'][$i + 1]['horse_id'];
                                    } else {
                                        $nextHorseId = $race['runners'][0]['horse_id'];
                                    }
                                    
                                    break 4; // Exit all loops once found
                                }
                            }
                        }
                    }
                }
            }
        }
        
        // Last resort: Use first available horse and race if nothing was found
        if (!$runnerData || !$raceData) {
            foreach ($racingData as $region => $courses) {
                if (!is_array($courses)) continue;
                
                foreach ($courses as $course => $races) {
                    if (!is_array($races)) continue;
                    
                    foreach ($races as $time => $race) {
                        if (!is_array($race)) continue;
                        
                        $raceData = $race;
                        $raceData['time'] = $time; // Save the race time
                        $raceData['course'] = $course; // Save the course name
                        
                        if (isset($race['runners']) && !empty($race['runners'])) {
                            $runnerData = $race['runners'][0];
                            $horseIndex = 0;
                            
                            $runnersCount = count($race['runners']);
                            if ($runnersCount > 1) {
                                $prevHorseId = $race['runners'][$runnersCount - 1]['horse_id']; // Last horse
                                $nextHorseId = $race['runners'][1]['horse_id']; // Second horse
                            } else {
                                $prevHorseId = $nextHorseId = $runnerData['horse_id']; // Only one horse
                            }
                            
                            break 3;
                        }
                    }
                }
            }
        }
    } else {
        $errorMessage = "JSON file not found at: {$jsonFilePath}";
    }
} catch (Exception $e) {
    $errorMessage = "Error: " . $e->getMessage();
    error_log("Error in puzzle-piece.php: " . $e->getMessage());
}

// Function to get full sex name
function getSexFull($sexCode) {
    $sexMap = [
        'C' => 'Colt',
        'F' => 'Filly',
        'G' => 'Gelding',
        'H' => 'Horse',
        'M' => 'Mare'
    ];
    return isset($sexMap[$sexCode]) ? $sexMap[$sexCode] : $sexCode;
}

// Function to format weight
function formatWeight($lbs) {
    if (!is_numeric($lbs)) return 'n/a';
    return floor($lbs/14) . 'st ' . ($lbs % 14) . 'lb';
}

// Function to get draw advantage
function getDrawAdvantage($drawNumber, $totalRunners, $distance) {
    if (!is_numeric($drawNumber) || !is_numeric($totalRunners)) return 'n/a';
    
    $position = $drawNumber / $totalRunners;
    
    if (stripos($distance, 'mile') !== false || stripos($distance, '8f') !== false) {
        if ($position < 0.3) return 'Inside draw - may need to save ground';
        if ($position > 0.7) return 'Outside draw - needs to avoid going wide';
        return 'Ideal middle draw position';
    } elseif (stripos($distance, '5f') !== false || stripos($distance, '6f') !== false) {
        if ($position < 0.3) return 'Inside draw - quick break needed';
        if ($position > 0.7) return 'Outside draw - clear run likely';
        return 'Middle draw - will need racing room';
    } else {
        return 'Draw less significant over this distance';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Puzzle Piece - <?php echo isset($runnerData['name']) ? htmlspecialchars($runnerData['name']) : 'Horse Details'; ?></title>
  <link rel="stylesheet" href="css/puzzle-piece.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    
  </style>
</head>
<body>
  <div class="page-layout">
    <!-- Left Sidebar: Current Race Shortlist -->
    <div class="sidebar" id="leftSidebar">
      <div class="sidebar-header">
        <i class="fas fa-star"></i> My Shortlist
      </div>
      
      <?php if ($raceData): ?>
      <div class="shortlist-section">
        <div class="shortlist-title">
          <div><?php echo htmlspecialchars($raceData['course'] ?? ''); ?></div>
          <div class="race-time"><?php echo htmlspecialchars($raceData['time'] ?? ''); ?></div>
        </div>
        
        <div class="shortlist-items" id="shortlistItems">
          <!-- Shortlisted horses will appear here via JavaScript -->
          <div class="shortlist-empty">Click the star icon on a horse to add it to your shortlist</div>
        </div>
      </div>
      <?php endif; ?>
      
      <div style="margin-top: auto; padding-top: 20px; opacity: 0.7; font-size: 13px; text-align: center;">
        Tap a horse in your shortlist to view its details
      </div>
    </div>
    
    <!-- Mobile sidebar toggle -->
    <button class="sidebar-toggle" id="sidebarToggle">
      <i class="fas fa-list"></i>
    </button>
    
    <!-- Main Content -->
    <div class="app-container">
    <div class="app-header">
        <div style="display: flex; align-items: center;">
          <button class="back-button" onclick="goBack()">←</button>
          <div class="app-logo">Puzzle<span>Piece</span></div>
        </div>
        <div class="header-icons">
          <div><i class="fas fa-horse"></i></div>
          <div><i class="fas fa-crown"></i></div>
        </div>
      </div>
      
      <?php if ($errorMessage): ?>
      <div class="notification error">
        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($errorMessage); ?>
      </div>
      <?php endif; ?>
      
      <?php if ($raceData): ?>
      <div id="race-info" class="race-info">
        <div class="race-title"><?php echo htmlspecialchars($raceData['race_name'] ?? 'Race Details'); ?></div>
        <div class="race-details">
          <div><?php echo htmlspecialchars($raceData['course'] ?? ''); ?>, <?php echo htmlspecialchars($raceData['region'] ?? ''); ?> • <?php echo htmlspecialchars($raceData['time'] ?? ''); ?></div>
          <div><?php echo htmlspecialchars($raceData['distance'] ?? $raceData['distance_round'] ?? ''); ?> • <?php echo htmlspecialchars($raceData['going'] ?? ''); ?> • <?php echo htmlspecialchars($raceData['prize'] ?? ''); ?></div>
        </div>
        <?php if (isset($raceData['pattern']) || isset($raceData['race_class'])): ?>
        <div class="race-class">
          <?php 
          $classInfo = [];
          if (!empty($raceData['pattern'])) $classInfo[] = $raceData['pattern'];
          if (!empty($raceData['race_class'])) $classInfo[] = $raceData['race_class'];
          echo htmlspecialchars(implode(' • ', $classInfo));
          ?>
        </div>
        <?php endif; ?>
      </div>
      
      <?php if (isset($raceData['runners']) && count($raceData['runners']) > 0): ?>
      <!-- Navigation indicator -->
      <div class="navigation-indicator">
          <?php for ($i = 0; $i < count($raceData['runners']); $i++): ?>
              <div class="indicator-dot <?php echo ($i == $horseIndex) ? 'active' : ''; ?>"></div>
          <?php endfor; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
      
      <?php if ($runnerData): ?>
      <!-- New Featured Silks Section -->
      <div class="puzzle-container">
        <!-- Shortlist button -->
        <button class="shortlist-btn" id="shortlistBtn" data-horse-id="<?php echo htmlspecialchars($runnerData['horse_id']); ?>" data-horse-name="<?php echo htmlspecialchars($runnerData['name']); ?>" data-jockey="<?php echo htmlspecialchars($runnerData['jockey']); ?>">
          <i class="far fa-star" id="shortlistIcon"></i>
        </button>
        
        <div class="silk-featured">
          <?php if (!empty($runnerData['silk_url'])): ?>
          <img src="<?php echo htmlspecialchars($runnerData['silk_url']); ?>" alt="Racing Silks">
          <?php else: ?>
          <div style="font-size: 100px; color: #ddd;"><i class="fas fa-tshirt"></i></div>
          <?php endif; ?>
        </div>
        
        <!-- Puzzle Piece Overlay Pattern -->
        <div class="puzzle-overlay">
          <div class="puzzle-piece" style="width: 80px; height: 80px; top: -15px; left: -15px; transform: rotate(15deg);"></div>
          <div class="puzzle-piece" style="width: 60px; height: 60px; bottom: 30px; right: -20px; transform: rotate(-10deg);"></div>
          <div class="puzzle-piece" style="width: 40px; height: 40px; top: 50px; right: 40px; transform: rotate(20deg);"></div>
          <div class="puzzle-piece" style="width: 50px; height: 50px; bottom: -10px; left: 100px; transform: rotate(-15deg);"></div>
        </div>
        
        <!-- Horse Details Overlay -->
        <div class="horse-details">
          <h2><?php echo htmlspecialchars($runnerData['name'] ?? 'Horse Name'); ?></h2>
          <div>
            <?php if (!empty($runnerData['headgear'])): ?>
            <span style="background: rgba(255,255,255,0.2); padding: 2px 8px; border-radius: 10px; font-size: 12px; margin-right: 5px;">
              <?php echo strtoupper(htmlspecialchars($runnerData['headgear'])); ?>
            </span>
            <?php endif; ?>
            <span><?php echo htmlspecialchars($runnerData['jockey'] ?? ''); ?></span>
          </div>
        </div>
      </div>
      
      <!-- Puzzle Stats Grid -->
      <div class="puzzle-stats">
        <div class="stat-box">
          <div class="stat-label">Number</div>
          <div class="stat-value"><?php echo htmlspecialchars($runnerData['number'] ?? 'n/a'); ?></div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Age/Sex</div>
          <div class="stat-value"><?php echo htmlspecialchars($runnerData['age'] ?? ''); ?>yo <?php echo getSexFull($runnerData['sex_code'] ?? ''); ?></div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Form</div>
          <div class="stat-value"><?php echo htmlspecialchars($runnerData['form'] ?? 'n/a'); ?></div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Weight</div>
          <div class="stat-value"><?php echo isset($runnerData['lbs']) ? formatWeight($runnerData['lbs']) : 'n/a'; ?></div>
        </div>
      </div>
      
      <!-- Trainer Info -->
      <div class="performance-grid">
        <div class="perf-item">
          <div class="perf-label">Trainer</div>
          <div class="perf-value" style="font-size: 16px;"><?php echo htmlspecialchars($runnerData['trainer'] ?? 'n/a'); ?></div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Rating</div>
          <div class="perf-value"><?php echo htmlspecialchars($runnerData['ofr'] ?? 'n/a'); ?></div>
        </div>
      </div>
      
      <!-- Performance Indicators -->
      <div class="section-title">
        <i class="fas fa-chart-line"></i> Performance Indicators
      </div>
      
      <div class="performance-grid">
        <div class="perf-item">
          <div class="perf-label">Speed</div>
          <div class="perf-value">75%</div>
          <div class="perf-bar">
            <div class="perf-fill" style="width: 75%"></div>
          </div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Stamina</div>
          <div class="perf-value">82%</div>
          <div class="perf-bar">
            <div class="perf-fill" style="width: 82%"></div>
          </div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Course Form</div>
          <div class="perf-value">60%</div>
          <div class="perf-bar">
            <div class="perf-fill" style="width: 60%"></div>
          </div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Going Preference</div>
          <div class="perf-value">91%</div>
          <div class="perf-bar">
            <div class="perf-fill" style="width: 91%"></div>
          </div>
        </div>
      </div>
      
      <!-- Expert Analysis -->
      <?php if (!empty($runnerData['spotlight']) || !empty($runnerData['comment'])): ?>
      <div class="section-title">
        <i class="fas fa-search"></i> Expert Analysis
      </div>
      
      <div class="analysis">
        <?php 
        if (!empty($runnerData['spotlight'])) {
            echo htmlspecialchars($runnerData['spotlight']);
        } elseif (!empty($runnerData['comment'])) {
            echo htmlspecialchars($runnerData['comment']);
        }
        ?>
      </div>
      <?php endif; ?>
      
      <!-- Race History -->
      <div class="section-title">
        <i class="fas fa-history"></i> Recent Form
      </div>
      
      <div class="history-container">
        <div class="history-header">
          Past Performances
        </div>
        <?php
        // Example race history (in a real app, this would come from the database)
        $historyItems = [
            ['date' => '12 Apr 2025', 'course' => 'Kempton (AW)', 'result' => '3rd', 'class' => 'place'],
            ['date' => '24 Mar 2025', 'course' => 'Lingfield (AW)', 'result' => '2nd', 'class' => 'place'],
            ['date' => '03 Mar 2025', 'course' => 'Southwell (AW)', 'result' => '1st', 'class' => 'win']
        ];
        
        foreach ($historyItems as $item): 
        ?>
        <div class="history-item">
            <div class="history-date"><?php echo htmlspecialchars($item['date']); ?></div>
            <div class="history-course"><?php echo htmlspecialchars($item['course']); ?></div>
            <div class="history-result <?php echo $item['class']; ?>"><?php echo htmlspecialchars($item['result']); ?></div>
        </div>
        <?php endforeach; ?>
      </div>
      
      <!-- Additional Details -->
      <div class="section-title">
        <i class="fas fa-info-circle"></i> Additional Details
      </div>
      
      <div class="performance-grid">
        <div class="perf-item">
          <div class="perf-label">Draw</div>
          <div class="perf-value"><?php echo htmlspecialchars($runnerData['draw'] ?? 'n/a'); ?> of <?php echo htmlspecialchars($raceData['field_size'] ?? '?'); ?></div>
          <div style="font-size: 11px; color: #666; margin-top: 5px;">
            <?php echo htmlspecialchars(getDrawAdvantage(
              $runnerData['draw'] ?? 0, 
              $raceData['field_size'] ?? 0, 
              $raceData['distance'] ?? ''
            )); ?>
          </div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Days Since Run</div>
          <div class="perf-value"><?php echo htmlspecialchars($runnerData['last_run'] ?? 'n/a'); ?></div>
          <div style="font-size: 11px; color: #666; margin-top: 5px;">
            <?php 
            $lastRun = $runnerData['last_run'] ?? 0;
            if ($lastRun == 0) echo 'First run';
            elseif ($lastRun < 7) echo 'Quick turnaround';
            elseif ($lastRun < 21) echo 'Typical spacing';
            elseif ($lastRun < 60) echo 'Well rested';
            else echo 'Fresh/returning from break';
            ?>
          </div>
        </div>
      </div>
      
      <?php if (!empty($runnerData['medical'])): ?>
      <div class="section-title">
        <i class="fas fa-heartbeat"></i> Medical History
      </div>
      
      <div class="analysis" style="background: #fff5f5;">
        <?php
        $medicalInfo = [];
        foreach ($runnerData['medical'] as $medical) {
          $medicalInfo[] = $medical['type'] . ' (' . $medical['date'] . ')';
        }
        echo htmlspecialchars(implode(', ', $medicalInfo));
        ?>
      </div>
      <?php endif; ?>
      
      <!-- Pedigree Info -->
      <div class="section-title">
        <i class="fas fa-dna"></i> Pedigree
      </div>
      
      <div class="performance-grid">
        <div class="perf-item">
          <div class="perf-label">Sire</div>
          <div class="perf-value" style="font-size: 16px;"><?php echo htmlspecialchars($runnerData['sire'] ?? 'n/a'); ?></div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Dam</div>
          <div class="perf-value" style="font-size: 16px;"><?php echo htmlspecialchars($runnerData['dam'] ?? 'n/a'); ?></div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Color</div>
          <div class="perf-value" style="font-size: 16px;"><?php echo htmlspecialchars($runnerData['colour'] ?? 'n/a'); ?></div>
        </div>
        <div class="perf-item">
          <div class="perf-label">Origin</div>
          <div class="perf-value" style="font-size: 16px;"><?php echo htmlspecialchars($runnerData['region'] ?? 'n/a'); ?></div>
        </div>
      </div>
      
      <!-- Action Buttons with Navigation -->
      <div class="action-buttons">
        <button class="action-button dislike" onclick="navigateToPrevHorse()">
          <i class="fas fa-times"></i>
        </button>
        <button class="action-button favorite" onclick="goBack()">
          <i class="fas fa-reply"></i>
        </button>
        <button class="action-button like" onclick="navigateToNextHorse()">
          <i class="fas fa-heart"></i>
        </button>
      </div>
      
      <?php else: ?>
      <!-- Placeholder when no horse data is available -->
      <div class="puzzle-container">
        <div class="silk-featured">
          <div style="font-size: 100px; color: #ddd;"><i class="fas fa-tshirt"></i></div>
        </div>
        
        <div class="puzzle-overlay">
          <div class="puzzle-piece" style="width: 80px; height: 80px; top: -15px; left: -15px; transform: rotate(15deg);"></div>
          <div class="puzzle-piece" style="width: 60px; height: 60px; bottom: 30px; right: -20px; transform: rotate(-10deg);"></div>
          <div class="puzzle-piece" style="width: 40px; height: 40px; top: 50px; right: 40px; transform: rotate(20deg);"></div>
          <div class="puzzle-piece" style="width: 50px; height: 50px; bottom: -10px; left: 100px; transform: rotate(-15deg);"></div>
        </div>
        
        <div class="horse-details">
          <h2>Horse Data Not Available</h2>
          <div>No jockey/trainer information</div>
        </div>
      </div>
      
      <div class="analysis">
        <p><i class="fas fa-exclamation-triangle"></i> No horse data was found for ID: <?php echo htmlspecialchars($horseId); ?></p>
        <p>Please check that the horse ID exists in the database or try refreshing the page.</p>
      </div>
      
      <div class="action-buttons">
        <button class="action-button dislike" onclick="goBack()">
          <i class="fas fa-times"></i>
        </button>
      </div>
      <?php endif; ?>
    </div>
  </div>
  
  <div class="loader" id="loader">
    <div class="loader-inner"></div>
  </div>

  <script>
    // Store navigation IDs
    const prevHorseId = '<?php echo $prevHorseId; ?>';
    const nextHorseId = '<?php echo $nextHorseId; ?>';
    const currentRaceId = '<?php echo $raceId; ?>';
    const currentHorseId = '<?php echo $horseId; ?>';
    let isNavigating = false;
    
    // Shortlist management
    class ShortlistManager {
      constructor() {
        this.shortlistKey = `shortlist_${currentRaceId}`;
        this.shortlistedHorses = this.loadShortlist();
        this.updateUI();
        this.bindEvents();
      }
      
      loadShortlist() {
        try {
          const saved = localStorage.getItem(this.shortlistKey);
          return saved ? JSON.parse(saved) : [];
        } catch (e) {
          console.error('Error loading shortlist:', e);
          return [];
        }
      }
      
      saveShortlist() {
        try {
          localStorage.setItem(this.shortlistKey, JSON.stringify(this.shortlistedHorses));
        } catch (e) {
          console.error('Error saving shortlist:', e);
        }
      }
      
      addHorse(horseId, horseName, jockey) {
        if (!this.isHorseShortlisted(horseId)) {
          this.shortlistedHorses.push({ 
            id: horseId, 
            name: horseName, 
            jockey: jockey
          });
          this.saveShortlist();
          this.updateUI();
          return true;
        }
        return false;
      }
      
      removeHorse(horseId) {
        const index = this.shortlistedHorses.findIndex(h => h.id === horseId);
        if (index !== -1) {
          this.shortlistedHorses.splice(index, 1);
          this.saveShortlist();
          this.updateUI();
          return true;
        }
        return false;
      }
      
      isHorseShortlisted(horseId) {
        return this.shortlistedHorses.some(h => h.id === horseId);
      }
      
      updateUI() {
        // Update shortlist button
        const shortlistBtn = document.getElementById('shortlistBtn');
        const shortlistIcon = document.getElementById('shortlistIcon');
        
        if (shortlistBtn && shortlistIcon) {
          if (this.isHorseShortlisted(currentHorseId)) {
            shortlistBtn.classList.add('active');
            shortlistIcon.classList.remove('far');
            shortlistIcon.classList.add('fas');
          } else {
            shortlistBtn.classList.remove('active');
            shortlistIcon.classList.remove('fas');
            shortlistIcon.classList.add('far');
          }
        }
        
        // Update shortlist sidebar
        const shortlistItemsContainer = document.getElementById('shortlistItems');
        if (shortlistItemsContainer) {
          if (this.shortlistedHorses.length === 0) {
            shortlistItemsContainer.innerHTML = `
              <div class="shortlist-empty">Click the star icon on a horse to add it to your shortlist</div>
            `;
          } else {
            shortlistItemsContainer.innerHTML = this.shortlistedHorses.map(horse => `
              <div class="shortlist-item ${horse.id === currentHorseId ? 'selected' : ''}" 
                   data-horse-id="${horse.id}" 
                   onclick="navigateToHorse('${horse.id}')">
                <div class="shortlist-item-name">${horse.name}</div>
                <div class="shortlist-item-detail">${horse.jockey}</div>
              </div>
            `).join('');
          }
        }
      }
      
      bindEvents() {
        const shortlistBtn = document.getElementById('shortlistBtn');
        if (shortlistBtn) {
          shortlistBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const horseId = shortlistBtn.dataset.horseId;
            const horseName = shortlistBtn.dataset.horseName;
            const jockey = shortlistBtn.dataset.jockey;
            
            if (this.isHorseShortlisted(horseId)) {
              this.removeHorse(horseId);
            } else {
              this.addHorse(horseId, horseName, jockey);
            }
          });
        }
      }
    }
    
    // Function to navigate directly to a specific horse
    function navigateToHorse(horseId) {
      if (isNavigating || !horseId) return;
      isNavigating = true;
      
      // Show loader
      document.getElementById('loader').style.display = 'flex';
      
      // Navigate to the horse
      setTimeout(() => {
        window.location.href = `puzzle-piece.php?runner=${horseId}&race=${currentRaceId}`;
      }, 300);
    }
    
    // Function to go back to the previous page
    function goBack() {
      window.history.back();
    }
    
    // Function to navigate to the previous horse
    function navigateToPrevHorse() {
      if (isNavigating || !prevHorseId) return;
      isNavigating = true;
      
      // Add animation
      document.querySelector('.app-container').classList.add('card-animate-prev');
      
      // Show loader
      document.getElementById('loader').style.display = 'flex';
      
      // Navigate to the previous horse
      setTimeout(() => {
        window.location.href = `puzzle-piece.php?runner=${prevHorseId}&race=${currentRaceId}`;
      }, 300);
    }
    
    // Function to navigate to the next horse
    function navigateToNextHorse() {
      if (isNavigating || !nextHorseId) return;
      isNavigating = true;
      
      // Add animation
      document.querySelector('.app-container').classList.add('card-animate');
      
      // Show loader
      document.getElementById('loader').style.display = 'flex';
      
      // Navigate to the next horse
      setTimeout(() => {
        window.location.href = `puzzle-piece.php?runner=${nextHorseId}&race=${currentRaceId}`;
      }, 300);
    }
    
    // Show loader initially, hide when page is loaded
    window.addEventListener('load', function() {
      document.getElementById('loader').style.display = 'none';
      
      // Initialize shortlist
      const shortlistManager = new ShortlistManager();
    });
    
    document.getElementById('loader').style.display = 'flex';
    
    // Mobile sidebar toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const leftSidebar = document.getElementById('leftSidebar');
    
    if (sidebarToggle && leftSidebar) {
      sidebarToggle.addEventListener('click', function() {
        leftSidebar.classList.toggle('open');
      });
      
      // Close sidebar when clicking outside
      document.addEventListener('click', function(event) {
        if (!leftSidebar.contains(event.target) && !sidebarToggle.contains(event.target) && leftSidebar.classList.contains('open')) {
          leftSidebar.classList.remove('open');
        }
      });
    }
    
    // Add touch swipe functionality for mobile
    let touchStartX = 0;
    let touchEndX = 0;
    
    document.addEventListener('touchstart', function(event) {
      touchStartX = event.changedTouches[0].screenX;
    });
    
    document.addEventListener('touchend', function(event) {
      touchEndX = event.changedTouches[0].screenX;
      handleSwipe();
    });
    
    function handleSwipe() {
      const swipeThreshold = 100;
      
      if (touchEndX < touchStartX - swipeThreshold) {
        // Swipe left (next horse)
        navigateToNextHorse();
      } else if (touchEndX > touchStartX + swipeThreshold) {
        // Swipe right (previous horse)
        navigateToPrevHorse();
      }
    }
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(event) {
      if (event.key === 'Escape') {
        goBack();
      } else if (event.key === 'ArrowLeft') {
        navigateToPrevHorse();
      } else if (event.key === 'ArrowUp') {
        // Middle button action (go back)
        goBack();
      } else if (event.key === 'ArrowRight') {
        navigateToNextHorse();
      }
    });
    
    // Add subtle animation to puzzle pieces
    const puzzlePieces = document.querySelectorAll('.puzzle-piece');
    puzzlePieces.forEach((piece, index) => {
      // Add a subtle floating animation with different timing for each piece
      piece.style.animation = `float ${2 + index * 0.5}s ease-in-out infinite alternate`;
    });
    
    // Define the animation in CSS
    const style = document.createElement('style');
    style.textContent = `
      @keyframes float {
        0% {
          transform: translate(0, 0) rotate(${Math.random() * 20 - 10}deg);
        }
        100% {
          transform: translate(${Math.random() * 10 - 5}px, ${Math.random() * 10 - 5}px) rotate(${Math.random() * 20 - 10}deg);
        }
      }
      
      .action-button.active {
        transform: scale(1.2);
      }
    `;
    document.head.appendChild(style);
  </script>
</body>
</html>