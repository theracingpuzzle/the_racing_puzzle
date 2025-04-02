<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Leagues</title>
    <link rel="stylesheet" href="assets/css/leagues.css">

     <!-- Link to Sidebar CSS -->
     <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
    
    <main class="container">
        <h1>Horse Racing Leagues</h1>
        
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
                                    <a href="league.php?league_id=<?php echo $league['id']; ?>">
                                        <?php echo htmlspecialchars($league['name']); ?>
                                        <span class="league-creator-badge">Creator</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            
                            <?php foreach($joined_leagues as $league): ?>
                                <li class="league-item <?php echo (isset($_GET['league_id']) && $_GET['league_id'] == $league['id']) ? 'active' : ''; ?>">
                                    <a href="league.php?league_id=<?php echo $league['id']; ?>">
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
                                <button id="copyPinBtn" class="btn btn-sm" data-pin="<?php echo $selected_league['pin']; ?>">
                                    Copy PIN
                                </button>
                                <button id="editLeagueBtn" class="btn btn-sm" data-league-id="<?php echo $selected_league['id']; ?>">
                                    Edit League
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
                            <table class="rankings-table">
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
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
    
    <!-- Create League Modal -->
    <div id="createLeagueModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Create New League</h2>
            <form action="league.php" method="POST">
                <div class="form-group">
                    <label for="league_name">League Name</label>
                    <input type="text" id="league_name" name="league_name" required>
                </div>
                <div class="form-group">
                    <label for="league_description">Description (Optional)</label>
                    <textarea id="league_description" name="league_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Ranking Type</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="ranking_type" value="winners" checked> 
                            Number of Winners
                        </label>
                        <label>
                            <input type="radio" name="ranking_type" value="returns"> 
                            Return on Investment
                        </label>
                    </div>
                </div>
                <button type="submit" name="create_league" class="btn btn-primary">Create League</button>
            </form>
        </div>
    </div>
    
    <!-- Join League Modal -->
    <div id="joinLeagueModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Join League</h2>
            <form action="league.php" method="POST">
                <div class="form-group">
                    <label for="league_pin">Enter League PIN</label>
                    <input type="text" id="league_pin" name="league_pin" required>
                </div>
                <button type="submit" name="join_league" class="btn btn-primary">Join League</button>
            </form>
        </div>
    </div>
    
    <!-- Edit League Modal -->
    <div id="editLeagueModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit League</h2>
            <form action="league.php" method="POST" id="editLeagueForm">
                <input type="hidden" id="edit_league_id" name="edit_league_id">
                <div class="form-group">
                    <label for="edit_league_name">League Name</label>
                    <input type="text" id="edit_league_name" name="edit_league_name" required>
                </div>
                <div class="form-group">
                    <label for="edit_league_description">Description</label>
                    <textarea id="edit_league_description" name="edit_league_description" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Ranking Type</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="edit_ranking_type" value="winners" id="edit_ranking_winners"> 
                            Number of Winners
                        </label>
                        <label>
                            <input type="radio" name="edit_ranking_type" value="returns" id="edit_ranking_returns"> 
                            Return on Investment
                        </label>
                    </div>
                </div>
                <button type="submit" name="update_league" class="btn btn-primary">Update League</button>
            </form>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>

    <?php include_once "../includes/sidebar.php"; ?>

<!-- Link to sidebar JavaScript -->
<script src="../assets/js/sidebar.js"></script>
    
 <script src="assets/js/leagues.js"></script>
</body>
</html>