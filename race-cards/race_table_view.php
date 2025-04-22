<?php
/**
 * race_table_view.php
 * Table view component for horse racing runners
 * To be included in the main racecard2.php file
 */
?>

<!-- Table View for Runners -->
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
                        <img src="<?php echo htmlspecialchars($runner['silk_url']); ?>" onerror="this.src='/api/placeholder/30/30'" alt="Racing Silks" class="table-silks">
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


<style>
/* Table View Styling */
.runners-table {
    width: 100%;
    overflow-x: auto;
    margin: 15px 0;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.race-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
    background-color: #ffffff;
}

.race-table th {
    background-color: #f5f7fa;
    color: #556677;
    font-weight: 600;
    text-align: left;
    padding: 12px 8px;
    border-bottom: 2px solid #ebeef2;
    position: sticky;
    top: 0;
    z-index: 10;
}

.race-table td {
    padding: 12px 8px;
    border-bottom: 1px solid #ebeef2;
    vertical-align: middle;
}

.runner-row:hover {
    background-color: #f9fafc;
}

.number-col {
    width: 40px;
    text-align: center;
    font-weight: 600;
}

.draw-col {
    width: 50px;
    text-align: center;
}

.draw-indicator {
    background-color: #e6e9ed;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    font-weight: 600;
    color: #444;
}

.silks-col {
    width: 40px;
    text-align: center;
}

.table-silks {
    width: 30px;
    height: 30px;
    object-fit: contain;
}

.horse-col {
    width: 180px;
    font-weight: 600;
}

.form-col {
    width: 80px;
}

.form-display {
    font-family: 'Roboto Mono', monospace;
    letter-spacing: 2px;
}

.jockey-col, .trainer-col {
    width: 150px;
}

.age-col, .weight-col {
    width: 80px;
    text-align: center;
}

.odds-col {
    width: 70px;
    text-align: center;
}

.odds-display {
    font-weight: 600;
    color: #2563eb;
}

.actions-col {
    width: 100px;
    text-align: right;
}

.table-actions {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    gap: 5px;
}

.table-action-btn {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
    color: #64748b;
    border-radius: 4px;
    transition: background-color 0.2s, color 0.2s;
}

.table-action-btn:hover {
    background-color: #f1f5f9;
    color: #334155;
}

@media (max-width: 768px) {
    .race-table {
        font-size: 12px;
    }
    
    .jockey-col, .trainer-col {
        display: none;
    }
    
    .table-silks {
        width: 25px;
        height: 25px;
    }
    
    .horse-col {
        max-width: 120px;
    }
}

/* Mobile scrollable table */
@media (max-width: 576px) {
    .runners-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .form-col, .age-col {
        display: none;
    }
}
</style>

<script>
/**
 * JavaScript for toggling between Card and Table views
 * To be integrated with your existing JavaScript
 */
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between card and table view
    const toggleButtons = document.querySelectorAll('.toggle-button');
    toggleButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            // Get the parent race card element
            const raceCard = this.closest('.race-card');
            
            // Find the runners grid and table within this race card
            const runnersGrid = raceCard.querySelector('.runners-grid');
            const runnersTable = raceCard.querySelector('.runners-table');
            
            // Remove active class from all buttons in this race card
            raceCard.querySelectorAll('.toggle-button').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Toggle view based on which button was clicked
            if (index === 0) { // Card View
                runnersGrid.style.display = 'grid';
                runnersTable.style.display = 'none';
            } else { // Table View
                runnersGrid.style.display = 'none';
                runnersTable.style.display = 'block';
            }
        });
    });
});
</script>