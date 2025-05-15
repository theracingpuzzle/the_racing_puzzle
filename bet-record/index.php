<?php
// bet-record/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Database connection
require_once "../includes/db-connection.php";

// Include helper functions
require_once "includes/functions.php";

// Initialize variables
$success_message = $_SESSION['success_message'] ?? null;
$error_message = $_SESSION['error_message'] ?? null;

// Clear session messages after using them
unset($_SESSION['success_message'], $_SESSION['error_message']);

// Get the current user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user's bet records
$result = fetchUserBetRecords($conn, $user_id);

// Calculate stats
$stats = calculateStats($result);

// Get racecourses
$racecourses = fetchRacecourses($conn);

// Debugging
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    error_log("POST request received in index.php");
    error_log("POST data: " . print_r($_POST, true));
}
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
    <link rel="stylesheet" href="assets/css/bet-record.css">

    <link rel="stylesheet" href="../test/betting-modal.css">
    
    <!-- Enhanced Table Styling -->
    <link rel="stylesheet" href="assets/css/enhanced-table.css">
    
    <!-- Add the toggleEditMode function definition before it's called in HTML -->
    <script>
    // Define the toggleEditMode function globally so it's available to inline onclick handlers
    function toggleEditMode(betId) {
        console.log("Edit mode toggled for bet ID:", betId);
        if (!betId) return;
        
        // Fetch bet data from the server
        fetch(`get_bet.php?id=${betId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.bet) {
                    // Open modal
                    const modal = document.getElementById('bettingModal');
                    if (!modal) {
                        console.error('Betting modal not found');
                        return;
                    }
                    modal.classList.add('open');
                    
                    // Set form title to indicate edit mode
                    const modalTitle = modal.querySelector('.betting-modal-header h2');
                    if (modalTitle) {
                        modalTitle.innerHTML = `<i class="fas fa-edit"></i> Edit Bet #${betId}`;
                    }
                    
                    // Add hidden input for bet ID
                    let idInput = document.getElementById('edit_bet_id');
                    if (!idInput) {
                        idInput = document.createElement('input');
                        idInput.type = 'hidden';
                        idInput.id = 'edit_bet_id';
                        idInput.name = 'id';
                        document.getElementById('bettingForm').appendChild(idInput);
                    }
                    idInput.value = betId;
                    
                    // Update form action to edit endpoint
                    const form = document.getElementById('bettingForm');
                    if (form) {
                        form.action = 'includes/edit_bet.php';
                    }
                    
                    // Populate form fields
                    document.getElementById('bet_type').value = data.bet.bet_type;
                    document.getElementById('stake').value = data.bet.stake;
                    document.getElementById('selection_0').value = data.bet.selection;
                    document.getElementById('racecourse_0').value = data.bet.racecourse;
                    document.getElementById('odds_0').value = data.bet.odds;
                    document.getElementById('jockey_0').value = data.bet.jockey;
                    document.getElementById('trainer_0').value = data.bet.trainer;
                    document.getElementById('outcome').value = data.bet.outcome;
                    
                    // Update submit button text
                    const submitBtn = document.querySelector('.btn-submit');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Bet';
                    }
                    
                    // Calculate potential returns if the function exists
                    if (typeof updatePotentialReturns === 'function') {
                        updatePotentialReturns();
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to get bet details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while getting the bet details: ' + error.message);
            });
    }
    </script>
</head>

<body>
<?php include '../test/app-header.php'; ?>

