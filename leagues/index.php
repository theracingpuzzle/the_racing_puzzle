<?php

// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Continue with the rest of your dashboard code

// For proof of concept - simulate logged-in user
$user_id = 1; // Hardcoded user ID for demonstration
$username = "DemoUser";

// Initialize variables
$error = null;
$success = null;
$selected_league = null;
$created_leagues = [];
$joined_leagues = [];
$league_rankings = [];

// Sample data for demonstration
$created_leagues = [
    ['id' => 1, 'name' => 'Triple Crown League'],
    ['id' => 2, 'name' => 'Weekend Warriors']
];

$joined_leagues = [
    ['id' => 3, 'name' => 'Derby Enthusiasts'],
    ['id' => 4, 'name' => 'Preakness Picks']
];

// Handle GET request to view a league
if (isset($_GET['league_id'])) {
    $league_id = $_GET['league_id'];
    
    // For proof of concept - hardcoded league data
    if ($league_id == 1) {
        $selected_league = [
            'id' => 1,
            'name' => 'Triple Crown League',
            'description' => 'A league for tracking picks across the three races of the Triple Crown.',
            'creator_id' => 1, // Matches our demo user
            'ranking_type' => 'winners',
            'pin' => '123456'
        ];
        
        // Sample rankings data for winners
        $league_rankings = [
            [
                'user_id' => 1,
                'username' => 'DemoUser',
                'wins' => 12,
                'total_bets' => 25,
                'win_percentage' => 48.0
            ],
            [
                'user_id' => 2,
                'username' => 'HorseExpert',
                'wins' => 15,
                'total_bets' => 28,
                'win_percentage' => 53.6
            ],
            [
                'user_id' => 3,
                'username' => 'LuckyPick',
                'wins' => 8,
                'total_bets' => 20,
                'win_percentage' => 40.0
            ]
        ];
    } else if ($league_id == 2) {
        $selected_league = [
            'id' => 2,
            'name' => 'Weekend Warriors',
            'description' => 'Weekly competition on weekend races.',
            'creator_id' => 1, // Matches our demo user
            'ranking_type' => 'returns',
            'pin' => '654321'
        ];
        
        // Sample rankings data for ROI
        $league_rankings = [
            [
                'user_id' => 1,
                'username' => 'DemoUser',
                'total_returns' => 1250.00,
                'total_stake' => 1000.00,
                'roi_percentage' => 25.0
            ],
            [
                'user_id' => 4,
                'username' => 'BigBettor',
                'total_returns' => 3200.00,
                'total_stake' => 2500.00,
                'roi_percentage' => 28.0
            ],
            [
                'user_id' => 5,
                'username' => 'SafePlayer',
                'total_returns' => 850.00,
                'total_stake' => 800.00,
                'roi_percentage' => 6.25
            ]
        ];
    } else if ($league_id == 3) {
        $selected_league = [
            'id' => 3,
            'name' => 'Derby Enthusiasts',
            'description' => 'Focus on Kentucky Derby and related races.',
            'creator_id' => 5, // Another user is creator
            'ranking_type' => 'winners',
            'pin' => '246810'
        ];
        
        // Sample rankings data
        $league_rankings = [
            [
                'user_id' => 1,
                'username' => 'DemoUser',
                'wins' => 5,
                'total_bets' => 10,
                'win_percentage' => 50.0
            ],
            [
                'user_id' => 5,
                'username' => 'SafePlayer',
                'wins' => 8,
                'total_bets' => 12,
                'win_percentage' => 66.7
            ]
        ];
    } else if ($league_id == 4) {
        $selected_league = [
            'id' => 4,
            'name' => 'Preakness Picks',
            'description' => 'Dedicated to Preakness Stakes predictions.',
            'creator_id' => 4, // Another user is creator
            'ranking_type' => 'returns',
            'pin' => '135790'
        ];
        
        // Sample rankings data
        $league_rankings = [
            [
                'user_id' => 1,
                'username' => 'DemoUser',
                'total_returns' => 720.00,
                'total_stake' => 600.00,
                'roi_percentage' => 20.0
            ],
            [
                'user_id' => 4,
                'username' => 'BigBettor',
                'total_returns' => 950.00,
                'total_stake' => 800.00,
                'roi_percentage' => 18.75
            ]
        ];
    }
}

