// Edit bet functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all edit buttons
    const editBtns = document.querySelectorAll('.edit-btn');
    
    // Add click event to edit buttons
    editBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const betId = this.getAttribute('data-bet-id');
            if (betId) {
                getBetDetails(betId);
            } else {
                console.error('No bet ID found on edit button');
            }
        });
    });
    
    // Function to fetch bet details and open the modal
    function getBetDetails(betId) {
        // Try to get bet data from the row first
        const betRow = document.getElementById(`bet-row-${betId}`);
        const betCard = document.querySelector(`.bet-card[data-bet-id="${betId}"]`);
        
        if (betRow) {
            // Extract data from the row
            try {
                const betData = {
                    id: betId,
                    bet_type: betRow.querySelector('[data-label="Bet Type"]')?.textContent.trim() || '',
                    stake: betRow.querySelector('[data-label="Stake"]')?.textContent.trim().replace('£', '') || '0',
                    selection: betRow.querySelector('[data-label="Selection"]')?.textContent.trim() || '',
                    racecourse: betRow.querySelector('[data-label="Racecourse"]')?.textContent.trim() || '',
                    odds: betRow.querySelector('[data-label="Odds"]')?.textContent.trim() || '',
                    jockey: betRow.querySelector('[data-label="Jockey"]')?.textContent.trim() || '',
                    trainer: betRow.querySelector('[data-label="Trainer"]')?.textContent.trim() || '',
                    outcome: betRow.querySelector('[data-label="Outcome"] .modern-badge')?.textContent.trim() || 'Pending'
                };
                
                // Open the betting modal and populate with data
                openBettingModalWithData(betData);
            } catch (error) {
                console.error("Error extracting data from row:", error);
                // If there's an error extracting from the row, try fetching from the server
                fetchBetDataFromServer(betId);
            }
        } 
        // If no row is found (possible on mobile), or if data extraction fails, fetch from server
        else if (betCard || !betRow) {
            fetchBetDataFromServer(betId);
        }
    }
    
    // Function to fetch bet data from the server
    function fetchBetDataFromServer(betId) {
        fetch(`get_bet.php?id=${betId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.bet) {
                    openBettingModalWithData(data.bet);
                } else {
                    alert('Error: ' + (data.message || 'Failed to get bet details'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while getting the bet details.');
            });
    }
    
    // Function to populate modal with bet data
    function openBettingModalWithData(betData) {
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
            modalTitle.innerHTML = `<i class="fas fa-edit"></i> Edit Bet #${betData.id}`;
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
        idInput.value = betData.id;
        
        // Update form action to edit endpoint
        const form = document.getElementById('bettingForm');
        if (form) {
            form.action = 'includes/edit_bet.php';
        }
        
        // Populate form fields
        const bet_type = document.getElementById('bet_type');
        if (bet_type) bet_type.value = betData.bet_type;
        
        const stake = document.getElementById('stake');
        if (stake) stake.value = betData.stake;
        
        const selection = document.getElementById('selection_0');
        if (selection) selection.value = betData.selection;
        
        const racecourse = document.getElementById('racecourse_0');
        if (racecourse) racecourse.value = betData.racecourse;
        
        const odds = document.getElementById('odds_0');
        if (odds) odds.value = betData.odds;
        
        const jockey = document.getElementById('jockey_0');
        if (jockey) jockey.value = betData.jockey;
        
        const trainer = document.getElementById('trainer_0');
        if (trainer) trainer.value = betData.trainer;
        
        const outcome = document.getElementById('outcome');
        if (outcome) outcome.value = betData.outcome;
        
        // Update submit button text
        const submitBtn = document.querySelector('.btn-submit');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Bet';
        }
        
        // Calculate potential returns if the function exists
        if (typeof updatePotentialReturns === 'function') {
            updatePotentialReturns();
        }
    }
    
    // Update form submission to handle AJAX
    const bettingForm = document.getElementById('bettingForm');
    if (bettingForm) {
        bettingForm.addEventListener('submit', function(e) {
            // Check if this is an edit operation
            const betId = document.getElementById('edit_bet_id');
            if (betId && betId.value) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(bettingForm);
                
                // Send AJAX request
                fetch('includes/edit_bet.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        document.getElementById('bettingModal').classList.remove('open');
                        
                        // Show success message
                        alert(data.message);
                        
                        // Reload page to show updated data
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Failed to update bet'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the bet.');
                });
            }
        });
    }
    
    // Function to close modal and reset form
    function closeBettingModal() {
        const modal = document.getElementById('bettingModal');
        if (!modal) return;
        
        modal.classList.remove('open');
        
        // Reset form title and action
        const modalTitle = modal.querySelector('.betting-modal-header h2');
        if (modalTitle) {
            modalTitle.innerHTML = '<i class="fas fa-ticket-alt"></i> Betting Information';
        }
        
        // Reset form
        const form = document.getElementById('bettingForm');
        if (form) {
            form.reset();
            form.action = 'includes/process_bet.php';
        }
        
        // Remove bet ID if it exists
        const betId = document.getElementById('edit_bet_id');
        if (betId) {
            betId.remove();
        }
        
        // Reset submit button text
        const submitBtn = document.querySelector('.btn-submit');
        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Bet';
        }
    }
    
    // Close modal when cancel button is clicked
    const cancelBtn = document.getElementById('cancelButton');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeBettingModal();
        });
    }
    
    // Close modal when X is clicked
    const closeBtn = document.querySelector('.betting-modal-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeBettingModal();
        });
    }
    
    // Function that can be called from outside to open edit mode for a bet
    window.toggleEditMode = function(betId) {
        if (betId) {
            getBetDetails(betId);
        }
    };
});