<div class="container mt-20">
    <div class="d-flex justify-between align-center mb-10">
        <h2>Bet Record</h2>
        <p>Track your selections performance</p>
    </div>    

    <div class="container mt-20">
        <div class="card mb-20">
            <div class="card-header">
                <div class="d-flex gap-10">
                    <button id="openModalBtn" class="test-button">
                        <i class="fas fa-plus-circle"></i> Add New Bet
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
            
            <!-- Stats Section is commented out in the original -->
            
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
        <!-- Desktop Table View (hides on small screens) -->
        <div class="table-responsive-large">
            <div class="table-responsive">
                <table class="table modern-table">
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
                                    <td data-label="Date" class="col-date">
                                        <?php 
                                            // Format date for SQLite timestamp
                                            $date = new DateTime($row['formatted_date']);
                                            echo $date->format('d/m/Y');
                                            // Add tooltip with time
                                            echo '<small class="d-block text-muted">' . $date->format('H:i') . '</small>';
                                        ?>
                                    </td>
                                    <td data-label="Bet Type"><?php echo htmlspecialchars($row['bet_type']); ?></td>
                                    <td data-label="Stake" class="col-stake">£<?php echo htmlspecialchars($row['stake']); ?></td>
                                    <td data-label="Selection"><?php echo htmlspecialchars($row['selection']); ?></td>
                                    <td data-label="Racecourse">
                                        <span class="racecourse-badge">
                                            <?php echo htmlspecialchars($row['racecourse']); ?>
                                        </span>
                                    </td>
                                    <td data-label="Odds" class="col-odds"><?php echo htmlspecialchars($row['odds']); ?></td>
                                    <td data-label="Jockey"><?php echo htmlspecialchars($row['jockey']); ?></td>
                                    <td data-label="Trainer"><?php echo htmlspecialchars($row['trainer']); ?></td>
                                    <td data-label="Outcome" class="col-outcome">
                                        <?php if($row['outcome'] == 'Won'): ?>
                                            <span class="modern-badge badge-won">Won</span>
                                        <?php elseif($row['outcome'] == 'Lost'): ?>
                                            <span class="modern-badge badge-lost">Lost</span>
                                        <?php elseif($row['outcome'] == 'Pending'): ?>
                                            <span class="modern-badge badge-pending">Pending</span>
                                            <!-- Quick update buttons for pending bets only -->
                                            <div class="quick-update">
                                                <button class="quick-update-btn quick-won" data-bet-id="<?php echo $row['id']; ?>" data-outcome="Won">Won</button>
                                                <button class="quick-update-btn quick-lost" data-bet-id="<?php echo $row['id']; ?>" data-outcome="Lost">Lost</button>
                                            </div>
                                        <?php else: ?>
                                            <span class="modern-badge badge-void"><?php echo htmlspecialchars($row['outcome']); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Actions" class="col-actions">
                                        <div class="action-buttons">
                                            <button class="action-btn edit-btn" data-bet-id="<?php echo $row['id']; ?>" data-bs-toggle="tooltip" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <a href="delete_bet.php?id=<?php echo $row['id']; ?>" class="action-btn delete-btn delete-bet" data-bs-toggle="tooltip" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
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

