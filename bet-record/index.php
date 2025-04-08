<?php

// Database connection
require_once "../includes/db-connection.php"; // This will now use your SQLite connection


// Initialize variables
$success_message = $error_message = null;
$stats = ['total' => 0, 'won' => 0, 'lost' => 0, 'pending' => 0, 'total_stake' => 0, 'total_returns' => 0];

function fetchRacecourses() {
    global $conn;
    try {
        $sql = "SELECT * FROM racecourses ORDER BY name ASC";
        $stmt = $conn->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!$result) {
            error_log("No racecourses found in the table.");
        }

        return $result;
    } catch(PDOException $e) {
        error_log("Error fetching racecourses: " . $e->getMessage());

        // Check if the table exists
        $checkTable = $conn->query("SELECT name FROM sqlite_master WHERE type='table' AND name='racecourses'");
        if (!$checkTable->fetch()) {
            error_log("Table 'racecourses' does not exist.");
        }

        // Fall back to distinct values from bet_records
        try {
            $sql = "SELECT DISTINCT racecourse FROM bet_records ORDER BY racecourse ASC";
            $stmt = $conn->query($sql);
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch(PDOException $e) {
            error_log("Error fetching distinct racecourses: " . $e->getMessage());
            return [];
        }
    }
}



// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    try {
        // Sanitize input data
        $bet_type = $_POST['bet_type'];
        $stake = floatval($_POST['stake']);
        $selection = $_POST['selection'];
        $odds = $_POST['odds'];
        $jockey = $_POST['jockey'];
        $trainer = $_POST['trainer'];
        $outcome = $_POST['outcome'];
        
        // Handle racecourse (either selected or new)
        $racecourse = $_POST['racecourse'];
        
        // Check if this is a new racecourse to be added to the database
        if (isset($_POST['new_racecourse']) && $_POST['new_racecourse'] == '1') {
            // First check if the racecourse already exists to avoid duplicates
            $checkSql = "SELECT COUNT(*) FROM racecourses WHERE name = :name";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':name', $racecourse);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() == 0) {
                // Add new racecourse to the racecourses table
                $insertCourseSql = "INSERT INTO racecourses (name) VALUES (:name)";
                $insertCourseStmt = $conn->prepare($insertCourseSql);
                $insertCourseStmt->bindParam(':name', $racecourse);
                $insertCourseStmt->execute();
            }
        }
        
        // Calculate returns if bet won
        $returns = 0;
        if ($outcome == 'Won') {
            if (strpos($odds, '/') !== false) {
                list($numerator, $denominator) = explode('/', $odds);
                $returns = $stake + ($stake * $numerator / $denominator);
            } else {
                $returns = $stake * floatval($odds);
            }
        }
        
        // Insert into database using PDO prepared statement
        $sql = "INSERT INTO bet_records (bet_type, stake, selection, racecourse, odds, jockey, trainer, outcome, returns) 
                VALUES (:bet_type, :stake, :selection, :racecourse, :odds, :jockey, :trainer, :outcome, :returns)";
                
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':bet_type', $bet_type);
        $stmt->bindParam(':stake', $stake);
        $stmt->bindParam(':selection', $selection);
        $stmt->bindParam(':racecourse', $racecourse);
        $stmt->bindParam(':odds', $odds);
        $stmt->bindParam(':jockey', $jockey);
        $stmt->bindParam(':trainer', $trainer);
        $stmt->bindParam(':outcome', $outcome);
        $stmt->bindParam(':returns', $returns);
        
        $stmt->execute();
        
        $success_message = "New bet record added successfully!";
        // Redirect to prevent form resubmission
        header("Location: index.php#new-record");
        exit();
    } catch(PDOException $e) {
        $error_message = "Error: " . $e->getMessage();
    }
}


// Fetch existing records
try {
    // SQLite uses different date functions than MySQL
    $sql = "SELECT *, datetime(date_added) as formatted_date FROM bet_records ORDER BY date_added DESC";
    $stmt = $conn->query($sql);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calculate stats
    if ($result) {
        $stats['total'] = count($result);
        
        foreach ($result as $row) {
            if ($row['outcome'] == 'Won') {
                $stats['won']++;
                $stats['total_returns'] += floatval($row['returns']);
            } elseif ($row['outcome'] == 'Lost') {
                $stats['lost']++;
            } elseif ($row['outcome'] == 'Pending') {
                $stats['pending']++;
            }
            $stats['total_stake'] += floatval($row['stake']);
        }
    }
} catch(PDOException $e) {
    $error_message = "Error fetching records: " . $e->getMessage();
}

