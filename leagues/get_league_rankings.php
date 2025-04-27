<?php
// get_league_rankings.php - Fetch rankings for a specific league

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set content type to JSON for all responses
header('Content-Type: application/json');

// Verify user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'You must be logged in to view league rankings'
    ]);
    exit;
}

// Get the current user's ID from session
$user_id = $_SESSION['user_id'];

// Check for league_id parameter
if (!isset($_GET['league_id']) || !is_numeric($_GET['league_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'League ID is required'
    ]);
    exit;
}

$league_id = (int)$_GET['league_id'];

try {
    // Include database connection file for $db_path
    require_once "../includes/db-connection.php";
    
    // Create a new connection
    $db = new PDO('sqlite:' . $db_path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First, verify the user has access to this league
    $access_check = $db->prepare("
        SELECT COUNT(*) FROM league_members 
        WHERE league_id = ? AND user_id = ?
    ");
    $access_check->execute([$league_id, $user_id]);
    
    if ($access_check->fetchColumn() == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'You do not have access to this league'
        ]);
        exit;
    }
    
    // Get the league's ranking type
    $league_stmt = $db->prepare("
        SELECT ranking_type FROM leagues WHERE id = ?
    ");
    $league_stmt->execute([$league_id]);
    $league = $league_stmt->fetch();
    
    if (!$league) {
        echo json_encode([
            'success' => false,
            'message' => 'League not found'
        ]);
        exit;
    }
    
    $ranking_type = $league['ranking_type'];
    
    // Query for rankings - this will need to be modified based on your actual data structure
    if ($ranking_type === 'winners') {
        // For 'winners' ranking type, rank by number of winning bets
        $rankings_stmt = $db->prepare("
            SELECT 
                u.user_id,
                u.username,
                COUNT(CASE WHEN lb.is_winner = 1 THEN 1 END) as wins,
                COUNT(lb.id) as total_bets,
                CASE 
                    WHEN COUNT(lb.id) > 0 THEN (COUNT(CASE WHEN lb.is_winner = 1 THEN 1 END) * 100.0 / COUNT(lb.id))
                    ELSE 0 
                END as percentage,
                (u.user_id = ?) as is_current_user
            FROM 
                league_members lm
                JOIN users u ON lm.user_id = u.user_id
                LEFT JOIN league_bets lb ON lb.league_id = lm.league_id AND lb.user_id = lm.user_id
            WHERE 
                lm.league_id = ?
            GROUP BY 
                u.user_id, u.username
            ORDER BY 
                wins DESC, percentage DESC, total_bets DESC, u.username
        ");
        $rankings_stmt->execute([$user_id, $league_id]);
    } else {
        // For 'returns' ranking type, rank by ROI (return on investment)
        $rankings_stmt = $db->prepare("
            SELECT 
                u.user_id,
                u.username,
                SUM(lb.returns) as total_returns,
                SUM(lb.bet_amount) as total_stake,
                CASE 
                    WHEN SUM(lb.bet_amount) > 0 THEN ((SUM(lb.returns) - SUM(lb.bet_amount)) * 100.0 / SUM(lb.bet_amount))
                    ELSE 0 
                END as percentage,
                (u.user_id = ?) as is_current_user
            FROM 
                league_members lm
                JOIN users u ON lm.user_id = u.user_id
                LEFT JOIN league_bets lb ON lb.league_id = lm.league_id AND lb.user_id = lm.user_id
            WHERE 
                lm.league_id = ?
            GROUP BY 
                u.user_id, u.username
            ORDER BY 
                percentage DESC, total_returns DESC, u.username
        ");
        $rankings_stmt->execute([$user_id, $league_id]);
    }
    
    $rankings_data = $rankings_stmt->fetchAll();
    
    // Format the rankings for the response
    $rankings = [];
    $rank = 1;
    
    foreach ($rankings_data as $member) {
        // For winners ranking type
        if ($ranking_type === 'winners') {
            $rankings[] = [
                'rank' => $rank++,
                'userId' => (int)$member['user_id'],
                'username' => $member['username'],
                'isCurrentUser' => $member['is_current_user'] ? true : false,
                'wins' => (int)$member['wins'],
                'totalBets' => (int)$member['total_bets'],
                'percentage' => round($member['percentage'], 1)
            ];
        } else {
            // For returns ranking type
            $rankings[] = [
                'rank' => $rank++,
                'userId' => (int)$member['user_id'],
                'username' => $member['username'],
                'isCurrentUser' => $member['is_current_user'] ? true : false,
                'totalReturns' => (float)$member['total_returns'],
                'totalStake' => (float)$member['total_stake'],
                'percentage' => round($member['percentage'], 1)
            ];
        }
    }
    
    // Return rankings data
    echo json_encode([
        'success' => true,
        'league_id' => $league_id,
        'ranking_type' => $ranking_type,
        'rankings' => $rankings
    ]);
    
} catch (Exception $e) {
    // Log error
    error_log('Error fetching league rankings: ' . $e->getMessage());
    
    // Return error response
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>