/**
 * modal.js - Handles the betting modal functionality
 * Responsible for opening, closing, and resetting the betting modal
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('bettingModal');
    const closeBtn = document.querySelector('.betting-modal-close');
    const cancelBtn = document.getElementById('cancelButton');
    const openModalBtn = document.getElementById('openModalBtn');
    
    // Add button click handler
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            resetForm();
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
        });
    }
    
    // Close button click handler
    if (closeBtn) {
        closeBtn.onclick = function() {
            closeModal();
        };
    }
    
    // Cancel button click handler
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            closeModal();
        };
    }
    
    // Close when clicking outside the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    };
    
    // Function to close the modal with animation
    function closeModal() {
        if (!modal) return;
        
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.opacity = '1';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }, 300);
    }
    
    // Function to reset form fields
    function resetForm() {
        const bettingForm = document.getElementById('bettingForm');
        const selectionsContainer = document.getElementById('selectionsContainer');
        const selectionsCountGroup = document.getElementById('selectionsCountGroup');
        
        if (!bettingForm || !selectionsContainer || !selectionsCountGroup) return;
        
        bettingForm.reset();
        
        // Reset to single selection
        while (selectionsContainer.children.length > 1) {
            selectionsContainer.removeChild(selectionsContainer.lastChild);
        }
        
        selectionsCountGroup.style.display = 'none';
        
        // Reset selection title
        const selectionTitle = document.querySelector('.selection-title');
        if (selectionTitle) {
            selectionTitle.textContent = 'Selection Details';
        }
        
        // Reset outcome badges
        const badges = document.querySelectorAll('.outcome-badges .badge');
        badges.forEach(badge => {
            if (badge.classList.contains('badge-pending')) {
                badge.style.opacity = '1';
            } else {
                badge.style.opacity = '0.3';
            }
        });
        
        // Reset potential returns
        const potentialReturnsEl = document.getElementById('potentialReturns');
        const potentialProfitEl = document.getElementById('potentialProfit');
        
        if (potentialReturnsEl) potentialReturnsEl.textContent = '£0.00';
        if (potentialProfitEl) potentialProfitEl.textContent = '£0.00';
    }
    
    // Add shake animation style for form validation
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }
    `;
    document.head.appendChild(styleSheet);
    
    // Expose functions to global scope for other scripts to use
    window.closeModal = closeModal;
    window.resetForm = resetForm;
});