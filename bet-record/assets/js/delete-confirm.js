// Delete confirmation functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get all delete buttons
    const deleteBtns = document.querySelectorAll('.delete-bet');
    
    // Add click event to delete buttons
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Extract bet ID from the URL
            const url = this.href;
            const betId = url.split('id=')[1];
            
            // Show confirmation dialog
            const confirmation = confirm('Are you sure you want to delete this bet record? This action cannot be undone.');
            
            if (confirmation) {
                // Send AJAX request to delete the bet
                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Remove the bet row from the desktop view
                        const betRow = document.getElementById(`bet-row-${betId}`);
                        if (betRow) {
                            betRow.remove();
                        }
                        
                        // Remove the bet card from the mobile view
                        const betCard = document.querySelector(`.bet-card[data-bet-id="${betId}"]`);
                        if (betCard) {
                            betCard.remove();
                        }
                        
                        // Show success message
                        alert(data.message);
                        
                        // Check if there are no more bets
                        const remainingRows = document.querySelectorAll('.modern-table tbody tr');
                        if (remainingRows.length === 0 || (remainingRows.length === 1 && remainingRows[0].querySelector('td[colspan]'))) {
                            // Add a "no records" row if not already there
                            const tbody = document.querySelector('.modern-table tbody');
                            if (tbody) {
                                // Check if there's already a "no records" row
                                const noRecordsRow = tbody.querySelector('td[colspan]');
                                if (!noRecordsRow) {
                                    tbody.innerHTML = '<tr><td colspan="10" class="text-center">No bet records found</td></tr>';
                                }
                            }
                            
                            // Also update mobile view if empty
                            const mobileContainer = document.querySelector('.table-responsive-mobile');
                            if (mobileContainer && mobileContainer.querySelectorAll('.bet-card').length === 0) {
                                mobileContainer.innerHTML = '<div class="alert alert-info">No bet records found</div>';
                            }
                        }
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the bet.');
                });
            }
        });
    });
});