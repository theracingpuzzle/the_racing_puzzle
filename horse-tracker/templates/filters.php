<div class="filters-container">
    <form id="filterForm" method="GET" action="">
        <div class="filter-group">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="breed" class="form-label">Breed:</label>
                    <select name="breed" id="breed" class="form-select">
                        <option value="">All Breeds</option>
                        <?php
                        // This should come from database in production
                        $breeds = ["Arabian", "Quarter Horse", "Thoroughbred", "Appaloosa", "Morgan", "Paint", "Andalusian"];
                        
                        foreach ($breeds as $breed) {
                            $selected = (isset($_GET['breed']) && $_GET['breed'] == $breed) ? 'selected' : '';
                            echo "<option value=\"$breed\" $selected>$breed</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="min_age" class="form-label">Min Age:</label>
                    <input type="number" name="min_age" id="min_age" class="form-control" min="0" max="40" 
                           value="<?php echo isset($_GET['min_age']) ? htmlspecialchars($_GET['min_age']) : ''; ?>">
                </div>
                
                <div class="col-md-2">
                    <label for="max_age" class="form-label">Max Age:</label>
                    <input type="number" name="max_age" id="max_age" class="form-control" min="0" max="40"
                           value="<?php echo isset($_GET['max_age']) ? htmlspecialchars($_GET['max_age']) : ''; ?>">
                </div>
                
                <div class="col-md-3">
                    <label for="health_status" class="form-label">Health Status:</label>
                    <select name="health_status" id="health_status" class="form-select">
                        <option value="">All Statuses</option>
                        <?php
                        $statuses = ["Excellent", "Good", "Fair", "Poor"];
                        
                        foreach ($statuses as $status) {
                            $selected = (isset($_GET['health_status']) && $_GET['health_status'] == $status) ? 'selected' : '';
                            echo "<option value=\"$status\" $selected>$status</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                </div>
            </div>
        </div>
    </form>
    
    <div class="sort-container mt-3">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex align-items-center">
                    <label for="sort" class="me-2">Sort by:</label>
                    <select name="sort" id="sort" class="form-select" onchange="this.form.submit()">
                        <option value="name_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                        <option value="name_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : ''; ?>>Name (Z-A)</option>
                        <option value="age_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_asc') ? 'selected' : ''; ?>>Age (Youngest First)</option>
                        <option value="age_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'age_desc') ? 'selected' : ''; ?>>Age (Oldest First)</option>
                        <option value="last_check_asc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'last_check_asc') ? 'selected' : ''; ?>>Last Check (Oldest First)</option>
                        <option value="last_check_desc" <?php echo (isset($_GET['sort']) && $_GET['sort'] == 'last_check_desc') ? 'selected' : ''; ?>>Last Check (Newest First)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button id="resetFilters" class="btn btn-outline-secondary">Reset Filters</button>
            </div>
        </div>
    </div>
</div>