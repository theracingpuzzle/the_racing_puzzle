<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Details Tracker</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="horse-tracker-modal.css">
</head>
<body>
    <div class="test-container">
        <h1>Horse Racing Information System</h1>
        <p>Track your horses and their performance over time.</p>
        
        <button id="openModalBtn" class="test-button">
            <i class="fas fa-horse"></i> Add New Horse
        </button>
        
        <!-- Test area to show submission results -->
        <div id="formResults">
            <h3>Form Submission Results:</h3>
            <pre id="resultsContent"></pre>
        </div>
    </div>

    <!-- Horse Modal HTML Structure -->
    <div id="horseModal" class="horse-modal">
        <div class="horse-modal-content">
            <div class="horse-modal-header">
                <h2><i class="fas fa-horse"></i> Horse Details</h2>
                <span class="horse-modal-close"><i class="fas fa-times"></i></span>
            </div>
            <div class="horse-modal-body">
                <form id="horseForm" method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="horse_name" class="required-field">Horse Name</label>
                            <input type="text" id="horse_name" name="horse_name" required placeholder="Enter horse name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="trainer" class="required-field">Trainer</label>
                            <input type="text" id="trainer" name="trainer" required placeholder="Enter trainer name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="4" placeholder="Enter any notes or comments about this horse"></textarea>
                        </div>
                    </div>

                    <!-- Additional Information (Optional Fields) -->
                    <div class="collapsible-section">
                        <div class="collapsible-header">
                            <h3>Additional Information</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapsible-content">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" id="age" name="age" min="1" max="30" placeholder="Horse age">
                                </div>
                                <div class="form-group">
                                    <label for="breed">Breed</label>
                                    <input type="text" id="breed" name="breed" placeholder="Horse breed">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <select id="color" name="color">
                                        <option value="">-- Select Color --</option>
                                        <option value="Bay">Bay</option>
                                        <option value="Black">Black</option>
                                        <option value="Chestnut">Chestnut</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Brown">Brown</option>
                                        <option value="White">White</option>
                                        <option value="Palomino">Palomino</option>
                                        <option value="Roan">Roan</option>
                                        <option value="Dun">Dun</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender">
                                        <option value="">-- Select Gender --</option>
                                        <option value="Colt">Colt</option>
                                        <option value="Filly">Filly</option>
                                        <option value="Gelding">Gelding</option>
                                        <option value="Mare">Mare</option>
                                        <option value="Stallion">Stallion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Comments Section -->
                    <div class="quick-comments-box">
                        <h3 class="quick-comments-title">Quick Notes</h3>
                        <div class="quick-comment-buttons">
                            <button type="button" class="quick-comment-btn" data-comment="Strong finish, showed good potential">Strong finish</button>
                            <button type="button" class="quick-comment-btn" data-comment="Started well but faded in the final stretch">Started well but faded</button>
                            <button type="button" class="quick-comment-btn" data-comment="Struggled with the going, consider different ground next time">Struggled with going</button>
                            <button type="button" class="quick-comment-btn" data-comment="Traveled well throughout the race">Traveled well</button>
                            <button type="button" class="quick-comment-btn" data-comment="Needs more distance, consider longer races">Needs more distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Better suited to shorter distances">Better at shorter distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Watch for next time, showed improvement">Watch for next time</button>
                            <button type="button" class="quick-comment-btn" data-comment="Add blinkers/headgear next time">Add blinkers next time</button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" id="cancelButton" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="submit" class="btn btn-submit">
                            <i class="fas fa-check"></i> Save Horse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="horse-tracker-modal.js"></script>
</body>
</html>