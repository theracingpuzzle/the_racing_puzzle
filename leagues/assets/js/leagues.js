// leagues.js
// Main JavaScript file for leagues functionality

document.addEventListener('DOMContentLoaded', () => {
    // Initialize UI components
    initializeModals();
    setupEventListeners();
    
    // Check if we need to load a specific league
    const urlParams = new URLSearchParams(window.location.search);
    const leagueId = urlParams.get('league_id');
    
    if (leagueId) {
        // Refresh rankings if viewing a league
        const refreshBtn = document.getElementById('refreshRankingsBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                refreshRankings(leagueId);
            });
        }
    }
    
    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// Initialize all modals
function initializeModals() {
    // Create League Modal
    const createLeagueBtn = document.getElementById('createLeagueBtn');
    if (createLeagueBtn) {
        createLeagueBtn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('createLeagueModal'));
            modal.show();
        });
    }
    
    // Join League Modal
    const joinLeagueBtn = document.getElementById('joinLeagueBtn');
    if (joinLeagueBtn) {
        joinLeagueBtn.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('joinLeagueModal'));
            modal.show();
        });
    }
    
    // Edit League Modal
    const editLeagueBtn = document.getElementById('editLeagueBtn');
    if (editLeagueBtn) {
        editLeagueBtn.addEventListener('click', function() {
            // Populate form with league data from data attributes
            document.getElementById('edit_league_id').value = this.getAttribute('data-league-id');
            document.getElementById('edit_league_name').value = this.getAttribute('data-league-name');
            document.getElementById('edit_league_description').value = this.getAttribute('data-league-description');
            
            const rankingType = this.getAttribute('data-ranking-type');
            if (rankingType === 'winners') {
                document.getElementById('edit_ranking_winners').checked = true;
            } else {
                document.getElementById('edit_ranking_returns').checked = true;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('editLeagueModal'));
            modal.show();
        });
    }
    
    // Invite Members Modal
    const inviteMembersBtn = document.getElementById('inviteMembersBtn');
    if (inviteMembersBtn) {
        inviteMembersBtn.addEventListener('click', () => {
            // Clear any existing emails
            document.getElementById('emailList').innerHTML = '';
            document.getElementById('invite_email').value = '';
            
            // Reset send button
            const sendBtn = document.getElementById('sendInvitesBtn');
            if (sendBtn) {
                sendBtn.disabled = true;
            }
            
            const modal = new bootstrap.Modal(document.getElementById('inviteMembersModal'));
            modal.show();
        });
    }
}

// Set up event listeners for forms and buttons
function setupEventListeners() {
    // Create League Form
    const createLeagueForm = document.getElementById('createLeagueForm');
    if (createLeagueForm) {
        createLeagueForm.addEventListener('submit', function(event) {
            event.preventDefault();
            createLeague();
        });
    }
    
    // Join League Form
    const joinLeagueForm = document.getElementById('joinLeagueForm');
    if (joinLeagueForm) {
        joinLeagueForm.addEventListener('submit', function(event) {
            event.preventDefault();
            joinLeague();
        });
    }
    
    // Edit League Form
    const editLeagueForm = document.getElementById('editLeagueForm');
    if (editLeagueForm) {
        editLeagueForm.addEventListener('submit', function(event) {
            event.preventDefault();
            updateLeague();
        });
    }
    
    // Copy PIN buttons
    const copyPinBtns = document.querySelectorAll('[id$="CopyPinBtn"]');
    copyPinBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const pin = this.getAttribute('data-pin');
            copyToClipboard(pin);
            showNotification('PIN copied to clipboard!', 'success');
        });
    });
    
    // Export Rankings Button
    const exportRankingsBtn = document.getElementById('exportRankingsBtn');
    if (exportRankingsBtn) {
        exportRankingsBtn.addEventListener('click', exportRankings);
    }
    
    // Invite Members Email Functionality
    const addEmailBtn = document.getElementById('addEmailBtn');
    if (addEmailBtn) {
        addEmailBtn.addEventListener('click', addEmailToList);
    }
    
    const inviteEmailInput = document.getElementById('invite_email');
    if (inviteEmailInput) {
        inviteEmailInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEmailToList();
            }
        });
    }
    
    const sendInvitesBtn = document.getElementById('sendInvitesBtn');
    if (sendInvitesBtn) {
        sendInvitesBtn.addEventListener('click', sendInvites);
    }
}

