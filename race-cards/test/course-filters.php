<?php
// includes/course-filters.php
// Filter options for race courses
?>

<section class="course-filters-section">
    <div class="container">
        <div class="filter-container">
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="raceSearch" class="search-input" placeholder="Search courses, horses, jockeys...">
            </div>
            
            <div class="region-filters">
                <button class="filter-button active" data-region="ALL">
                    <i class="fas fa-globe"></i> All
                </button>
                <button class="filter-button" data-region="UK">
                    <span class="flag-icon flag-icon-gb"></span> UK
                </button>
                <button class="filter-button" data-region="IRELAND">
                    <i class="flag-icon flag-icon-ie"></i> Ireland
                </button>
                <button class="filter-button" data-region="FRANCE">
                    <i class="flag-icon flag-icon-fr"></i> France
                </button>
                <button class="filter-button" data-region="OTHER">
                    <i class="fas fa-ellipsis-h"></i> Other
                </button>
            </div>
            
            <div class="view-options">
                <button id="expandAllBtn" class="view-option-btn">
                    <i class="fas fa-expand-alt"></i> Expand All
                </button>
                <button id="collapseAllBtn" class="view-option-btn">
                    <i class="fas fa-compress-alt"></i> Collapse All
                </button>
            </div>
        </div>
    </div>
</section>