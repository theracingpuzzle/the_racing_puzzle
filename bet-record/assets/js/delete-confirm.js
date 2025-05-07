/**
 * delete-confirm.js - Handles better confirmation dialogs for deleting bets
 */
document.addEventListener('DOMContentLoaded', function() {
    setupDeleteConfirmation();
});

/**
 * Sets up enhanced delete confirmation
 */
function setupDeleteConfirmation() {
    // Create the modal if it doesn't exist
    createConfirmModal();
    
    // Find all delete buttons in both desktop and mobile views
    const deleteButtons = document.querySelectorAll('.delete-bet');
    
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Get the delete URL and bet info
            const deleteUrl = this.getAttribute('href');
            
            // Find bet information
            let betInfo = '';
            
            // For table view
            const tableRow = this.closest('tr');
            if (tableRow) {
                const selection = tableRow.querySelector('td:nth-child(4)').textContent.trim();
                const racecourse = tableRow.querySelector('td:nth-child(5)').textContent.trim();
                const odds = tableRow.querySelector('td:nth-child(6)').textContent.trim();
                betInfo = `${selection} at ${racecourse} (${odds})`;
            }
            
            // For card view
            const card = this.closest('.bet-card');
            if (card) {
                const selectionEl = card.querySelector('.bet-info-group:nth-child(1) .bet-value');
                const racecourseEl = card.querySelector('.bet-info-group:nth-child(2) .bet-value');
                
                if (selectionEl && racecourseEl) {
                    const selection = selectionEl.textContent.trim();
                    const racecourse = racecourseEl.textContent.trim();
                    betInfo = `${selection} at ${racecourse}`;
                }
            }
            
            // Show the confirmation modal
            showConfirmModal(deleteUrl, betInfo);
        });
    });
}

/**
 * Creates the confirmation modal if it doesn't exist
 */
function createConfirmModal() {
    // Check if modal already exists
    if (document.getElementById('deleteConfirmModal')) {
        return;
    }
    
    // Create modal structure
    const modal = document.createElement('div');
    modal.id = 'deleteConfirmModal';
    modal.className = 'modal-overlay';
    modal.innerHTML = `
        <div class="confirm-modal">
            <div class="confirm-modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Deletion</h3>
                <button class="close-modal-btn"><i class="fas fa-times"></i></button>
            </div>
            <div class="confirm-modal-body">
                <p>Are you sure you want to delete this bet record?</p>
                <p class="bet-info"></p>
                <p class="text-danger"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="confirm-modal-footer">
                <button class="btn-cancel"><i class="fas fa-times"></i> Cancel</button>
                <button class="btn-confirm-delete"><i class="fas fa-trash"></i> Delete</button>
            </div>
        </div>
    `;
    
    // Add modal to body
    document.body.appendChild(modal);
    
    // Add styles for the modal
    const style = document.createElement('style');
    style.textContent = `
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        
        .confirm-modal {
            background-color: white;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            animation: modal-in 0.3s ease;
        }
        
        @keyframes modal-in {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        .confirm-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .confirm-modal-header h3 {
            margin: 0;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .confirm-modal-header h3 i {
            color: #dc3545;
        }
        
        .close-modal-btn {
            background: none;
            border: none;
            font-size: 1.2rem;
            cursor: pointer;
            color: #6c757d;
        }
        
        .confirm-modal-body {
            padding: 20px;
        }
        
        .bet-info {
            font-weight: 600;
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        
        .confirm-modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 15px 20px;
            border-top: 1px solid #e9ecef;
        }
        
        .btn-cancel {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-confirm-delete {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-cancel:hover, .btn-confirm-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        
        .text-danger {
            color: #dc3545;
        }
    `;
    document.head.appendChild(style);
    
    // Add event listeners for modal buttons
    const closeBtn = modal.querySelector('.close-modal-btn');
    const cancelBtn = modal.querySelector('.btn-cancel');
    const deleteBtn = modal.querySelector('.btn-confirm-delete');
    
    closeBtn.addEventListener('click', hideConfirmModal);
    cancelBtn.addEventListener('click', hideConfirmModal);
    
    // The delete button will have its URL set when shown
    deleteBtn.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        if (url) {
            window.location.href = url;
        }
    });
    
    // Close on click outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            hideConfirmModal();
        }
    });
}

/**
 * Shows the confirmation modal with specific bet info
 * @param {string} deleteUrl - The URL to redirect to if deletion is confirmed
 * @param {string} betInfo - Information about the bet to be deleted
 */
function showConfirmModal(deleteUrl, betInfo) {
    const modal = document.getElementById('deleteConfirmModal');
    if (!modal) return;
    
    // Set the bet info
    const betInfoEl = modal.querySelector('.bet-info');
    if (betInfoEl) {
        betInfoEl.textContent = betInfo;
    }
    
    // Set the delete URL
    const deleteBtn = modal.querySelector('.btn-confirm-delete');
    if (deleteBtn) {
        deleteBtn.setAttribute('data-url', deleteUrl);
    }
    
    // Show the modal
    modal.style.display = 'flex';
    
    // Prevent body scrolling
    document.body.style.overflow = 'hidden';
}

/**
 * Hides the confirmation modal
 */
function hideConfirmModal() {
    const modal = document.getElementById('deleteConfirmModal');
    if (modal) {
        modal.style.display = 'none';
    }
    
    // Re-enable body scrolling
    document.body.style.overflow = 'auto';
}

// Make functions available globally
window.setupDeleteConfirmation = setupDeleteConfirmation;
window.showConfirmModal = showConfirmModal;
window.hideConfirmModal = hideConfirmModal;