// Get racecourses
$racecourses = fetchRacecourses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bet Records</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bet-record.css">

    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <h2>Bet Records</h2>
                <div>
                    <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addBetModal">
                        <i class="fas fa-plus"></i> Add New Bet
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleStats()">
                        <i class="fas fa-chart-line"></i> Toggle Stats
                    </button>
                    <button type="button" class="btn btn-success ms-2" onclick="exportToCSV()">
                        <i class="fas fa-file-export"></i> Export CSV
                    </button>
                    
                </div>
            </div>
        </div>
        
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <!-- Stats Section -->
        <div id="statsContainer" class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card total">
                    <div class="stats-value"><?php echo $stats['total']; ?></div>
                    <div class="stats-label">Total Bets</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card wins">
                    <div class="stats-value"><?php echo $stats['won']; ?></div>
                    <div class="stats-label">Wins</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card losses">
                    <div class="stats-value"><?php echo $stats['lost']; ?></div>
                    <div class="stats-label">Losses</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card pending">
                    <div class="stats-value"><?php echo $stats['pending']; ?></div>
                    <div class="stats-label">Pending</div>
                </div>
            </div>
            <div class="col-md-6 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Financial Summary</h5>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1">Total Staked:</p>
                                <h3 class="text-danger">£<?php echo number_format($stats['total_stake'], 2); ?></h3>
                            </div>
                            <div class="col-6">
                                <p class="mb-1">Total Returns:</p>
                                <h3 class="text-success">£<?php echo number_format($stats['total_returns'], 2); ?></h3>
                            </div>
                        </div>
                        <div class="progress mt-2">
                            <?php 
                            $win_percentage = ($stats['won'] > 0 && $stats['total'] > 0) ? ($stats['won'] / $stats['total']) * 100 : 0; 
                            ?>
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $win_percentage; ?>%">
                                <?php echo round($win_percentage); ?>% Win Rate
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Search and Filter Section -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" class="form-control" id="searchBets" placeholder="Search bets...">
                </div>
            </div>
            <div class="col-md-6">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary filter-outcome active" data-outcome="all">All</button>
                    <button type="button" class="btn btn-outline-success filter-outcome" data-outcome="won">Won</button>
                    <button type="button" class="btn btn-outline-danger filter-outcome" data-outcome="lost">Lost</button>
                    <button type="button" class="btn btn-outline-warning filter-outcome" data-outcome="pending">Pending</button>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Bet Type</th>
                                <th>Stake</th>
                                <th>Selection/Horse</th>
                                <th>Racecourse</th>
                                <th>Odds</th>
                                <th>Jockey</th>
                                <th>Trainer</th>
                                <th>Outcome</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <!-- Table section in HTML -->
                        <tbody>
                            <?php if (!empty($result)): ?>
                                <?php foreach($result as $row): ?>
                                <tr id="bet-row-<?php echo $row['id']; ?>">
                                    <td><?php 
                                        // Format date for SQLite timestamp
                                        $date = new DateTime($row['formatted_date']);
                                        echo $date->format('d/m/Y H:i');
                                    ?></td>
                                    <td><?php echo htmlspecialchars($row['bet_type']); ?></td>
                                    <td>£<?php echo htmlspecialchars($row['stake']); ?></td>
                                    <td><?php echo htmlspecialchars($row['selection']); ?></td>
                                    <td><?php echo htmlspecialchars($row['racecourse']); ?></td>
                                    <td><?php echo htmlspecialchars($row['odds']); ?></td>
                                    <td><?php echo htmlspecialchars($row['jockey']); ?></td>
                                    <td><?php echo htmlspecialchars($row['trainer']); ?></td>
                                    <td>
                                        <?php if($row['outcome'] == 'Won'): ?>
                                            <span class="badge bg-success">Won</span>
                                        <?php elseif($row['outcome'] == 'Lost'): ?>
                                            <span class="badge bg-danger">Lost</span>
                                        <?php elseif($row['outcome'] == 'Pending'): ?>
                                            <span class="badge bg-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?php echo htmlspecialchars($row['outcome']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="toggleEditMode(<?php echo $row['id']; ?>)" 
                                                data-bs-toggle="tooltip" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="delete_bet.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger delete-bet" 
                                        data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="10" class="text-center">No bet records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="noResults" class="alert alert-info d-none">No matching bet records found</div>
                </div>
            </div>
        </div>
    </div>

   <!-- Add Bet Modal -->
   <div class="modal fade" id="addBetModal" tabindex="-1" aria-labelledby="addBetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBetModalLabel">Add New Bet</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="" id="betForm" class="needs-validation" novalidate>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="bet_type" class="form-label">Bet Type</label>
                                <select class="form-select" id="bet_type" name="bet_type" required>
                                    <option value="">Select Bet Type</option>
                                    <option value="Win">Win</option>
                                    <option value="Place">Place</option>
                                    <option value="Each Way">Each Way</option>
                                    <option value="Forecast">Forecast</option>
                                    <option value="Tricast">Tricast</option>
                                    <option value="Accumulator">Accumulator</option>
                                </select>
                                <div class="invalid-feedback">Please select a bet type</div>
                            </div>
                            <div class="col-md-6">
                                <label for="stake" class="form-label">Stake (£)</label>
                                <input type="number" step="0.01" class="form-control" id="stake" name="stake" required>
                                <div class="invalid-feedback">Please enter stake amount</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="selection" class="form-label">Selection/Horse</label>
                                <input type="text" class="form-control" id="selection" name="selection" required>
                                <div class="invalid-feedback">Please enter horse name</div>
                            </div>
                            <div class="col-md-6">
    <label for="racecourse" class="form-label">Racecourse</label>
    <select class="form-select" id="racecourse" name="racecourse" required>
        <option value="">Select Racecourse</option>
        <?php 
        // Check if we got array of arrays (from racecourses table) or just values
        if(!empty($racecourses) && isset($racecourses[0]) && is_array($racecourses[0])) {
            foreach($racecourses as $course): ?>
                <option value="<?php echo htmlspecialchars($course['name']); ?>">
                    <?php echo htmlspecialchars($course['name']); ?>
                </option>
            <?php endforeach;
        } else {
            // Simple array of values
            foreach($racecourses as $course): ?>
                <option value="<?php echo htmlspecialchars($course); ?>">
                    <?php echo htmlspecialchars($course); ?>
                </option>
            <?php endforeach;
        } ?>
    </select>
    <div class="invalid-feedback">Please select a racecourse</div>
    
    <!-- This input will appear when "Other" is selected -->
    <div id="newRacecourseContainer" class="mt-2 d-none">
        <input type="text" class="form-control" id="newRacecourse" placeholder="Enter new racecourse">
    </div>
