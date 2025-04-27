<?php

// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Continue with the rest of your dashboard code

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
        
        // Get the current user ID from session
        $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
        
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
        
        // Insert into database using PDO prepared statement with user_id
        $sql = "INSERT INTO bet_records (bet_type, stake, selection, racecourse, odds, jockey, trainer, outcome, returns, user_id) 
                VALUES (:bet_type, :stake, :selection, :racecourse, :odds, :jockey, :trainer, :outcome, :returns, :user_id)";
                
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
        $stmt->bindParam(':user_id', $user_id);
        
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
    // Get the current user ID from session
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in session
    
    // SQLite uses different date functions than MySQL
    // Add WHERE clause to only get current user's records
    $sql = "SELECT *, datetime(date_added) as formatted_date FROM bet_records 
            WHERE user_id = :user_id 
            ORDER BY date_added DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto+Mono&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <!-- Additional CSS for bet records page -->
    <!-- <link rel="stylesheet" href="assets/css/bet-record.css"> -->
   
</head>

<style>
        /* Improved Table Styles */
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
            border-radius: var(--radius-sm);
            overflow: hidden;
        }
        
        .table th, .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        .table th {
            background-color: var(--medium-bg);
            font-weight: 600;
            color: var(--text-dark);
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 1px 0 var(--border-color);
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(30, 86, 49, 0.07);
        }
        
        /* Responsive table */
        @media (max-width: 992px) {
            .table-responsive {
                display: block;
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }
        
        /* Status Badge Positioning */
        td .badge {
            padding: 5px 10px;
            display: inline-block;
            text-align: center;
            min-width: 70px;
        }
        
        /* Better Modal Scrolling */
        .modal-dialog {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
        
        .modal-content {
            max-height: 90vh;
            display: flex;
            flex-direction: column;
        }
        
        .modal-body {
            overflow-y: auto;
            padding: 1.5rem;
        }
        
        /* Stats cards styling */
        .stats-card {
            padding: 1.2rem;
            border-radius: var(--radius-md);
            min-width: 120px;
            background-color: white;
            box-shadow: var(--shadow-sm);
            text-align: center;
            flex: 1 1 0;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .stats-card.total {
         background: linear-gradient(135deg, #007bff, #0056b3); /* Blue gradient */
        color: white;
        }

        .stats-card.wins {
    background: linear-gradient(135deg, #28a745, #218838); /* Green gradient */
    color: white;
        }

        .stats-card.losses {
    background: linear-gradient(135deg, #dc3545, #a71d2a); /* Red gradient */
    color: white;
}

        .stats-card.pending {
          background: linear-gradient(135deg, #ffc107, #ff9800); /* Amber gradient */
           color: #333;
}


        .stats-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-label {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        /* Action button styling */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .text-success {
            color: #1e5631;
        }
        
        .text-danger {
            color: #e63946;
        }
        
        /* Financial info in stats */
        .financial-summary {
            border-left: 4px solid var(--primary-color);
            padding-left: 15px;
        }
        
        /* Progress bar */
        .progress {
            height: 12px;
            background-color: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            color: white;
            text-align: center;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }
        
        .bg-success {
            background-color: var(--primary-color) !important;
        }
        
        /* Search and filter styling */
        .search-container {
            flex: 1;
            max-width: 400px;
        }
        
        .input-group {
            display: flex;
            position: relative;
        }
        
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0.375rem 0.75rem;
            background-color: var(--medium-bg);
            border: 1px solid var(--border-color);
            border-right: none;
            border-top-left-radius: var(--radius-sm);
            border-bottom-left-radius: var(--radius-sm);
        }
        
        .form-control {
            flex: 1;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        
        /* Filter buttons */
        .btn-group {
            display: flex;
        }
        
        .btn-outline-secondary,
        .btn-outline-success,
        .btn-outline-danger,
        .btn-outline-warning {
            background-color: transparent;
            padding: 0.375rem 0.75rem;
        }
        
        .btn-outline-secondary {
            border: 1px solid #6c757d;
            color: #6c757d;
        }
        
        .btn-outline-success {
            border: 1px solid #1e5631;
            color: #1e5631;
        }
        
        .btn-outline-danger {
            border: 1px solid #e63946;
            color: #e63946;
        }
        
        .btn-outline-warning {
            border: 1px solid #f3a712;
            color: #f3a712;
        }
        
        .btn-outline-secondary:hover, .btn-outline-secondary.active {
            background-color: #6c757d;
            color: white;
        }
        
        .btn-outline-success:hover, .btn-outline-success.active {
            background-color: #1e5631;
            color: white;
        }
        
        .btn-outline-danger:hover, .btn-outline-danger.active {
            background-color: #e63946;
            color: white;
        }
        
        .btn-outline-warning:hover, .btn-outline-warning.active {
            background-color: #f3a712;
            color: white;
        }
        
        /* Fix modal footer position */
        .modal-footer {
            border-top: 1px solid var(--border-color);
            padding: 1rem;
            background-color: var(--medium-bg);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .d-none {
            display: none !important;
        }
    </style>

<?php include '../test/app-header.php'; ?>

<body>
<div class="container mt-20">
        <div class="d-flex justify-between align-center mb-10">
            <h2>Bet Record</h2>
            <p>Track your selections performance</p>
        </div>    

<div class="container mt-20">
        <div class="card mb-20">
            <div class="card-header">
                <div class="d-flex gap-10">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBetModal">
                        <i class="fas fa-plus"></i> Add New Bet
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="toggleStats()">
                        <i class="fas fa-chart-line"></i> Toggle Stats
                    </button>
                    <button type="button" class="btn btn-primary" onclick="exportToCSV()">
                        <i class="fas fa-file-export"></i> Export CSV
                    </button>
                </div>
            </div>
            
            <?php if(isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php endif; ?>
            <?php if(isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>
            
            <!-- Stats Section with race-themed styling -->
            <div id="statsContainer" class="card-body">
                <div class="d-flex flex-wrap gap-20 mb-20">
                    <div class="stats-card total">
                        <div class="stats-value"><?php echo $stats['total']; ?></div>
                        <div class="stats-label">Total Bets</div>
                    </div>
                    <div class="stats-card wins">
                        <div class="stats-value"><?php echo $stats['won']; ?></div>
                        <div class="stats-label">Wins</div>
                    </div>
                    <div class="stats-card losses">
                        <div class="stats-value"><?php echo $stats['lost']; ?></div>
                        <div class="stats-label">Losses</div>
                    </div>
                    <div class="stats-card pending">
                        <div class="stats-value"><?php echo $stats['pending']; ?></div>
                        <div class="stats-label">Pending</div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Financial Summary</h5>
                        <div class="d-flex justify-between mb-10">
                            <div>
                                <p class="mb-10">Total Staked:</p>
                                <h3 class="text-danger">£<?php echo number_format($stats['total_stake'], 2); ?></h3>
                            </div>
                            <div>
                                <p class="mb-10">Total Returns:</p>
                                <h3 class="text-success">£<?php echo number_format($stats['total_returns'], 2); ?></h3>
                            </div>
                        </div>
                        <div class="progress mt-10">
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
        <div class="card mb-20">
            <div class="card-body">
                <div class="d-flex justify-between flex-wrap gap-10">
                    <div class="search-container">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchBets" placeholder="Search bets...">
                        </div>
                    </div>
                    <div class="filter-buttons">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary filter-outcome active" data-outcome="all">All</button>
                            <button type="button" class="btn btn-outline-success filter-outcome" data-outcome="won">Won</button>
                            <button type="button" class="btn btn-outline-danger filter-outcome" data-outcome="lost">Lost</button>
                            <button type="button" class="btn btn-outline-warning filter-outcome" data-outcome="pending">Pending</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Records Table Card -->
        <div class="card">
            <div class="card-body">
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
                                            <span class="badge badge-success">Won</span>
                                        <?php elseif($row['outcome'] == 'Lost'): ?>
                                            <span class="badge badge-danger">Lost</span>
                                        <?php elseif($row['outcome'] == 'Pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-info"><?php echo htmlspecialchars($row['outcome']); ?></span>
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

   <!-- Add Bet Modal with racing-themed styling -->
<div class="modal fade" id="addBetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="addBetModalLabel">Add New Bet</h3>
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
                            <input type="text" class="form-control" id="racecourse" name="racecourse" list="racecourseList" autocomplete="off" required>
                            <datalist id="racecourseList">
                                <?php 
                                // Check if we got array of arrays (from racecourses table) or just values
                                if(!empty($racecourses) && isset($racecourses[0]) && is_array($racecourses[0])) {
                                    foreach($racecourses as $course): ?>
                                        <option value="<?php echo htmlspecialchars($course['name']); ?>">
                                    <?php endforeach;
                                } else {
                                    // Simple array of values
                                    foreach($racecourses as $course): ?>
                                        <option value="<?php echo htmlspecialchars($course); ?>">
                                    <?php endforeach;
                                } ?>
                            </datalist>
                            <div class="invalid-feedback">Please enter a racecourse</div>
                            
                            <!-- Hidden field to track if this is a new racecourse -->
                            <input type="hidden" name="new_racecourse" id="new_racecourse" value="0">
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
                            <div class="invalid-feedback">Please enter Jockey name</div>
                        </div>
                        <div class="col-md-4">
                            <label for="trainer" class="form-label">Trainer</label>
                            <input type="text" class="form-control" id="trainer" name="trainer" required>
                            <div class="invalid-feedback">Please enter Trainer name</div>
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

    <?php include '../test/bottom-nav.php'; ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bet-record.js"></script>
    
    <script>
    // Script to handle new racecourse entry
    document.addEventListener('DOMContentLoaded', function() {
        const racecourseSelect = document.getElementById('racecourse');
        const newRacecourseContainer = document.getElementById('newRacecourseContainer');
        const newRacecourseInput = document.getElementById('newRacecourse');
        const newRacecourseFlag = document.getElementById('new_racecourse');
        const closeButtons = document.querySelectorAll('.close-modal');
        
        racecourseSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                newRacecourseContainer.classList.remove('d-none');
                newRacecourseFlag.value = '1';
                newRacecourseInput.required = true;
                
                // When "Other" is selected, we'll use the value from the new input field
                newRacecourseInput.addEventListener('input', function() {
                    racecourseSelect.value = newRacecourseInput.value;
                });
            } else {
                newRacecourseContainer.classList.add('d-none');
                newRacecourseFlag.value = '0';
                newRacecourseInput.required = false;
            }
        });
        
        // Calculate potential returns when stake or odds change
        const stakeInput = document.getElementById('stake');
        const oddsInput = document.getElementById('odds');
        const potentialReturnsDisplay = document.getElementById('potentialReturns');
        const outcomeSelect = document.getElementById('outcome');
        
        function calculateReturns() {
            const stake = parseFloat(stakeInput.value) || 0;
            const odds = oddsInput.value;
            let returns = 0;
            
            if (odds && stake > 0) {
                if (odds.includes('/')) {
                    const [numerator, denominator] = odds.split('/');
                    returns = stake + (stake * parseFloat(numerator) / parseFloat(denominator));
                } else {
                    returns = stake * parseFloat(odds);
                }
            }
            
            potentialReturnsDisplay.textContent = returns.toFixed(2);
        }
        
        stakeInput.addEventListener('input', calculateReturns);
        oddsInput.addEventListener('input', calculateReturns);
    });
    
    // Toggle stats section visibility
    function toggleStats() {
        const statsContainer = document.getElementById('statsContainer');
        if (statsContainer.style.display === 'none') {
            statsContainer.style.display = 'block';
        } else {
            statsContainer.style.display = 'none';
        }
    }
    
    // Export table data to CSV
    function exportToCSV() {
        const table = document.querySelector('table');
        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = [], cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                // Get the text content and remove any commas
                let data = cols[j].textContent.replace(/,/g, ' ');
                // Remove the actions column content
                if (j === cols.length - 1 && i > 0) data = '';
                row.push('"' + data + '"');
            }
            
            csv.push(row.join(','));
        }
        
        // Download CSV file
        const csvFile = new Blob([csv.join('\n')], {type: 'text/csv'});
        const downloadLink = document.createElement('a');
        downloadLink.download = 'bet_records_' + new Date().toISOString().slice(0,10) + '.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
    </script>
</body>
</html>