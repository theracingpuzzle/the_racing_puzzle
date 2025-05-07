/**
 * edit-bet.js - Handles the edit functionality for bet records
 * Allows users to edit existing bets and update their outcomes
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('edit-bet.js loaded');
    // Set up edit mode functionality
    setupEditMode();
    
    // Setup delete confirmation
    setupDeleteConfirmation();
});

/**
 * Sets up the edit mode functionality
 */
function setupEditMode() {
    console.log('Setting up edit mode');
    // Find all edit buttons in both desktop and mobile views
    const editButtons = document.querySelectorAll('.btn-info, .edit-btn');
    console.log('Found edit buttons:', editButtons.length);
    
    editButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Edit button clicked:', this);
            // Get bet ID from the button's data attribute or from onclick attribute
            let betId;
            if (this.hasAttribute('data-bet-id')) {
                betId = this.getAttribute('data-bet-id');
            } else {
                // Try to extract from onclick attribute if data attribute not present
                const onclickAttr = this.getAttribute('onclick');
                if (onclickAttr && onclickAttr.includes('toggleEditMode')) {
                    const match = onclickAttr.match(/toggleEditMode\s*\(\s*(\d+)\s*\)/);
                    if (match && match[1]) {
                        betId = match[1];
                    }
                }
            }
            
            console.log('Extracted bet ID:', betId);
            
            if (betId) {
                // Fetch bet data and populate the betting modal
                fetchBetData(betId);
            } else {
                console.error('Could not determine bet ID');
                alert('Error: Could not determine which bet to edit.');
            }
        });
    });
}

/**
 * Fetches bet data for editing
 * @param {number} betId - The ID of the bet to edit
 */
function fetchBetData(betId) {
    console.log('Fetching bet data for ID:', betId);
    
    // Show loading overlay
    showLoadingOverlay();
    
    // Fetch the bet data using AJAX
    const url = `includes/get_bet.php?id=${betId}`;
    console.log('Fetch URL:', url);
    
    fetch(url)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Open and populate the betting modal with existing data
                populateBettingModal(data.bet, betId);
            } else {
                console.error('Error loading bet data:', data.message);
                alert("Error loading bet data: " + data.message);
                hideLoadingOverlay();
            }
        })
        .catch(error => {
            console.error('Error fetching bet data:', error);
            alert("An error occurred while loading the bet data.");
            hideLoadingOverlay();
        });
}

/**
 * Populates the betting modal with existing bet data
 * @param {Object} bet - The bet data to populate
 * @param {number} betId - The ID of the bet being edited
 */
function populateBettingModal(bet, betId) {
    console.log('Populating modal with bet data:', bet);
    
    // Get the betting form
    const bettingForm = document.getElementById('bettingForm');
    
    // Create a hidden field for the bet ID
    let idField = document.getElementById('edit_bet_id');
    if (!idField) {
        idField = document.createElement('input');
        idField.type = 'hidden';
        idField.id = 'edit_bet_id';
        idField.name = 'bet_id';
        bettingForm.appendChild(idField);
    }
    idField.value = betId;
    
    // Set form to edit mode
    bettingForm.action = 'includes/update_bet.php';
    
    // Change submit button text
    const submitBtn = bettingForm.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Bet';
    }
    
    // Change modal title
    const modalTitle = document.querySelector('.betting-modal-header h2');
    if (modalTitle) {
        modalTitle.innerHTML = '<i class="fas fa-edit"></i> Edit Betting Information';
    }
    
    // Populate form fields
    console.log('Populating form fields');
    
    const betTypeSelect = document.getElementById('bet_type');
    const stakeInput = document.getElementById('stake');
    const selectionInput = document.getElementById('selection_0');
    const racecourseInput = document.getElementById('racecourse_0');
    const jockeyInput = document.getElementById('jockey_0');
    const trainerInput = document.getElementById('trainer_0');
    const oddsInput = document.getElementById('odds_0');
    const outcomeSelect = document.getElementById('outcome');
    
    if (betTypeSelect) betTypeSelect.value = bet.bet_type || '';
    if (stakeInput) stakeInput.value = bet.stake || '';
    if (selectionInput) selectionInput.value = bet.selection || '';
    if (racecourseInput) racecourseInput.value = bet.racecourse || '';
    if (jockeyInput) jockeyInput.value = bet.jockey || '';
    if (trainerInput) trainerInput.value = bet.trainer || '';
    if (oddsInput) oddsInput.value = bet.odds || '';
    if (outcomeSelect) outcomeSelect.value = bet.outcome || '';
    
    console.log('Form fields populated');
    
    // Handle multi-selection bets if needed
    if (bet.bet_type === 'Accumulator' || bet.bet_type === 'Cover/Insure') {
        // Show selections count group
        const selectionsCountGroup = document.getElementById('selectionsCountGroup');
        if (selectionsCountGroup) {
            selectionsCountGroup.style.display = 'block';
        }
        
        // TODO: Handle additional selections if present in the bet data
        // This would need additional server-side support to retrieve multiple selections
    }
    
    // Update outcome badges
    updateOutcomeBadges(bet.outcome);
    
    // Calculate returns
    if (window.calculateReturns) {
        calculateReturns();
    }
    
    // Show the modal
    const modal = document.getElementById('bettingModal');
    if (modal) {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
    }
    
    // Hide loading overlay
    hideLoadingOverlay();
}

/**
 * Updates the outcome badges to highlight the current outcome
 * @param {string} outcome - The current outcome
 */
function updateOutcomeBadges(outcome) {
    const badges = document.querySelectorAll('.outcome-badges .badge');
    badges.forEach(badge => {
        const badgeOutcome = badge.classList.contains(`badge-${outcome.toLowerCase()}`);
        badge.style.opacity = badgeOutcome ? '1' : '0.3';
    });
}

/**
 * Shows a loading overlay while fetching data
 */
function showLoadingOverlay() {
    // Create loading overlay if it doesn't exist
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading...</span>
            </div>
        `;
        document.body.appendChild(overlay);
        
        // Add styles for the overlay
        const style = document.createElement('style');
        style.textContent = `
            #loadingOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 2000;
            }
            .loading-spinner {
                background-color: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .loading-spinner i {
                font-size: 2rem;
                margin-bottom: 10px;
                color: var(--primary-color);
            }
        `;
        document.head.appendChild(style);
    }
    
    overlay.style.display = 'flex';
}

/**
 * Hides the loading overlay
 */
function hideLoadingOverlay() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

/**
 * Sets up confirmation for delete actions
 */
function setupDeleteConfirmation() {
    console.log('Setting up delete confirmation');
}

// Make functions available globally
window.toggleEditMode = function(betId) {
    console.log('toggleEditMode called with ID:', betId);
    // Ensure betId is properly formatted
    betId = parseInt(betId, 10);
    if (isNaN(betId) || betId <= 0) {
        console.error('Invalid bet ID:', betId);
        alert('Invalid bet ID. Please try again.');
        return;
    }
    
    fetchBetData(betId);
};