// CRUD Operations

// Create a new league
function createLeague() {
    const name = document.getElementById('league_name').value.trim();
    const description = document.getElementById('league_description').value.trim();
    const rankingType = document.querySelector('input[name="ranking_type"]:checked').value;
    
    // Client-side validation
    if (!name) {
        showNotification('League name is required', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#createLeagueForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitBtn.disabled = true;
    
    // Prepare data
    const data = {
        name: name,
        description: description,
        ranking_type: rankingType
    };
    
    // Send AJAX request
    fetch('league-api.php?action=create_league', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Reset form
        document.getElementById('createLeagueForm').reset();
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('createLeagueModal'));
        modal.hide();
        
        showNotification(`League "${name}" created successfully! League PIN: ${data.pin}`, 'success');
        
        // Navigate to the new league
        window.location.href = `?league_id=${data.league_id}`;
    })
    .catch(error => {
        showNotification('Failed to create league: ' + error.message, 'error');
        console.error('Create league error:', error);
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Join an existing league
function joinLeague() {
    const pin = document.getElementById('league_pin').value.trim();
    
    // Client-side validation
    if (!pin) {
        showNotification('League PIN is required', 'error');
        return;
    }
    
    if (!pin.match(/^\d{6}$/)) {
        showNotification('PIN must be a 6-digit number', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#joinLeagueForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Joining...';
    submitBtn.disabled = true;
    
    // Prepare data
    const data = {
        pin: pin
    };
    
    // Send AJAX request
    fetch('league-api.php?action=join_league', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        if (data.success) {
            // Reset form
            document.getElementById('joinLeagueForm').reset();
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('joinLeagueModal'));
            modal.hide();
            
            showNotification('Successfully joined the league!', 'success');
            
            // Navigate to the joined league
            window.location.href = `?league_id=${data.league_id}`;
        } else {
            showNotification(data.message, 'warning');
        }
    })
    .catch(error => {
        showNotification('Failed to join league: ' + error.message, 'error');
        console.error('Join league error:', error);
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Update an existing league
function updateLeague() {
    const leagueId = document.getElementById('edit_league_id').value;
    const name = document.getElementById('edit_league_name').value.trim();
    const description = document.getElementById('edit_league_description').value.trim();
    const rankingType = document.querySelector('input[name="edit_ranking_type"]:checked').value;
    
    // Client-side validation
    if (!leagueId || !name) {
        showNotification('League ID and name are required', 'error');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#editLeagueForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitBtn.disabled = true;
    
    // Prepare data
    const data = {
        id: leagueId,
        name: name,
        description: description,
        ranking_type: rankingType
    };
    
    // Send AJAX request
    fetch('league-api.php?action=update_league', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        // Close modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('editLeagueModal'));
        modal.hide();
        
        showNotification('League updated successfully!', 'success');
        
        // Reload the page to reflect changes
        window.location.reload();
    })
    .catch(error => {
        showNotification('Failed to update league: ' + error.message, 'error');
        console.error('Update league error:', error);
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Refresh league rankings
function refreshRankings(leagueId) {
    const rankingsContainer = document.querySelector('.league-rankings');
    if (!rankingsContainer) return;
    
    // Show loading indicator
    const tableContainer = rankingsContainer.querySelector('.table-responsive');
    if (tableContainer) {
        tableContainer.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i> Loading rankings...</div>';
    }
    
    // Send AJAX request
    fetch(`league-api.php?action=get_rankings&league_id=${leagueId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        
        updateRankingsTable(data);
    })
    .catch(error => {
        if (tableContainer) {
            tableContainer.innerHTML = `<div class="alert alert-danger">Error loading rankings: ${error.message}</div>`;
        }
        console.error('Load rankings error:', error);
    });
}

// Update the rankings table with new data
function updateRankingsTable(data) {
    const rankingsContainer = document.querySelector('.league-rankings');
    if (!rankingsContainer) return;
    
    const tableContainer = rankingsContainer.querySelector('.table-responsive');
    if (!tableContainer) return;
    
    // Update ranking type in header
    const rankingTypeSpan = rankingsContainer.querySelector('.ranking-type');
    if (rankingTypeSpan) {
        rankingTypeSpan.textContent = `(by ${data.rankingType === 'winners' ? 'Number of Winners' : 'Return on Investment'})`;
    }
    
    // Generate rankings table
    if (data.rankings.length === 0) {
        tableContainer.innerHTML = `
            <div class="empty-state text-center p-4 border rounded bg-light">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <h4>No betting activity yet</h4>
                <p class="text-muted">Be the first to place bets and appear on the leaderboard!</p>
                <a href="../racing/index.php" class="btn btn-primary mt-2">
                    <i class="fas fa-horse"></i> Place Your First Bet
                </a>
            </div>
        `;
    } else {
        // Get current user ID
        const userId = getCurrentUserId();
        
        // Generate the table HTML
        let tableHtml = `
            <table class="rankings-table table table-striped table-hover">
                <thead>
                    <tr>
                        <th width="10%">Rank</th>
                        <th width="30%">Member</th>
        `;
        
        if (data.rankingType === 'winners') {
            tableHtml += `
                <th width="20%">Wins</th>
                <th width="20%">Total Bets</th>
                <th width="20%">Win %</th>
            `;
        } else {
            tableHtml += `
                <th width="20%">Returns</th>
                <th width="20%">Total Stake</th>
                <th width="20%">ROI %</th>
            `;
        }
        
        tableHtml += `
                    </tr>
                </thead>
                <tbody>
        `;
        
        // Add each member row
        let rank = 1;
        data.rankings.forEach(member => {
            tableHtml += `
                <tr class="${member.user_id == userId ? 'current-user' : ''}">
                    <td>${rank++}</td>
                    <td>
                        ${escapeHtml(member.username)}
                        ${member.user_id == userId ? '<span class="badge bg-warning text-dark ms-1">You</span>' : ''}
                    </td>
            `;
            
            if (data.rankingType === 'winners') {
                tableHtml += `
                    <td>${member.wins}</td>
                    <td>${member.total_bets}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: ${member.win_percentage}%;" 
                                aria-valuenow="${member.win_percentage}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                ${member.win_percentage}%
                            </div>
                        </div>
                    </td>
                `;
            } else {
                const roiClass = member.roi_percentage >= 0 ? 'bg-success' : 'bg-danger';
                const roiWidth = Math.min(Math.abs(member.roi_percentage), 100);
                
                tableHtml += `
                    <td>$${formatNumber(member.total_returns)}</td>
                    <td>$${formatNumber(member.total_stake)}</td>
                    <td>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar ${roiClass}" role="progressbar" 
                                style="width: ${roiWidth}%;" 
                                aria-valuenow="${member.roi_percentage}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">
                                ${member.roi_percentage}%
                            </div>
                        </div>
                    </td>
                `;
            }
            
            tableHtml += `</tr>`;
        });
        
        tableHtml += `
                </tbody>
            </table>
            <div class="text-end mt-3">
                <a href="../racing/index.php" class="btn btn-primary">
                    <i class="fas fa-horse"></i> Place New Bets
                </a>
            </div>
        `;
        
        tableContainer.innerHTML = tableHtml;
    }
}