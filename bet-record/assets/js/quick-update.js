/**
 * quick-update.js - Handles quick updates for bet outcomes
 * Allows users to quickly update a bet's outcome without opening the edit modal
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize quick update functionality
    setupQuickUpdate();
    
    // Setup table row highlighting
    highlightUpdatedRow();
    
    // Initialize view toggle
    setupViewToggle();
});

/**
 * Sets up quick update functionality
 */
function setupQuickUpdate() {
    // Attach event listener to container for event delegation
    document.addEventListener('click', function(e) {
        // Check if a quick update button was clicked
        if (e.target.closest('.quick-update-btn')) {
            const button = e.target.closest('.quick-update-btn');
            const betId = button.getAttribute('data-bet-id');
            const outcome = button.getAttribute('data-outcome');
            
            // Confirm the update if it's changing to Lost (potentially confusing for users)
            if (outcome === 'Lost' && !confirm('Are you sure you want to mark this bet as Lost?')) {
                return;
            }
            
            // Call the update function
            quickUpdateOutcome(betId, outcome);
        }
    });
}

/**
 * Performs a quick update of the bet outcome
 * @param {number} betId - The ID of the bet to update
 * @param {string} outcome - The new outcome value
 */
function quickUpdateOutcome(betId, outcome) {
    // Show loading overlay
    showLoadingOverlay();
    
    // Create form data for the request
    const formData = new FormData();
    formData.append('bet_id', betId);
    formData.append('outcome', outcome);
    formData.append('quick_update', 'true');
    
    // Send AJAX request to update outcome
    fetch('includes/quick_update.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        hideLoadingOverlay();
        
        if (data.success) {
            // Update UI to reflect the change
            updateBetUI(betId, outcome, data.returns);
            
            // Show success message
            showNotification('Bet outcome updated successfully', 'success');
        } else {
            // Show error message
            showNotification('Error updating bet outcome: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoadingOverlay();
        console.error('Error updating outcome:', error);
        showNotification('An error occurred while updating the bet outcome', 'error');
    });
}

/**
 * Updates the UI to reflect the new outcome
 * @param {number} betId - The ID of the bet
 * @param {string} outcome - The new outcome
 * @param {number} returns - The updated returns value
 */
function updateBetUI(betId, outcome, returns) {
    // Update desktop view
    const tableRow = document.getElementById(`bet-row-${betId}`);
    if (tableRow) {
        // Update outcome badge
        const badgeCell = tableRow.querySelector('td:nth-child(9)'); // Assuming outcome is the 9th column
        if (badgeCell) {
            // Remove existing badges
            const existingBadge = badgeCell.querySelector('.badge');
            if (existingBadge) {
                existingBadge.remove();
            }
            
            // Create and add new badge
            const badge = document.createElement('span');
            badge.className = `badge badge-${outcome.toLowerCase()}`;
            badge.textContent = outcome;
            badgeCell.appendChild(badge);
        }
        
        // Highlight the updated row
        tableRow.classList.add('row-highlight');
        setTimeout(() => {
            tableRow.classList.remove('row-highlight');
        }, 2000);
    }
    
    // Update mobile view
    const mobileCard = document.querySelector(`.bet-card[data-bet-id="${betId}"]`);
    if (mobileCard) {
        // Update outcome badge
        const badgeElement = mobileCard.querySelector('.badge');
        if (badgeElement) {
            // Remove existing classes
            badgeElement.classList.remove('badge-won', 'badge-lost', 'badge-pending', 'badge-void');
            // Add new class
            badgeElement.classList.add(`badge-${outcome.toLowerCase()}`);
            badgeElement.textContent = outcome;
        }
        
        // Update data attribute
        mobileCard.setAttribute('data-outcome', outcome.toLowerCase());
        
        // Highlight the card
        mobileCard.classList.add('row-highlight');
        setTimeout(() => {
            mobileCard.classList.remove('row-highlight');
        }, 2000);
    }
    
    // Update stats if available (this would require a page refresh for full accuracy)
    // For now, we can show a refresh button
    const statsContainer = document.getElementById('statsContainer');
    if (statsContainer) {
        const refreshBtn = document.createElement('button');
        refreshBtn.className = 'btn btn-sm btn-outline-primary refresh-stats-btn';
        refreshBtn.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Stats';
        refreshBtn.addEventListener('click', function() {
            window.location.reload();
        });
        
        // Add the button if it doesn't exist
        if (!document.querySelector('.refresh-stats-btn')) {
            statsContainer.prepend(refreshBtn);
        }
    }
}

/**
 * Highlights a row if it was recently updated
 */
function highlightUpdatedRow() {
    // Check if there's a hash in the URL that matches updated-record-{id}
    const hash = window.location.hash;
    if (hash && hash.includes('updated-record-')) {
        const betId = hash.replace('#updated-record-', '');
        const tableRow = document.getElementById(`bet-row-${betId}`);
        const mobileCard = document.querySelector(`.bet-card[data-bet-id="${betId}"]`);
        
        // Highlight the row/card
        if (tableRow) {
            tableRow.classList.add('row-highlight');
            // Scroll to the row
            tableRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            setTimeout(() => {
                tableRow.classList.remove('row-highlight');
            }, 3000);
        }
        
        if (mobileCard) {
            mobileCard.classList.add('row-highlight');
            if (!tableRow) { // Only scroll if table row doesn't exist (mobile view)
                mobileCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            setTimeout(() => {
                mobileCard.classList.remove('row-highlight');
            }, 3000);
        }
    }
}

/**
 * Sets up the view toggle functionality
 */
function setupViewToggle() {
    // Check if toggle buttons exist
    const toggleContainer = document.querySelector('.view-toggle');
    if (!toggleContainer) {
        // Create toggle container and buttons
        const container = document.createElement('div');
        container.className = 'view-toggle';
        
        const tableBtn = document.createElement('button');
        tableBtn.innerHTML = '<i class="fas fa-table"></i> Table View';
        tableBtn.className = 'view-toggle-btn active';
        tableBtn.setAttribute('data-view', 'table');
        
        const cardBtn = document.createElement('button');
        cardBtn.innerHTML = '<i class="fas fa-th-large"></i> Card View';
        cardBtn.className = 'view-toggle-btn';
        cardBtn.setAttribute('data-view', 'card');
        
        container.appendChild(tableBtn);
        container.appendChild(cardBtn);
        
        // Find suitable place to insert toggle
        const filterContainer = document.querySelector('.filter-buttons');
        if (filterContainer) {
            filterContainer.parentNode.appendChild(container);
        }
        
        // Add event listeners
        tableBtn.addEventListener('click', () => toggleView('table'));
        cardBtn.addEventListener('click', () => toggleView('card'));
    }
}

/**
 * Toggles between table and card view
 * @param {string} view - The view to show ('table' or 'card')
 */
function toggleView(view) {
    const tableView = document.querySelector('.table-responsive-large');
    const cardView = document.querySelector('.table-responsive-mobile');
    const tableBtn = document.querySelector('[data-view="table"]');
    const cardBtn = document.querySelector('[data-view="card"]');
    
    if (view === 'table') {
        tableView.style.display = 'block';
        cardView.style.display = 'none';
        tableBtn.classList.add('active');
        cardBtn.classList.remove('active');
        localStorage.setItem('preferredView', 'table');
    } else {
        tableView.style.display = 'none';
        cardView.style.display = 'block';
        tableBtn.classList.remove('active');
        cardBtn.classList.add('active');
        localStorage.setItem('preferredView', 'card');
    }
}

/**
 * Shows a notification message
 * @param {string} message - The message to show
 * @param {string} type - The type of notification ('success', 'error', 'info')
 */
function showNotification(message, type = 'info') {
    // Create notification element if it doesn't exist
    let notification = document.getElementById('notification');
    if (!notification) {
        notification = document.createElement('div');
        notification.id = 'notification';
        document.body.appendChild(notification);
        
        // Add styles for notification
        const style = document.createElement('style');
        style.textContent = `
            #notification {
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 20px;
                border-radius: 5px;
                color: white;
                font-weight: 500;
                z-index: 1000;
                opacity: 0;
                transform: translateY(-10px);
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            #notification.show {
                opacity: 1;
                transform: translateY(0);
            }
            #notification.success {
                background-color: #28a745;
            }
            #notification.error {
                background-color: #dc3545;
            }
            #notification.info {
                background-color: #17a2b8;
            }
        `;
        document.head.appendChild(style);
    }
    
    // Set notification content and type
    notification.textContent = message;
    notification.className = type;
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Hide after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

/**
 * Shows a loading overlay
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

// Make functions available globally
window.quickUpdateOutcome = quickUpdateOutcome;
window.toggleView = toggleView;
window.showNotification = showNotification;