// Handle form submissions (just show success messages for demo)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create_league'])) {
        $success = "League created successfully! (Demo)";
    }
    
    if (isset($_POST['join_league'])) {
        $success = "Successfully joined the league! (Demo)";
    }
    
    if (isset($_POST['update_league'])) {
        $success = "League updated successfully! (Demo)";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Leagues</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
     <!-- Main CSS -->
     <link rel="stylesheet" href="../assets/css/main.css">  

    <!-- <link rel="stylesheet" href="assets/css/leagues.css"> -->
    
</head>
<body>

<?php include '../test/app-header.php'; ?>
    
    <main class="container">
        <h1 class="my-4">Horse Racing Leagues</h1>
        
        <?php if(isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if(isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <div class="league-container">
            <div class="league-sidebar">
                <div class="league-actions">
                    <button id="createLeagueBtn" class="btn btn-primary">Create New League</button>
                    <button id="joinLeagueBtn" class="btn btn-secondary">Join League</button>
                </div>
                
                <div class="league-list">
                    <h3>Your Leagues</h3>
                    <?php if(count($created_leagues) > 0 || count($joined_leagues) > 0): ?>
                        <ul>
                            <?php foreach($created_leagues as $league): ?>
                                <li class="league-item <?php echo (isset($_GET['league_id']) && $_GET['league_id'] == $league['id']) ? 'active' : ''; ?>">
                                    <a href="?league_id=<?php echo $league['id']; ?>">
                                        <?php echo htmlspecialchars($league['name']); ?>
                                        <span class="league-creator-badge">Creator</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            
                            <?php foreach($joined_leagues as $league): ?>
                                <li class="league-item <?php echo (isset($_GET['league_id']) && $_GET['league_id'] == $league['id']) ? 'active' : ''; ?>">
                                    <a href="?league_id=<?php echo $league['id']; ?>">
                                        <?php echo htmlspecialchars($league['name']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>You haven't created or joined any leagues yet.</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="league-content">
                <?php if($selected_league): ?>
                    <div class="league-header">
                        <h2><?php echo htmlspecialchars($selected_league['name']); ?></h2>
                        <p class="league-description"><?php echo htmlspecialchars($selected_league['description']); ?></p>
                        
                        <?php if($selected_league['creator_id'] == $user_id): ?>
                            <div class="league-admin">
                                <p>League PIN: <span class="league-pin"><?php echo $selected_league['pin']; ?></span></p>
                                <button id="copyPinBtn" class="btn btn-sm btn-outline-secondary" data-pin="<?php echo $selected_league['pin']; ?>">
                                    <i class="fas fa-copy"></i> Copy PIN
                                </button>
                                <button id="editLeagueBtn" class="btn btn-sm btn-outline-primary" data-league-id="<?php echo $selected_league['id']; ?>"
                                        data-league-name="<?php echo htmlspecialchars($selected_league['name']); ?>"
                                        data-league-description="<?php echo htmlspecialchars($selected_league['description']); ?>"
                                        data-ranking-type="<?php echo $selected_league['ranking_type']; ?>">
                                    <i class="fas fa-edit"></i> Edit League
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="league-rankings">
                        <h3>
                            League Rankings 
                            <span class="ranking-type">
                                (by <?php echo $selected_league['ranking_type'] == 'winners' ? 'Number of Winners' : 'Return on Investment'; ?>)
                            </span>
                        </h3>
                        
                        <?php if(count($league_rankings) > 0): ?>
                            <table class="rankings-table table table-striped">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Member</th>
                                        <?php if($selected_league['ranking_type'] == 'winners'): ?>
                                            <th>Wins</th>
                                            <th>Total Bets</th>
                                            <th>Win %</th>
                                        <?php else: ?>
                                            <th>Returns</th>
                                            <th>Total Stake</th>
                                            <th>ROI %</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $rank = 1; ?>
                                    <?php foreach($league_rankings as $member): ?>
                                        <tr class="<?php echo ($member['user_id'] == $user_id) ? 'current-user' : ''; ?>">
                                            <td><?php echo $rank++; ?></td>
                                            <td><?php echo htmlspecialchars($member['username']); ?></td>
                                            <?php if($selected_league['ranking_type'] == 'winners'): ?>
                                                <td><?php echo $member['wins']; ?></td>
                                                <td><?php echo $member['total_bets']; ?></td>
                                                <td><?php echo $member['win_percentage']; ?>%</td>
                                            <?php else: ?>
                                                <td>$<?php echo number_format($member['total_returns'], 2); ?></td>
                                                <td>$<?php echo number_format($member['total_stake'], 2); ?></td>
                                                <td><?php echo $member['roi_percentage']; ?>%</td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p>No betting activity recorded in this league yet.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="league-welcome">
                        <h2>Welcome to Horse Racing Leagues!</h2>
                        <p>Create your own league or join an existing one to compete with friends.</p>
                        <p>Track your betting performance and see who has the best horse racing instincts!</p>
                        <div class="mt-4">
                            <img src="https://via.placeholder.com/600x300?text=Horse+Racing+Leagues" alt="Horse Racing" class="img-fluid rounded">
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Create League Modal -->
    <div class="modal fade" id="createLeagueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New League</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="league_name" class="form-label">League Name</label>
                            <input type="text" class="form-control" id="league_name" name="league_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="league_description" class="form-label">Description (Optional)</label>
                            <textarea class="form-control" id="league_description" name="league_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ranking Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ranking_type" id="ranking_winners" value="winners" checked>
                                <label class="form-check-label" for="ranking_winners">
                                    Number of Winners
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="ranking_type" id="ranking_returns" value="returns">
                                <label class="form-check-label" for="ranking_returns">
                                    Return on Investment
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="create_league" class="btn btn-primary">Create League</button>
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
                    <h5 class="modal-title">Join League</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="league_pin" class="form-label">Enter League PIN</label>
                            <input type="text" class="form-control" id="league_pin" name="league_pin" required>
                        </div>
                        <button type="submit" name="join_league" class="btn btn-primary">Join League</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Edit League Modal -->
    <div class="modal fade" id="editLeagueModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit League</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST" id="editLeagueForm">
                        <input type="hidden" id="edit_league_id" name="edit_league_id">
                        <div class="mb-3">
                            <label for="edit_league_name" class="form-label">League Name</label>
                            <input type="text" class="form-control" id="edit_league_name" name="edit_league_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_league_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_league_description" name="edit_league_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ranking Type</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edit_ranking_type" id="edit_ranking_winners" value="winners">
                                <label class="form-check-label" for="edit_ranking_winners">
                                    Number of Winners
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="edit_ranking_type" id="edit_ranking_returns" value="returns">
                                <label class="form-check-label" for="edit_ranking_returns">
                                    Return on Investment
                                </label>
                            </div>
                        </div>
                        <button type="submit" name="update_league" class="btn btn-primary">Update League</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "../test/bottom-nav.php"; ?>
    
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    
    <script src="assets/js/leagues.js"></script>
</body>
</html>