</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="odds" class="form-label">Odds</label>
                                <input type="text" class="form-control" id="odds" name="odds" placeholder="e.g. 5/1" required>
                                <div class="invalid-feedback">Please enter odds (e.g. 5/1)</div>
                            </div>
                            <div class="col-md-4">
                                <label for="jockey" class="form-label">Jockey</label>
                                <input type="text" class="form-control" id="jockey" name="jockey" required>
                                <div class="invalid-feedback">Please enter jockey name</div>
                            </div>
                            <div class="col-md-4">
                                <label for="trainer" class="form-label">Trainer</label>
                                <input type="text" class="form-control" id="trainer" name="trainer" required>
                                <div class="invalid-feedback">Please enter trainer name</div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="outcome" class="form-label">Outcome</label>
                                <select class="form-select" id="outcome" name="outcome" required>
                                    <option value="">Select Outcome</option>
                                    <option value="Won">Won</option>
                                    <option value="Lost">Lost</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Void">Void</option>
                                </select>
                                <div class="invalid-feedback">Please select an outcome</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Potential Returns</label>
                                <div class="input-group">
                                    <span class="input-group-text">£</span>
                                    <div class="form-control bg-light" id="potentialReturns">0.00</div>
                                </div>
                                <small class="text-muted">Based on stake and odds</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="submit" class="btn btn-primary">Save Bet</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "../includes/sidebar.php"; ?>

    <!-- Link to sidebar JavaScript -->
<script src="../assets/js/sidebar.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bet-record.js"></script>
</body>
</html>