<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Leagues</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #1e5631; /* Racing green */
            --secondary-color: #8f2d56; /* Jockey silk accent */
            --accent-color: #d4af37; /* Winner's gold */
            --dark-bg: #1c1c1c;
            --light-bg: #f6f5f1; /* Off-white like racing forms */
            --border-color: #dddbd2;
            --text-dark: #333333;
            --text-light: #ffffff;
        }

        body {
            font-family: 'Source Sans Pro', 'Roboto', sans-serif;
            background-color: var(--light-bg);
            color: var(--text-dark);
            line-height: 1.6;
        }

        .app-header {
            background-color: var(--dark-bg);
            color: var(--text-light);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .logo-container h1 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin: 0;
            letter-spacing: -0.5px;
            color: var(--text-light);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #267b42;
            border-color: #267b42;
        }

        .btn-secondary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background-color: #7d2649;
            border-color: #7d2649;
        }

        .league-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .league-sidebar {
            width: 25%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .league-content {
            width: 75%;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .league-actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-bottom: 20px;
        }

        .league-list {
            margin-top: 20px;
        }

        .league-list h3 {
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .league-item {
            list-style: none;
            margin-bottom: 5px;
        }

        .league-item a {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            border-radius: 4px;
            color: var(--text-dark);
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .league-item a:hover {
            background-color: rgba(30, 86, 49, 0.1);
        }

        .league-item.active a {
            background-color: rgba(30, 86, 49, 0.2);
            font-weight: 600;
        }

        .league-category {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: var(--text-medium);
        }

        .rankings-table th {
            font-weight: 600;
            background-color: var(--light-bg);
        }

        .current-user {
            background-color: rgba(212, 175, 55, 0.1) !important;
        }

        .badge {
            font-size: 0.75rem;
        }

        .league-creator-badge {
            font-size: 0.7rem;
            padding: 2px 6px;
            background-color: var(--accent-color);
            color: var(--dark-bg);
            border-radius: 10px;
            font-weight: 600;
        }

        .league-welcome {
            padding: 30px;
        }

        .empty-state {
            text-align: center;
            padding: 20px;
            color: var(--text-medium);
        }

        /* Media Queries for Responsive Design */
        @media (max-width: 768px) {
            .league-container {
                flex-direction: column;
            }

            .league-sidebar, .league-content {
                width: 100%;
            }
        }
    </style>
</head>


<body>
<?php include '../test/app-header.php'; ?>
    
    <main class="container py-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1>Horse Racing Leagues</h1>
        </header>

        <!-- Notification area for messages -->
        <div id="notification-container"></div>

        <div class="league-container">
            <aside class="league-sidebar">
                <div class="league-actions">
                    <button id="createLeagueBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeagueModal">
                        <i class="fas fa-plus-circle me-2"></i>Create New League
                    </button>
                    <button id="joinLeagueBtn" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#joinLeagueModal">
                        <i class="fas fa-user-plus me-2"></i>Join League
                    </button>
                </div>
                
                <div class="league-list">
                    <h3><i class="fas fa-trophy me-2"></i>Your Leagues</h3>
                    <div id="userLeagues">
                        <!-- League items will be dynamically added here with JavaScript -->
                        <div class="empty-state">
                            <p class="text-muted">You haven't created or joined any leagues yet.</p>
                            <p>Get started by creating your first league or joining an existing one!</p>
                        </div>
                    </div>
                </div>
            </aside>

            <section class="league-content">
                <div id="leagueDetails">
                    <!-- League details will be shown here when selected -->
                    <div class="league-welcome text-center">
                        <div class="mb-4">
                            <i class="fas fa-trophy fa-5x text-warning"></i>
                        </div>
                        <h2>Welcome to Horse Racing Leagues!</h2>
                        <p class="lead">Compete with friends and track your betting performance.</p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                        <h3>Create a League</h3>
                                        <p>Start your own league and invite friends to compete.</p>
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createLeagueModal">
                                            Create Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-body text-center">
                                        <i class="fas fa-users fa-3x text-secondary mb-3"></i>
                                        <h3>Join a League</h3>
                                        <p>Have a league PIN? Join an existing league.</p>
                                        <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#joinLeagueModal">
                                            Join Now
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <h4>How it Works</h4>
                            <ul class="list-unstyled">
                                <li class="d-flex align-items-start mb-3">
                                    <span class="badge bg-primary rounded-circle me-3 p-2">1</span>
                                    <div class="text-start">
                                        <strong>Create or Join a League</strong> - Start your own league or join one with a PIN
                                    </div>
                                </li>
                                <li class="d-flex align-items-start mb-3">
                                    <span class="badge bg-primary rounded-circle me-3 p-2">2</span>
                                    <div class="text-start">
                                        <strong>Place Bets on Races</strong> - Your bets will count toward your league standings
                                    </div>
                                </li>
                                <li class="d-flex align-items-start">
                                    <span class="badge bg-primary rounded-circle me-3 p-2">3</span>
                                    <div class="text-start">
                                        <strong>Compete & Win</strong> - See who has the best horse racing predictions!
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <!-- Create League Modal -->
    <div class="modal fade" id="createLeagueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Create New League</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createLeagueForm">
                        <div class="mb-3">
                            <label for="league_name" class="form-label">League Name</label>
                            <input type="text" class="form-control" id="league_name" name="league_name" required 
                                placeholder="Enter a unique name for your league">
                            <div class="form-text">Choose a name that reflects your group or competition theme.</div>
                        </div>
                        <div class="mb-3">
                            <label for="league_description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="league_description" name="league_description" rows="3"
                                    placeholder="Describe your league's purpose or rules"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ranking Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ranking_type" id="ranking_winners" value="winners" checked>
                                <label class="form-check-label" for="ranking_winners">
                                    Number of Winners
                                    <small class="d-block text-muted">Rank members by how many winning horses they pick</small>
                                </label>
                            </div>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="ranking_type" id="ranking_returns" value="returns">
                                <label class="form-check-label" for="ranking_returns">
                                    Return on Investment
                                    <small class="d-block text-muted">Rank members by their betting profitability</small>
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create League</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Join League Modal -->
<div class="modal fade" id="joinLeagueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Join League</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="joinLeagueForm">
                    <div class="mb-3">
                        <label for="league_pin" class="form-label">Enter League PIN</label>
                        <input type="text" 
                               class="form-control" 
                               id="league_pin" 
                               name="league_pin" 
                               required
                               placeholder="Enter the 6-digit PIN" 
                               maxlength="6">
                        <div class="form-text">Ask the league creator for the PIN.</div>
                    </div>
                    <button type="submit" class="btn btn-primary">Join League</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- League Details Template (will be cloned and populated by JS) -->
    <template id="leagueDetailsTemplate">
        <div class="league-header">
            <h2 id="leagueName">League Name</h2>
            <p id="leagueDescription" class="league-description">League description goes here.</p>
            
            <div id="leagueAdminSection" class="league-admin mt-3 mb-4">
                <div class="d-flex align-items-center">
                    <span class="me-2">League PIN:</span>
                    <span id="leaguePin" class="badge bg-secondary">123456</span>
                    <button id="copyPinBtn" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="fas fa-copy"></i> Copy
                    </button>
                </div>
            </div>
        </div>
        
        <div class="league-rankings">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>
                    League Rankings
                    <span id="rankingType" class="ranking-type">
                        (by Number of Winners)
                    </span>
                </h3>
            </div>
            
            <div class="table-responsive">
                <table class="rankings-table table table-striped table-hover">
                    <thead>
                        <tr>
                            <th width="10%">Rank</th>
                            <th width="30%">Member</th>
                            <th width="20%" id="statsHeader1">Wins</th>
                            <th width="20%" id="statsHeader2">Total Bets</th>
                            <th width="20%" id="statsHeader3">Win %</th>
                        </tr>
                    </thead>
                    <tbody id="rankingsTableBody">
                        <!-- Rankings will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
            
            <div class="text-end mt-3">
                <button class="btn btn-primary">
                    <i class="fas fa-horse"></i> Place New Bets
                </button>
            </div>
        </div>
    </template>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    
    // Sample rankings data - this would be fetched from server in a full implementation
const sampleRankings = [
    { rank: 1, userId: 1, username: "JohnD", isCurrentUser: true, wins: 12, totalBets: 25, percentage: 48 },
    { rank: 2, userId: 2, username: "RacingQueen", isCurrentUser: false, wins: 10, totalBets: 22, percentage: 45 },
    { rank: 3, userId: 3, username: "HorseFan99", isCurrentUser: false, wins: 8, totalBets: 30, percentage: 27 }
];

// Global variables
let sampleLeagues = [];

// DOM elements
const userLeaguesElement = document.getElementById('userLeagues');
const leagueDetailsElement = document.getElementById('leagueDetails');
const leagueDetailsTemplate = document.getElementById('leagueDetailsTemplate');
const createLeagueForm = document.getElementById('createLeagueForm');
const joinLeagueForm = document.getElementById('joinLeagueForm');
const notificationContainer = document.getElementById('notification-container');

// Show notification
function showNotification(message, type = 'success') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'exclamation'}-circle me-2"></i> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    notificationContainer.appendChild(alertDiv);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Function to fetch user's leagues from the server
function fetchUserLeagues() {
    // Show loading state
    userLeaguesElement.innerHTML = `
        <div class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Loading your leagues...</p>
        </div>
    `;
    
    // Fetch leagues data from server
    fetch('get_user_leagues.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Store leagues data globally
                const createdLeagues = data.created_leagues.map(league => ({
                    id: parseInt(league.id),
                    name: league.name,
                    description: league.description,
                    isCreator: true,
                    pin: league.pin,
                    rankingType: league.ranking_type
                }));
                
                const joinedLeagues = data.joined_leagues.map(league => ({
                    id: parseInt(league.id),
                    name: league.name,
                    description: league.description,
                    isCreator: false,
                    creatorName: league.creator_name,
                    rankingType: league.ranking_type
                }));
                
                // Update global leagues array
                sampleLeagues = [...createdLeagues, ...joinedLeagues];
                
                // Render leagues list
                initLeaguesList();
                
                // If we have leagues and none selected yet, select the first one
                if (sampleLeagues.length > 0) {
                    showLeagueDetails(sampleLeagues[0].id);
                }
            } else {
                // Show error message
                userLeaguesElement.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        ${data.message || 'Failed to load leagues'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching leagues:', error);
            
            userLeaguesElement.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    Failed to load leagues. Please refresh the page.
                </div>
            `;
        });
}

// Function to fetch league details including rankings
function fetchLeagueRankings(leagueId) {
    const tableBody = document.querySelector('#rankingsTableBody');
    if (!tableBody) return;
    
    // Show loading state
    tableBody.innerHTML = `
        <tr>
            <td colspan="5" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading rankings...</span>
                </div>
                <p class="mt-2">Loading league rankings...</p>
            </td>
        </tr>
    `;
    
    // Fetch league rankings from server
    fetch(`get_league_rankings.php?league_id=${leagueId}`)
        .then(response => {
            if (!response.ok) {
                // For now, use sample data if endpoint doesn't exist
                return { json: () => Promise.resolve({ success: true, rankings: sampleRankings }) };
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Populate rankings table with fetched data
                populateRankingsTable(data.rankings, leagueId);
            } else {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center">
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                ${data.message || 'Failed to load rankings'}
                            </div>
                        </td>
                    </tr>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching rankings:', error);
            
            // Fallback to sample data for demo purposes
            populateRankingsTable(sampleRankings, leagueId);
        });
}

// Populate rankings table
function populateRankingsTable(rankings, leagueId) {
    const tableBody = document.querySelector('#rankingsTableBody');
    if (!tableBody) return;
    
    const league = sampleLeagues.find(l => l.id === leagueId);
    if (!league) return;
    
    tableBody.innerHTML = '';
    
    if (rankings.length === 0) {
        tableBody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4">
                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                    <h5>No data yet</h5>
                    <p class="text-muted">Be the first to place bets in this league!</p>
                </td>
            </tr>
        `;
        return;
    }
    
    rankings.forEach(member => {
        const row = document.createElement('tr');
        if (member.isCurrentUser) {
            row.classList.add('current-user');
        }
        
        let userCell = `<td>${member.username}</td>`;
        
        if (member.isCurrentUser) {
            userCell = `
                <td>
                    ${member.username}
                    <span class="badge bg-warning text-dark ms-1">You</span>
                </td>
            `;
        }

        // Different stats for different ranking types
        let statsHtml;
        if (league.rankingType === 'winners') {
            statsHtml = `
                <td>${member.wins}</td>
                <td>${member.totalBets}</td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: ${member.percentage}%;" 
                            aria-valuenow="${member.percentage}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            ${member.percentage}%
                        </div>
                    </div>
                </td>
            `;
        } else {
            // For Return on Investment ranking type
            statsHtml = `
                <td>$${(member.wins * 10).toFixed(2)}</td>
                <td>$${(member.totalBets * 2).toFixed(2)}</td>
                <td>
                    <div class="progress" style="height: 20px;">
                        <div class="progress-bar bg-${member.percentage > 0 ? 'success' : 'danger'}" role="progressbar" 
                            style="width: ${Math.abs(member.percentage)}%;" 
                            aria-valuenow="${member.percentage}" 
                            aria-valuemin="0" 
                            aria-valuemax="100">
                            ${member.percentage}%
                        </div>
                    </div>
                </td>
            `;
        }
        
        row.innerHTML = `
            <td>${member.rank}</td>
            ${userCell}
            ${statsHtml}
        `;
        
        tableBody.appendChild(row);
    });
}

// Initialize league list
function initLeaguesList() {
    if (sampleLeagues.length === 0) {
        userLeaguesElement.innerHTML = `
            <div class="empty-state">
                <p class="text-muted">You haven't created or joined any leagues yet.</p>
                <p>Get started by creating your first league or joining an existing one!</p>
            </div>
        `;
        return;
    }

    // Group leagues by created and joined
    const createdLeagues = sampleLeagues.filter(league => league.isCreator);
    const joinedLeagues = sampleLeagues.filter(league => !league.isCreator);
    
    let html = '<ul>';
    
    if (createdLeagues.length > 0) {
        html += '<li class="league-category">Leagues You Created</li>';
        createdLeagues.forEach(league => {
            html += `
                <li class="league-item" data-league-id="${league.id}">
                    <a href="#" onclick="showLeagueDetails(${league.id}); return false;">
                        <span>${league.name}</span>
                        <span class="league-creator-badge">Creator</span>
                    </a>
                </li>
            `;
        });
    }
    
    if (joinedLeagues.length > 0) {
        html += '<li class="league-category">Leagues You Joined</li>';
        joinedLeagues.forEach(league => {
            html += `
                <li class="league-item" data-league-id="${league.id}">
                    <a href="#" onclick="showLeagueDetails(${league.id}); return false;">
                        <span>${league.name}</span>
                    </a>
                </li>
            `;
        });
    }
    
    html += '</ul>';
    userLeaguesElement.innerHTML = html;
}

// Show league details
function showLeagueDetails(leagueId) {
    // Find selected league
    const league = sampleLeagues.find(l => l.id === leagueId);
    if (!league) return;

    // Mark the selected league as active in the sidebar
    document.querySelectorAll('.league-item').forEach(item => {
        item.classList.remove('active');
    });
    
    const leagueItem = document.querySelector(`.league-item[data-league-id="${leagueId}"]`);
    if (leagueItem) {
        leagueItem.classList.add('active');
    }

    // Clone the template
    const leagueDetailsNode = leagueDetailsTemplate.content.cloneNode(true);
    
    // Populate the template with league data
    leagueDetailsNode.querySelector('#leagueName').textContent = league.name;
    leagueDetailsNode.querySelector('#leagueDescription').textContent = league.description || 'No description provided.';
    
    const leagueAdminSection = leagueDetailsNode.querySelector('#leagueAdminSection');
    if (league.isCreator) {
        leagueDetailsNode.querySelector('#leaguePin').textContent = league.pin;
        
        // Set up copy pin button
        const copyPinBtn = leagueDetailsNode.querySelector('#copyPinBtn');
        copyPinBtn.addEventListener('click', () => {
            navigator.clipboard.writeText(league.pin)
                .then(() => {
                    showNotification('PIN copied to clipboard!');
                })
                .catch(err => {
                    console.error('Failed to copy PIN: ', err);
                    showNotification('Failed to copy PIN. Try selecting and copying manually.', 'warning');
                });
        });
    } else {
        leagueAdminSection.style.display = 'none';
    }
    
    // Update ranking type display
    const rankingTypeText = league.rankingType === 'winners' ? 'Number of Winners' : 'Return on Investment';
    leagueDetailsNode.querySelector('#rankingType').textContent = `(by ${rankingTypeText})`;
    
    // Update table headers based on ranking type
    if (league.rankingType === 'winners') {
        leagueDetailsNode.querySelector('#statsHeader1').textContent = 'Wins';
        leagueDetailsNode.querySelector('#statsHeader2').textContent = 'Total Bets';
        leagueDetailsNode.querySelector('#statsHeader3').textContent = 'Win %';
    } else {
        leagueDetailsNode.querySelector('#statsHeader1').textContent = 'Returns';
        leagueDetailsNode.querySelector('#statsHeader2').textContent = 'Total Stake';
        leagueDetailsNode.querySelector('#statsHeader3').textContent = 'ROI %';
    }
    
    // Clear previous content and add new content
    leagueDetailsElement.innerHTML = '';
    leagueDetailsElement.appendChild(leagueDetailsNode);
    
    // Fetch and populate rankings
    fetchLeagueRankings(leagueId);
}

// Handle create league form submission
createLeagueForm.addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Disable submit button to prevent double submission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating...';
    
    // Get form data
    const leagueName = document.getElementById('league_name').value;
    const leagueDescription = document.getElementById('league_description').value || '';
    const rankingType = document.querySelector('input[name="ranking_type"]:checked').value;
    
    // Create the data object to send to the server
    const formData = new FormData();
    formData.append('name', leagueName);
    formData.append('description', leagueDescription);
    formData.append('ranking_type', rankingType);
    
    // Send the data to the server using fetch API
    fetch('create_league.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createLeagueModal'));
            modal.hide();
            
            // Refresh leagues from server
            fetchUserLeagues();
            
            // Show success notification
            showNotification(`League "${leagueName}" created successfully!`);
        } else {
            // Show error message
            showNotification(data.message || 'Failed to create league. Please try again.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error creating league:', error);
        showNotification('An error occurred while creating the league. Please try again.', 'danger');
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = 'Create League';
        
        // Reset form
        createLeagueForm.reset();
    });
});

