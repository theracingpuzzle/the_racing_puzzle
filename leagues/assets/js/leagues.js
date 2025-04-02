/**
 * league.js - JavaScript functionality for the Horse Racing Leagues feature
 */

document.addEventListener('DOMContentLoaded', function() {
    // Modal elements
    const createLeagueModal = document.getElementById('createLeagueModal');
    const joinLeagueModal = document.getElementById('joinLeagueModal');
    const editLeagueModal = document.getElementById('editLeagueModal');
    
    // Buttons
    const createLeagueBtn = document.getElementById('createLeagueBtn');
    const joinLeagueBtn = document.getElementById('joinLeagueBtn');
    const editLeagueBtn = document.getElementById('editLeagueBtn');
    const copyPinBtn = document.getElementById('copyPinBtn');
    
    // Close buttons
    const closeButtons = document.getElementsByClassName('close');
    
    // Open Create League modal
    if (createLeagueBtn) {
        createLeagueBtn.addEventListener('click', function() {
            createLeagueModal.style.display = 'block';
        });
    }
    
    // Open Join League modal
    if (joinLeagueBtn) {
        joinLeagueBtn.addEventListener('click', function() {
            joinLeagueModal.style.display = 'block';
        });
    }
    
    // Open Edit League modal
    if (editLeagueBtn) {
        editLeagueBtn.addEventListener('click', function() {
            const leagueId = this.getAttribute('data-league-id');
            
            // Fetch league data
            fetch('api/leagues.php?id=' + leagueId)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const league = data.league;
                        
                        // Populate form fields
                        document.getElementById('edit_league_id').value = league.id;
                        document.getElementById('edit_league_name').value = league.name;
                        document.getElementById('edit_league_description').value = league.description;
                        
                        // Set ranking type radio button
                        if (league.ranking_type === 'winners') {
                            document.getElementById('edit_ranking_winners').checked = true;
                        } else {
                            document.getElementById('edit_ranking_returns').checked = true;
                        }
                        
                        // Show modal
                        editLeagueModal.style.display = 'block';
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error fetching league data:', error);
                    showNotification('Could not fetch league data. Please try again.', 'error');
                });
        });
    }
    
    // Copy league PIN to clipboard
    if (copyPinBtn) {
        copyPinBtn.addEventListener('click', function() {
            const pin = this.getAttribute('data-pin');
            
            navigator.clipboard.writeText(pin).then(() => {
                // Show success message
                const originalText = this.textContent;
                this.textContent = 'Copied!';
                
                // Revert button text after a delay
                setTimeout(() => {
                    this.textContent = originalText;
                }, 2000);
            }).catch(err => {
                console.error('Could not copy text: ', err);
                showNotification('Failed to copy PIN. Please try again.', 'error');
            });
        });
    }
    
    // Handle modal close buttons
    Array.from(closeButtons).forEach(button => {
        button.addEventListener('click', function() {
            createLeagueModal.style.display = 'none';
            joinLeagueModal.style.display = 'none';
            editLeagueModal.style.display = 'none';
        });
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(event) {
        if (event.target === createLeagueModal) {
            createLeagueModal.style.display = 'none';
        }
        if (event.target === joinLeagueModal) {
            joinLeagueModal.style.display = 'none';
        }
        if (event.target === editLeagueModal) {
            editLeagueModal.style.display = 'none';
        }
    });
    
    // Handle edit league form submission
    const editLeagueForm = document.getElementById('editLeagueForm');
    if (editLeagueForm) {
        editLeagueForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('api/update_league.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Close modal
                    editLeagueModal.style.display = 'none';
                    
                    // Show success notification
                    showNotification('League updated successfully!', 'success');
                    
                    // Reload page to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error updating league:', error);
                showNotification('Failed to update league. Please try again.', 'error');
            });
        });
    }
    
    // Function to show notifications
    function showNotification(message, type = 'success') {
        // Check if notification container exists, if not create it
        let notificationContainer = document.querySelector('.notification-container');
        
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.className = 'notification-container';
            document.body.appendChild(notificationContainer);
            
            // Style the notification container
            notificationContainer.style.position = 'fixed';
            notificationContainer.style.bottom = '20px';
            notificationContainer.style.right = '20px';
            notificationContainer.style.zIndex = '1000';
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'notification ' + type;
        notification.innerHTML = message;
        
        // Style the notification
        notification.style.backgroundColor = type === 'success' ? '#e8f5e9' : '#ffebee';
        notification.style.color = type === 'success' ? '#2e7d32' : '#c62828';
        notification.style.border = type === 'success' ? '1px solid #2e7d32' : '1px solid #c62828';
        notification.style.borderRadius = '4px';
        notification.style.padding = '12px 16px';
        notification.style.marginTop = '10px';
        notification.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        notification.style.transition = 'all 0.3s ease';
        notification.style.opacity = '0';
        
        // Add notification to container
        notificationContainer.appendChild(notification);
        
        // Show notification with animation
        setTimeout(() => {
            notification.style.opacity = '1';
        }, 10);
        
        // Remove notification after a delay
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    // Animate league items when page loads
    const leagueItems = document.querySelectorAll('.league-item');
    leagueItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(10px)';
        item.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            item.style.opacity = '1';
            item.style.transform = 'translateY(0)';
        }, 100 + (index * 50));
    });
    
    // Handle league form validation
    const leagueForms = document.querySelectorAll('form');
    leagueForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const nameField = this.querySelector('input[name$="league_name"]');
            
            if (nameField && nameField.value.trim() === '') {
                e.preventDefault();
                nameField.style.borderColor = '#c62828';
                
                // Show validation message
                let validationMsg = nameField.parentNode.querySelector('.validation-msg');
                if (!validationMsg) {
                    validationMsg = document.createElement('div');
                    validationMsg.className = 'validation-msg';
                    validationMsg.style.color = '#c62828';
                    validationMsg.style.fontSize = '0.85rem';
                    validationMsg.style.marginTop = '0.5rem';
                    nameField.parentNode.appendChild(validationMsg);
                }
                validationMsg.textContent = 'League name is required';
                
                // Clear validation message when user starts typing
                nameField.addEventListener('input', function() {
                    this.style.borderColor = '';
                    if (validationMsg) {
                        validationMsg.textContent = '';
                    }
                });
            }
        });
    });
    
    // Highlight current user's position in rankings
    const userRow = document.querySelector('.rankings-table .current-user');
    if (userRow) {
        // Scroll to the user's position if not in visible area
        const tableContainer = document.querySelector('.league-rankings');
        const rowPosition = userRow.offsetTop - tableContainer.offsetTop;
        
        if (rowPosition > tableContainer.clientHeight) {
            setTimeout(() => {
                userRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 1000);
        }
        
        // Add highlight animation
        setTimeout(() => {
            userRow.style.transition = 'background-color 1s ease';
            userRow.style.backgroundColor = '#fff8e1';
            
            setTimeout(() => {
                userRow.style.backgroundColor = '#fffde7';
            }, 1000);
        }, 800);
    }
    
    // Handle alerts auto-dismissal
    const alerts = document.querySelectorAll('.alert');
    if (alerts.length > 0) {
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    alert.style.opacity = '0';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 5000);
            }, 300);
        });
    }
    
    // League stats refresh functionality
    const refreshButton = document.createElement('button');
    if (document.querySelector('.league-rankings')) {
        refreshButton.className = 'btn btn-sm';
        refreshButton.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> Refresh';
        refreshButton.style.marginLeft = '10px';
        
        const rankingsHeader = document.querySelector('.league-rankings h3');
        rankingsHeader.appendChild(refreshButton);
        
        refreshButton.addEventListener('click', function() {
            // Get current league ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            const leagueId = urlParams.get('league_id');
            
            if (leagueId) {
                // Show loading indicator
                this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="spin"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg> Loading...';
                this.disabled = true;
                
                // Add spin animation
                const spinIcon = this.querySelector('.spin');
                if (spinIcon) {
                    spinIcon.style.animation = 'spin 1s linear infinite';
                }
                
                // Create a style element for the animation if it doesn't exist
                if (!document.getElementById('spin-animation')) {
                    const style = document.createElement('style');
                    style.id = 'spin-animation';
                    style.innerHTML = '@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }';
                    document.head.appendChild(style);
                }
                
                // Fetch updated rankings
                fetch('api/refresh_rankings.php?league_id=' + leagueId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Reload the page to show updated rankings
                            window.location.reload();
                        } else {
                            showNotification(data.message, 'error');
                            // Reset button
                            this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> Refresh';
                            this.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Error refreshing rankings:', error);
                        showNotification('Failed to refresh rankings. Please try again.', 'error');
                        // Reset button
                        this.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 4v6h-6"></path><path d="M1 20v-6h6"></path><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> Refresh';
                        this.disabled = false;
                    });
            }
        });
    }
});