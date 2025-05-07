<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betting Modal Test</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="betting-modal.css">
    <style>
        
    </style>
</head>
<body>
    <div class="test-container">
        <h1>Betting Information System</h1>
        <p>Track your horse racing bets and analyze your performance over time.</p>
        
        <button id="openModalBtn" class="test-button">
            <i class="fas fa-plus-circle"></i> Add New Bet
        </button>
        
        <!-- Test form to show submission results -->
        <div id="formResults">
            <h3>Form Submission Results:</h3>
            <pre id="resultsContent"></pre>
        </div>
    </div>

    <!-- Betting Modal HTML Structure -->
    <div id="bettingModal" class="betting-modal">
        <div class="betting-modal-content">
            <div class="betting-modal-header">
                <h2><i class="fas fa-ticket-alt"></i> Betting Information</h2>
                <span class="betting-modal-close"><i class="fas fa-times"></i></span>
            </div>
            <div class="betting-modal-body">
                <form id="bettingForm" method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bet_type" class="required-field">Bet Type</label>
                            <select id="bet_type" name="bet_type" required>
                                <option value="Win">Win</option>
                                <option value="Place">Place</option>
                                <option value="Each Way">Each Way</option>
                                <option value="Accumulator">Accumulator</option>
                                <option value="Cover/Insure">Insure</option>
                            </select>
                            <div class="tooltip">
                                <i class="fas fa-info-circle"></i>
                                <span class="tooltip-text">Select the type of bet you're placing</span>
                            </div>
                        </div>
                        
                        <div class="form-group" id="selectionsCountGroup" style="display: none;">
                            <label for="numberOfSelections" class="required-field">Number of Selections</label>
                            <select id="numberOfSelections">
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                    </div>

                    <!-- Selection container - this will hold all selections -->
                    <div id="selectionsContainer">
                        <div class="selection-box" data-index="0">
                            <h3 class="selection-title">Selection Details</h3>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="selection_0" class="required-field">Horse Name</label>
                                    <input type="text" id="selection_0" name="selection" required placeholder="Enter horse name">
                                </div>
                                <div class="form-group">
                                    <label for="racecourse_0" class="required-field">Racecourse</label>
                                    <input type="text" id="racecourse_0" name="racecourse" required placeholder="Enter racecourse">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="jockey_0">Jockey</label>
                                    <input type="text" id="jockey_0" name="jockey" placeholder="Enter jockey name">
                                </div>
                                <div class="form-group">
                                    <label for="trainer_0">Trainer</label>
                                    <input type="text" id="trainer_0" name="trainer" placeholder="Enter trainer name">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="odds_0" class="required-field">Odds</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-percentage input-icon"></i>
                                        <input type="text" id="odds_0" name="odds" placeholder="e.g. 5/1 or 6.0" required>
                                    </div>
                                    <div class="tooltip">
                                        <i class="fas fa-info-circle"></i>
                                        <span class="tooltip-text">Enter odds in fractional (e.g. 5/1) or decimal (e.g. 6.0) format</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="stake" class="required-field">Stake (£)</label>
                            <div class="input-with-icon">
                                <i class="fas fa-pound-sign input-icon"></i>
                                <input type="number" id="stake" name="stake" step="0.01" min="0.01" required placeholder="Enter stake amount">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="outcome">Outcome</label>
                            <select id="outcome" name="outcome">
                                <option value="Pending">Pending</option>
                                <option value="Won">Won</option>
                                <option value="Lost">Lost</option>
                                <option value="Void">Void</option>
                            </select>
                            <div class="outcome-badges" style="margin-top: 8px;">
                                <span class="badge badge-pending">Pending</span>
                                <span class="badge badge-won">Won</span>
                                <span class="badge badge-lost">Lost</span>
                                <span class="badge badge-void">Void</span>
                            </div>
                        </div>
                    </div>

                    <!-- Potential Returns Box -->
                    <div class="returns-box">
                        <div class="returns-row">
                            <div class="returns-item">
                                <p class="returns-label">Potential Return:</p>
                                <p class="returns-value" id="potentialReturns">£0.00</p>
                            </div>
                            <div class="returns-item">
                                <p class="returns-label">Potential Profit:</p>
                                <p class="returns-value" id="potentialProfit">£0.00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden field for new racecourse -->
                    <input type="hidden" name="new_racecourse" value="1">
                    
                    <div class="form-actions">
                        <button type="button" id="cancelButton" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="submit" class="btn btn-submit">
                            <i class="fas fa-check"></i> Submit Bet
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="betting-modal.js"></script>

    <script>
  
        </script>
    </body>
</html>