// Handle join league form submission
document.getElementById('joinLeagueForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Get PIN value
    const leaguePin = document.getElementById('league_pin').value;
    
    // Client-side validation
    if (!/^[0-9]{6}$/.test(leaguePin)) {
        showNotification('Please enter a valid 6-digit PIN.', 'danger');
        return;
    }
    
    // Disable submit button to prevent double submission
    const submitButton = this.querySelector('button[type="submit"]');
    submitButton.disabled = true;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Joining...';
    
    // Create form data
    const formData = new FormData();
    formData.append('pin', leaguePin);
    
    // Send the request to join the league
    fetch('join_league.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok: ' + response.statusText);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Close modal
            const modalElement = document.getElementById('joinLeagueModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            } else {
                // Fallback method
                document.querySelector('.modal-backdrop')?.remove();
                document.body.classList.remove('modal-open');
                modalElement.style.display = 'none';
            }
            
            // Refresh leagues from server
            fetchUserLeagues();
            
            // Show success notification
            showNotification(`Successfully joined league: ${data.league_name || 'New League'}`);
        } else {
            // Show error message
            showNotification(data.message || 'Failed to join league. Please check the PIN and try again.', 'danger');
        }
    })
    .catch(error => {
        console.error('Error joining league:', error);
        showNotification('An error occurred while joining the league. Please try again.', 'danger');
    })
    .finally(() => {
        // Re-enable submit button
        submitButton.disabled = false;
        submitButton.innerHTML = 'Join League';
        
        // Reset form
        this.reset();
    });
});

// Initialize when page loads
document.addEventListener('DOMContentLoaded', function() {
    // Fetch user leagues from server
    fetchUserLeagues();
    
    // Set up track restrictions checkbox
    const trackRestrictionsCheckbox = document.getElementById('track_restrictions');
    const trackSelectionDiv = document.getElementById('trackSelectionDiv');
    
    if (trackRestrictionsCheckbox && trackSelectionDiv) {
        trackRestrictionsCheckbox.addEventListener('change', function() {
            trackSelectionDiv.style.display = this.checked ? 'block' : 'none';
        });
    }
    
    // Make copy pin button work for dynamically added elements
    document.addEventListener('click', function(event) {
        if (event.target && event.target.id === 'copyPinBtn') {
            const pin = event.target.closest('.league-admin').querySelector('#leaguePin').textContent;
            navigator.clipboard.writeText(pin)
                .then(() => {
                    showNotification('PIN copied to clipboard!');
                })
                .catch(err => {
                    console.error('Failed to copy PIN: ', err);
                });
        }
    });
});
</script>
</body>
</html>