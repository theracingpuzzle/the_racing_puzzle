// leagues-db.js
// Handles database operations for leagues

// Initialize SQLite database connection
const initLeaguesDB = () => {
    return new Promise((resolve, reject) => {
        // Use an established connection if you have a db.js module
        const db = window.db || new SQL.Database();
        
        // Execute the table creation SQL
        fetch('create-leagues-tables.sql')
            .then(response => response.text())
            .then(sql => {
                try {
                    db.exec(sql);
                    console.log("League tables initialized successfully");
                    resolve(db);
                } catch (err) {
                    console.error("Error initializing league tables:", err);
                    reject(err);
                }
            })
            .catch(err => {
                console.error("Error fetching SQL file:", err);
                reject(err);
            });
    });
};

// League Operations
const LeagueOperations = {
    // Create a new league
    createLeague: function(db, leagueData) {
        return new Promise((resolve, reject) => {
            try {
                // Generate a random 6-digit PIN
                const pin = Math.floor(100000 + Math.random() * 900000).toString();
                
                const stmt = db.prepare(`
                    INSERT INTO leagues (name, description, creator_id, ranking_type, pin)
                    VALUES (?, ?, ?, ?, ?)
                `);
                
                stmt.run([
                    leagueData.name,
                    leagueData.description || '',
                    leagueData.creator_id,
                    leagueData.ranking_type || 'winners',
                    pin
                ]);
                
                stmt.free();
                
                // Add creator as first member
                const memberId = this.getLastInsertId(db);
                this.addLeagueMember(db, memberId, leagueData.creator_id)
                    .then(() => {
                        resolve({
                            id: memberId,
                            pin: pin
                        });
                    })
                    .catch(err => reject(err));
                
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Get the last inserted ID
    getLastInsertId: function(db) {
        const stmt = db.prepare("SELECT last_insert_rowid() as id");
        const result = stmt.getAsObject();
        stmt.free();
        return result.id;
    },
    
    // Add a member to a league
    addLeagueMember: function(db, leagueId, userId) {
        return new Promise((resolve, reject) => {
            try {
                const stmt = db.prepare(`
                    INSERT INTO league_members (league_id, user_id)
                    VALUES (?, ?)
                `);
                
                stmt.run([leagueId, userId]);
                stmt.free();
                resolve(true);
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Join a league using PIN
    joinLeague: function(db, pin, userId) {
        return new Promise((resolve, reject) => {
            try {
                // First find the league by PIN
                const findStmt = db.prepare("SELECT id FROM leagues WHERE pin = ?");
                findStmt.bind([pin]);
                
                if (findStmt.step()) {
                    const leagueId = findStmt.getAsObject().id;
                    findStmt.free();
                    
                    // Check if user is already a member
                    const checkStmt = db.prepare(`
                        SELECT 1 FROM league_members 
                        WHERE league_id = ? AND user_id = ?
                    `);
                    checkStmt.bind([leagueId, userId]);
                    
                    const isMember = checkStmt.step();
                    checkStmt.free();
                    
                    if (isMember) {
                        resolve({ success: false, message: "You're already a member of this league" });
                    } else {
                        // Add user to league
                        this.addLeagueMember(db, leagueId, userId)
                            .then(() => {
                                resolve({ success: true, leagueId: leagueId });
                            })
                            .catch(err => reject(err));
                    }
                } else {
                    findStmt.free();
                    resolve({ success: false, message: "Invalid league PIN" });
                }
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Update league details
    updateLeague: function(db, leagueData) {
        return new Promise((resolve, reject) => {
            try {
                const stmt = db.prepare(`
                    UPDATE leagues 
                    SET name = ?, description = ?, ranking_type = ?
                    WHERE id = ? AND creator_id = ?
                `);
                
                stmt.run([
                    leagueData.name,
                    leagueData.description || '',
                    leagueData.ranking_type,
                    leagueData.id,
                    leagueData.creator_id
                ]);
                
                const changes = db.getRowsModified();
                stmt.free();
                
                if (changes > 0) {
                    resolve({ success: true });
                } else {
                    resolve({ 
                        success: false, 
                        message: "League not found or you don't have permission to edit it" 
                    });
                }
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Get leagues created by a user
    getCreatedLeagues: function(db, userId) {
        return new Promise((resolve, reject) => {
            try {
                const stmt = db.prepare(`
                    SELECT id, name, description, ranking_type, pin
                    FROM leagues
                    WHERE creator_id = ?
                    ORDER BY name
                `);
                
                const leagues = [];
                stmt.bind([userId]);
                
                while (stmt.step()) {
                    leagues.push(stmt.getAsObject());
                }
                
                stmt.free();
                resolve(leagues);
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Get leagues joined by a user (excluding ones they created)
    getJoinedLeagues: function(db, userId) {
        return new Promise((resolve, reject) => {
            try {
                const stmt = db.prepare(`
                    SELECT l.id, l.name, l.description, l.ranking_type
                    FROM leagues l
                    JOIN league_members lm ON l.id = lm.league_id
                    WHERE lm.user_id = ? AND l.creator_id != ?
                    ORDER BY l.name
                `);
                
                const leagues = [];
                stmt.bind([userId, userId]);
                
                while (stmt.step()) {
                    leagues.push(stmt.getAsObject());
                }
                
                stmt.free();
                resolve(leagues);
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Get single league details
    getLeagueDetails: function(db, leagueId, userId) {
        return new Promise((resolve, reject) => {
            try {
                // Check if user is a member of this league
                const memberStmt = db.prepare(`
                    SELECT 1 FROM league_members 
                    WHERE league_id = ? AND user_id = ?
                `);
                memberStmt.bind([leagueId, userId]);
                
                const isMember = memberStmt.step();
                memberStmt.free();
                
                if (!isMember) {
                    resolve({ error: "You are not a member of this league" });
                    return;
                }
                
                const stmt = db.prepare(`
                    SELECT id, name, description, creator_id, ranking_type, pin
                    FROM leagues
                    WHERE id = ?
                `);
                
                stmt.bind([leagueId]);
                
                if (stmt.step()) {
                    const league = stmt.getAsObject();
                    stmt.free();
                    resolve(league);
                } else {
                    stmt.free();
                    resolve({ error: "League not found" });
                }
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Get league rankings
    getLeagueRankings: function(db, leagueId) {
        return new Promise((resolve, reject) => {
            try {
                // First get the ranking type
                const typeStmt = db.prepare(`
                    SELECT ranking_type FROM leagues WHERE id = ?
                `);
                typeStmt.bind([leagueId]);
                
                if (!typeStmt.step()) {
                    typeStmt.free();
                    resolve({ error: "League not found" });
                    return;
                }
                
                const rankingType = typeStmt.getAsObject().ranking_type;
                typeStmt.free();
                
                let sql;
                
                if (rankingType === 'winners') {
                    // Rankings based on number of winners
                    sql = `
                        SELECT u.id as user_id, u.username, 
                            COUNT(CASE WHEN lb.is_winner = 1 THEN 1 END) as wins,
                            COUNT(lb.id) as total_bets,
                            CASE WHEN COUNT(lb.id) > 0 
                                THEN ROUND((COUNT(CASE WHEN lb.is_winner = 1 THEN 1 END) * 100.0 / COUNT(lb.id)), 1)
                                ELSE 0 
                            END as win_percentage
                        FROM users u
                        JOIN league_members lm ON u.id = lm.user_id
                        LEFT JOIN league_bets lb ON u.id = lb.user_id AND lb.league_id = lm.league_id
                        WHERE lm.league_id = ?
                        GROUP BY u.id, u.username
                        ORDER BY wins DESC, win_percentage DESC, total_bets DESC
                    `;
                } else {
                    // Rankings based on ROI
                    sql = `
                        SELECT u.id as user_id, u.username,
                            COALESCE(SUM(lb.returns), 0) as total_returns,
                            COALESCE(SUM(lb.bet_amount), 0) as total_stake,
                            CASE WHEN SUM(lb.bet_amount) > 0 
                                THEN ROUND(((SUM(lb.returns) - SUM(lb.bet_amount)) * 100.0 / SUM(lb.bet_amount)), 2)
                                ELSE 0 
                            END as roi_percentage
                        FROM users u
                        JOIN league_members lm ON u.id = lm.user_id
                        LEFT JOIN league_bets lb ON u.id = lb.user_id AND lb.league_id = lm.league_id
                        WHERE lm.league_id = ?
                        GROUP BY u.id, u.username
                        ORDER BY roi_percentage DESC, total_returns DESC
                    `;
                }
                
                const stmt = db.prepare(sql);
                stmt.bind([leagueId]);
                
                const rankings = [];
                while (stmt.step()) {
                    rankings.push(stmt.getAsObject());
                }
                
                stmt.free();
                resolve({ rankingType, rankings });
            } catch (err) {
                reject(err);
            }
        });
    },
    
    // Record a new bet
    recordBet: function(db, betData) {
        return new Promise((resolve, reject) => {
            try {
                // Check if user is in the league
                const checkStmt = db.prepare(`
                    SELECT 1 FROM league_members 
                    WHERE league_id = ? AND user_id = ?
                `);
                checkStmt.bind([betData.league_id, betData.user_id]);
                
                const isMember = checkStmt.step();
                checkStmt.free();
                
                if (!isMember) {
                    resolve({ success: false, message: "You are not a member of this league" });
                    return;
                }
                
                const stmt = db.prepare(`
                    INSERT INTO league_bets (
                        league_id, user_id, race_id, horse_id, 
                        bet_amount, odds, is_winner, returns
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                `);
                
                // Calculate returns if it's a winner
                let returns = 0;
                if (betData.is_winner) {
                    // Parse odds like "5/1" into a multiplier
                    const oddsParts = betData.odds.split('/');
                    if (oddsParts.length === 2) {
                        const numerator = parseInt(oddsParts[0]);
                        const denominator = parseInt(oddsParts[1]);
                        if (!isNaN(numerator) && !isNaN(denominator) && denominator > 0) {
                            // Return calculation: original stake + (stake * (odds numerator/denominator))
                            returns = betData.bet_amount + (betData.bet_amount * (numerator / denominator));
                        }
                    }
                }
                
                stmt.run([
                    betData.league_id,
                    betData.user_id,
                    betData.race_id,
                    betData.horse_id,
                    betData.bet_amount,
                    betData.odds,
                    betData.is_winner ? 1 : 0,
                    returns
                ]);
                
                stmt.free();
                resolve({ success: true, betId: this.getLastInsertId(db) });
            } catch (err) {
                reject(err);
            }
        });
    }
};

// Export the module
export { initLeaguesDB, LeagueOperations };