// Basic edit functionality
function toggleEditMode(betId) {
    alert("Edit mode toggle for bet ID: " + betId);
    
    // Open the modal
    const modal = document.getElementById('bettingModal');
    if (modal) {
        modal.classList.add('open');
        
        // Set the title
        const title = modal.querySelector('.betting-modal-header h2');
        if (title) {
            title.innerHTML = '<i class="fas fa-edit"></i> Edit Bet #' + betId;
        }
        
        // Create a simple form to edit the bet
        const form = document.getElementById('bettingForm');
        if (form) {
            // Add a hidden input for the bet ID
            let idInput = document.getElementById('edit_bet_id');
            if (!idInput) {
                idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.id = 'edit_bet_id';
                idInput.name = 'id';
                form.appendChild(idInput);
            }
            idInput.value = betId;
            
            // Update form action
            form.action = 'includes/edit_bet.php';
            
            // Fetch the bet details
            fetch('get_bet.php?id=' + betId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.bet) {
                        // Populate the form
                        document.getElementById('bet_type').value = data.bet.bet_type;
                        document.getElementById('stake').value = data.bet.stake;
                        document.getElementById('selection_0').value = data.bet.selection;
                        document.getElementById('racecourse_0').value = data.bet.racecourse;
                        document.getElementById('odds_0').value = data.bet.odds;
                        document.getElementById('jockey_0').value = data.bet.jockey;
                        document.getElementById('trainer_0').value = data.bet.trainer;
                        document.getElementById('outcome').value = data.bet.outcome;
                        
                        // Update submit button
                        const submitBtn = document.querySelector('.btn-submit');
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Bet';
                        }
                    } else {
                        alert('Error: ' + (data.message || 'Failed to get bet details'));
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
        } else {
            alert('Error: Betting form not found!');
        }
    } else {
        alert('Error: Betting modal not found!');
    }
}