<!-- Betting Modal HTML Structure -->
<div id="bettingModal" class="betting-modal">
    <div class="betting-modal-content">
        <div class="betting-modal-header">
            <h2><i class="fas fa-ticket-alt"></i> Betting Information</h2>
            <span class="betting-modal-close"><i class="fas fa-times"></i></span>
        </div>
        <div class="betting-modal-body">
            <form id="bettingForm" method="post" action="includes/process_bet.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="bet_type" class="required-field">Bet Type</label>
                        <select id="bet_type" name="bet_type" required>
                            <option value="Win">Win</option>
                            <option value="Place">Place</option>
                            <option value="Each Way">Each Way</option>
                            <option value="Accumulator">Accumulator</option>
                            <option value="Cover/Insure">Insure</option>
                        </select>
                        <div class="tooltip">
                            <i class="fas fa-info-circle"></i>
                            <span class="tooltip-text">Select the type of bet you're placing</span>
                        </div>
                    </div>
                    
                    <div class="form-group" id="selectionsCountGroup" style="display: none;">
                        <label for="numberOfSelections" class="required-field">Number of Selections</label>
                        <select id="numberOfSelections">
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>

                <!-- Selection container - this will hold all selections -->
                <div id="selectionsContainer">
                    <div class="selection-box" data-index="0">
                        <h3 class="selection-title">Selection Details</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="selection_0" class="required-field">Horse Name</label>
                                <input type="text" id="selection_0" name="selection" required placeholder="Enter horse name">
                            </div>
                            <div class="form-group">
                                <label for="racecourse_0" class="required-field">Racecourse</label>
                                <input type="text" id="racecourse_0" name="racecourse" required 
                                          placeholder="Enter racecourse" list="racecourseList">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="jockey_0">Jockey</label>
                                <input type="text" id="jockey_0" name="jockey" placeholder="Enter jockey name">
                            </div>
                            <div class="form-group">
                                <label for="trainer_0">Trainer</label>
                                <input type="text" id="trainer_0" name="trainer" placeholder="Enter trainer name">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="odds_0" class="required-field">Odds</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-percentage input-icon"></i>
                                    <input type="text" id="odds_0" name="odds" placeholder="e.g. 5/1 or 6.0" required>
                                </div>
                                <div class="tooltip">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="tooltip-text">Enter odds in fractional (e.g. 5/1) or decimal (e.g. 6.0) format</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Add this to your form before the closing </form> tag -->
                <datalist id="racecourseList">
                    <?php foreach($racecourses as $racecourse): ?>
                        <?php $raceName = is_array($racecourse) ? $racecourse['name'] : $racecourse; ?>
                        <option value="<?php echo htmlspecialchars($raceName); ?>">
                    <?php endforeach; ?>
                </datalist>

                <div class="form-row">
                    <div class="form-group">
                        <label for="stake" class="required-field">Stake (£)</label>
                        <div class="input-with-icon">
                            <i class="fas fa-pound-sign input-icon"></i>
                            <input type="number" id="stake" name="stake" step="0.01" min="0.01" required placeholder="Enter stake amount">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="outcome">Outcome</label>
                        <select id="outcome" name="outcome">
                            <option value="Pending">Pending</option>
                            <option value="Won">Won</option>
                            <option value="Lost">Lost</option>
                            <option value="Void">Void</option>
                        </select>
                        <div class="outcome-badges" style="margin-top: 8px;">
                            <span class="badge badge-pending">Pending</span>
                            <span class="badge badge-won">Won</span>
                            <span class="badge badge-lost">Lost</span>
                            <span class="badge badge-void">Void</span>
                        </div>
                    </div>
                </div>

                <!-- Potential Returns Box -->
                <div class="returns-box">
                    <div class="returns-row">
                        <div class="returns-item">
                            <p class="returns-label">Potential Return:</p>
                            <p class="returns-value" id="potentialReturns">£0.00</p>
                        </div>
                        <div class="returns-item">
                            <p class="returns-label">Potential Profit:</p>
                            <p class="returns-value" id="potentialProfit">£0.00</p>
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="cancelButton" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" name="submitBtn" class="btn btn-submit">
                        <i class="fas fa-check"></i> Submit Bet
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Card View (automatically shows on small screens) -->
<div class="table-responsive-mobile">
    <?php if (!empty($result)): ?>
        <?php foreach($result as $row): ?>
            <div class="bet-card" data-bet-id="<?php echo $row['id']; ?>" data-outcome="<?php echo strtolower($row['outcome']); ?>">
                <div class="bet-card-header">
                    <span class="bet-date"><?php 
                        $date = new DateTime($row['formatted_date']);
                        echo $date->format('d/m/Y');
                    ?></span>
                    
                    <?php if($row['outcome'] == 'Won'): ?>
                        <span class="badge badge-success">Won</span>
                    <?php elseif($row['outcome'] == 'Lost'): ?>
                        <span class="badge badge-danger">Lost</span>
                    <?php elseif($row['outcome'] == 'Pending'): ?>
                        <span class="badge badge-warning">Pending</span>
                    <?php else: ?>
                        <span class="badge badge-info"><?php echo htmlspecialchars($row['outcome']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="bet-card-body">
                    <div class="bet-info-group">
                        <div class="bet-label">Horse:</div>
                        <div class="bet-value"><?php echo htmlspecialchars($row['selection']); ?></div>
                    </div>
                    <div class="bet-info-group">
                        <div class="bet-label">Racecourse:</div>
                        <div class="bet-value"><?php echo htmlspecialchars($row['racecourse']); ?></div>
                    </div>
                    <div class="bet-info-row">
                        <div class="bet-info-group">
                            <div class="bet-label">Stake:</div>
                            <div class="bet-value">£<?php echo htmlspecialchars($row['stake']); ?></div>
                        </div>
                        <div class="bet-info-group">
                            <div class="bet-label">Odds:</div>
                            <div class="bet-value"><?php echo htmlspecialchars($row['odds']); ?></div>
                        </div>
                    </div>
                    <div class="bet-info-row">
                        <div class="bet-info-group">
                            <div class="bet-label">Type:</div>
                            <div class="bet-value"><?php echo htmlspecialchars($row['bet_type']); ?></div>
                        </div>
                        <?php if (!empty($row['jockey'])): ?>
                        <div class="bet-info-group">
                            <div class="bet-label">Jockey:</div>
                            <div class="bet-value"><?php echo htmlspecialchars($row['jockey']); ?></div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if($row['outcome'] == 'Pending'): ?>
                    <!-- Quick update buttons for pending bets only -->
                    <div class="quick-update">
                        <button class="quick-update-btn quick-won" data-bet-id="<?php echo $row['id']; ?>" data-outcome="Won">Mark as Won</button>
                        <button class="quick-update-btn quick-lost" data-bet-id="<?php echo $row['id']; ?>" data-outcome="Lost">Mark as Lost</button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="bet-card-footer">
                    <button type="button" class="action-btn edit-btn" 
        onclick="toggleEditMode(<?php echo $row['id']; ?>)" 
        data-bet-id="<?php echo $row['id']; ?>">
    <i class="fas fa-edit"></i>
</button>
                        <a href="delete_bet.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger delete-bet">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info">No bet records found</div>
    <?php endif; ?>
</div>

<?php include '../test/bottom-nav.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/modal.js"></script>
<script src="assets/js/calculations.js"></script>
<script src="assets/js/form-handler.js"></script>
<script src="assets/js/filters.js"></script>

<!-- Enhanced Edit, Delete, and UI functionality -->
<script src="assets/js/edit-bet.js"></script>
<script src="assets/js/quick-update.js"></script>
<script src="assets/js/delete-confirm.js"></script>
<script src="assets/js/edit-functions.js"></script>


<script>
// Function to show a temporary notification
function showNotification(message, type = 'success') {
    // Create notification element if it doesn't exist
    let notification = document.getElementById('temp-notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'temp-notification';
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.padding = '10px 15px';
        notification.style.borderRadius = '5px';
        notification.style.zIndex = '1000';
        notification.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
        notification.style.transition = 'opacity 0.5s ease-in-out';
        document.body.appendChild(notification);
    }
    
    // Set notification style based on type
    if (type === 'success') {
        notification.style.backgroundColor = '#4caf50';
        notification.style.color = 'white';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#f44336';
        notification.style.color = 'white';
    }
    
    // Set notification message
    notification.textContent = message;
    notification.style.opacity = '1';
    
    // Hide notification after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
    }, 3000);
}

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

// Add event delegation for edit buttons
document.addEventListener('DOMContentLoaded', function() {
    // For desktop view
    document.querySelector('.table-responsive').addEventListener('click', function(e) {
        if (e.target.closest('.edit-btn')) {
            e.preventDefault();
            const editBtn = e.target.closest('.edit-btn');
            const betId = editBtn.getAttribute('data-bet-id');
            if (betId) {
                toggleEditMode(betId);
            }
        }
    });
    
    // For mobile view
    document.querySelector('.table-responsive-mobile').addEventListener('click', function(e) {
        if (e.target.closest('.btn-info')) {
            e.preventDefault();
            const editBtn = e.target.closest('.btn-info');
            const betId = editBtn.getAttribute('data-bet-id');
            if (betId) {
                toggleEditMode(betId);
            }
        }
    });
});
</script>
</body>
</html>