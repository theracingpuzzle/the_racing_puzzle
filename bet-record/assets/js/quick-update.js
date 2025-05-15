// Quick update functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all quick update buttons
    const quickUpdateBtns = document.querySelectorAll('.quick-update-btn');
    
    // Add click event to quick update buttons
    quickUpdateBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const betId = this.getAttribute('data-bet-id');
            const outcome = this.getAttribute('data-outcome');
            
            // Create form data
            const formData = new FormData();
            formData.append('id', betId);
            formData.append('outcome', outcome);
            
            // Send AJAX request
            fetch('includes/quick_update.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the UI
                    updateBetOutcomeUI(betId, outcome);
                    
                    // Show success message (optional)
                    // alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the bet outcome.');
            });
        });
    });
    
    // Function to update the UI after a successful outcome change
    function updateBetOutcomeUI(betId, outcome) {
        // Update desktop view
        const betRow = document.getElementById(`bet-row-${betId}`);
        if (betRow) {
            const outcomeCell = betRow.querySelector('.col-outcome');
            if (outcomeCell) {
                // Remove existing badge
                const existingBadge = outcomeCell.querySelector('.modern-badge');
                if (existingBadge) {
                    existingBadge.remove();
                }
                
                // Remove quick update buttons
                const quickUpdate = outcomeCell.querySelector('.quick-update');
                if (quickUpdate) {
                    quickUpdate.remove();
                }
                
                // Add new badge
                let badgeClass = '';
                if (outcome === 'Won') {
                    badgeClass = 'badge-won';
                } else if (outcome === 'Lost') {
                    badgeClass = 'badge-lost';
                } else if (outcome === 'Pending') {
                    badgeClass = 'badge-pending';
                } else {
                    badgeClass = 'badge-void';
                }
                
                const badge = document.createElement('span');
                badge.className = `modern-badge ${badgeClass}`;
                badge.textContent = outcome;
                outcomeCell.prepend(badge);
            }
        }
        
        // Update mobile view
        const betCard = document.querySelector(`.bet-card[data-bet-id="${betId}"]`);
        if (betCard) {
            // Update data attribute
            betCard.setAttribute('data-outcome', outcome.toLowerCase());
            
            // Update badge in header
            const cardHeader = betCard.querySelector('.bet-card-header');
            if (cardHeader) {
                const existingBadge = cardHeader.querySelector('.badge');
                if (existingBadge) {
                    existingBadge.remove();
                }
                
                let badgeClass = '';
                if (outcome === 'Won') {
                    badgeClass = 'badge-success';
                } else if (outcome === 'Lost') {
                    badgeClass = 'badge-danger';
                } else if (outcome === 'Pending') {
                    badgeClass = 'badge-warning';
                } else {
                    badgeClass = 'badge-info';
                }
                
                const badge = document.createElement('span');
                badge.className = `badge ${badgeClass}`;
                badge.textContent = outcome;
                cardHeader.appendChild(badge);
            }
            
            // Remove quick update buttons if they exist
            const quickUpdate = betCard.querySelector('.quick-update');
            if (quickUpdate) {
                quickUpdate.remove();
            }
        }
    }
});