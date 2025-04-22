 <!-- Link to main.css instead of horse_tracker.css -->
 <link rel="stylesheet" href="../assets/css/main.css">
 
 <!-- User Profile/Settings Section (Tabbed) -->
 <div class="row">
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white">
                            <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">My Profile</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">Change Password</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="preferences-tab" data-bs-toggle="tab" data-bs-target="#preferences" type="button" role="tab">Preferences</button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                    <form action="update_profile.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="display_name" class="form-label">Display Name</label>
                                                <input type="text" class="form-control" id="display_name" name="display_name" value="<?php echo htmlspecialchars($profile['display_name'] ?? $username); ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($profile['email'] ?? ''); ?>">
                                            </div>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label for="favorite_racing_type" class="form-label">Favorite Racing Type</label>
                                                <select class="form-select" id="favorite_racing_type" name="favorite_racing_type">
                                                    <option value="">-- Select --</option>
                                                    <option value="Flat (Turf)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (Turf)') ? 'selected' : ''; ?>>Flat (Turf)</option>
                                                    <option value="Flat (AW)" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Flat (AW)') ? 'selected' : ''; ?>>Flat (AW)</option>
                                                    <option value="Hurdles" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Hurdles') ? 'selected' : ''; ?>>Hurdles</option>
                                                    <option value="Chase" <?php echo (isset($profile['favorite_racing_type']) && $profile['favorite_racing_type'] == 'Chase') ? 'selected' : ''; ?>>Chase</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="theme" class="form-label">Theme</label>
                                                <select class="form-select" id="theme" name="theme">
                                                    <option value="light" <?php echo (isset($profile['theme']) && $profile['theme'] == 'light') ? 'selected' : ''; ?>>Light</option>
                                                    <option value="dark" <?php echo (isset($profile['theme']) && $profile['theme'] == 'dark') ? 'selected' : ''; ?>>Dark</option>
                                                    <option value="racing" <?php echo (isset($profile['theme']) && $profile['theme'] == 'racing') ? 'selected' : ''; ?>>Racing</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 form-check">
                                            <input type="checkbox" class="form-check-input" id="newsletter_subscription" name="newsletter_subscription" value="1" <?php echo (isset($profile['newsletter_subscription']) && $profile['newsletter_subscription'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="newsletter_subscription">Subscribe to newsletter</label>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </form>
                                </div>
                                
                                <div class="tab-pane fade" id="password" role="tabpanel" aria-labelledby="password-tab">
                                    <form action="change_password.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="new_password" class="form-label">New Password</label>
                                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                                            <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password" required>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </form>
                                </div>
                                
                                <div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
                                    <form action="update_preferences.php" method="post">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Notification Preferences</label>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_race" name="notify_race" value="1" checked>
                                                <label class="form-check-label" for="notify_race">New races</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_results" name="notify_results" value="1" checked>
                                                <label class="form-check-label" for="notify_results">Race results</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" id="notify_news" name="notify_news" value="1">
                                                <label class="form-check-label" for="notify_news">Racing news</label>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-primary">Save Preferences</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>