<?php
// includes/tracker-modal.php
// Horse tracker modal for adding horses to track
?>

<!-- Horse Tracker Modal -->
<div id="horseTrackerModal" class="horse-modal">
    <div class="horse-modal-content">
        <div class="horse-modal-header">
            <h2><i class="fas fa-horse"></i> Add to Horse Tracker</h2>
            <span class="horse-modal-close"><i class="fas fa-times"></i></span>
        </div>
        <div class="horse-modal-body">
            <form id="trackerForm" method="post" action="actions/add-to-tracker.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="horse_name" class="required-field">Horse Name</label>
                        <input type="text" id="horse_name" name="horse_name" required placeholder="Enter horse name">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="jockey">Jockey</label>
                        <input type="text" id="jockey" name="jockey" placeholder="Enter jockey name">
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

                <!-- Quick Notes Section -->
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
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="button" id="cancelButton" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-check"></i> Add to Tracker
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>