/**
 * Course List Component - Controls the expandable course list
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const courseContainers = document.querySelectorAll('.course-container');
    const filterButtons = document.querySelectorAll('.filter-button');
    const searchInput = document.getElementById('raceSearch');
    const expandAllBtn = document.getElementById('expandAllBtn');
    const collapseAllBtn = document.getElementById('collapseAllBtn');
    
    // Initialize courses
    initCoursesToggle();
    
    // Initialize filters
    initFilters();
    
    // Initialize search
    initSearch();
    
    // Initialize expand/collapse all
    initExpandCollapseAll();
    
    /**
     * Initialize course toggle functionality
     */
    function initCoursesToggle() {
        if (!courseContainers.length) return;
        
        courseContainers.forEach(container => {
            const header = container.querySelector('.course-header');
            const content = container.querySelector('.course-content');
            
            if (header && content) {
                header.addEventListener('click', function() {
                    // Toggle header expanded class
                    this.classList.toggle('expanded');
                    
                    // Toggle content visibility
                    if (content.style.display === 'none' || !content.style.display) {
                        content.style.display = 'block';
                        
                        const racesContainer = container.querySelector('.races-container');
                        if (racesContainer && racesContainer.getAttribute('data-loaded') === 'false') {
                            racesContainer.setAttribute('data-loaded', 'true');
                            
                            // You could add AJAX loading here if needed
                            // For now, we're using the pre-rendered HTML
                        }
                    } else {
                        content.style.display = 'none';
                    }
                });
            }
        });
    }
    
    /**
     * Initialize region filters
     */
    function initFilters() {
        if (!filterButtons.length || !courseContainers.length) return;
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get selected region
                const selectedRegion = this.getAttribute('data-region');
                
                // Filter courses
                filterCoursesByRegion(selectedRegion);
            });
        });
    }
    
    /**
     * Filter courses by region
     * @param {string} selectedRegion - The selected region to filter by
     */
    function filterCoursesByRegion(selectedRegion) {
        courseContainers.forEach(container => {
            const courseRegion = container.getAttribute('data-region');
            
            if (selectedRegion === 'ALL' || courseRegion === selectedRegion) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });
        
        // Update no results message
        updateNoResultsMessage();
    }
    
    /**
     * Initialize search functionality
     */
    function initSearch() {
        if (!searchInput || !courseContainers.length) return;
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.trim().toLowerCase();
            
            if (searchTerm.length > 0) {
                searchCourses(searchTerm);
            } else {
                // If search is cleared, restore filter by currently selected region
                const activeFilter = document.querySelector('.filter-button.active');
                if (activeFilter) {
                    const selectedRegion = activeFilter.getAttribute('data-region');
                    filterCoursesByRegion(selectedRegion);
                } else {
                    // If no active filter, show all
                    courseContainers.forEach(container => {
                        container.style.display = 'block';
                    });
                }
            }
        });
    }
    
    /**
     * Search courses by term
     * @param {string} searchTerm - The search term
     */
    function searchCourses(searchTerm) {
        let hasResults = false;
        
        courseContainers.forEach(container => {
            const courseName = container.querySelector('.course-name').textContent.toLowerCase();
            const races = container.querySelectorAll('.race-details');
            let matchFound = false;
            
            // Check if course name matches
            if (courseName.includes(searchTerm)) {
                matchFound = true;
            }
            
            // Check races for matches
            if (!matchFound && races.length) {
                races.forEach(race => {
                    const raceName = race.querySelector('.race-name').textContent.toLowerCase();
                    
                    if (raceName.includes(searchTerm)) {
                        matchFound = true;
                    }
                    
                    // Check runners if expanded
                    if (!matchFound) {
                        const runners = race.querySelectorAll('.runner-card');
                        
                        if (runners.length) {
                            runners.forEach(runner => {
                                const horseName = runner.querySelector('.runner-name').textContent.toLowerCase();
                                const jockey = runner.querySelector('.jockey').textContent.toLowerCase();
                                
                                if (horseName.includes(searchTerm) || jockey.includes(searchTerm)) {
                                    matchFound = true;
                                }
                            });
                        }
                    }
                });
            }
            
            // Show or hide based on match
            if (matchFound) {
                container.style.display = 'block';
                hasResults = true;
            } else {
                container.style.display = 'none';
            }
        });
        
        // Update no results message
        updateNoResultsMessage();
    }
    
    /**
     * Update no results message
     */
    function updateNoResultsMessage() {
        const visibleCourses = document.querySelectorAll('.course-container[style="display: block"]');
        const courseListContainer = document.getElementById('course-list-container');
        
        // Remove existing no results message
        const existingMessage = document.querySelector('.no-results-message');
        if (existingMessage) {
            existingMessage.remove();
        }
        
        // Add no results message if no courses are visible
        if (visibleCourses.length === 0 && courseListContainer) {
            const noResultsMessage = document.createElement('div');
            noResultsMessage.className = 'no-races-container no-results-message';
            noResultsMessage.innerHTML = `
                <div class="no-races-message">
                    <i class="fas fa-search"></i>
                    <h3>No matching races found</h3>
                    <p>Try adjusting your search criteria or filters.</p>
                </div>
            `;
            
            courseListContainer.appendChild(noResultsMessage);
        }
    }
    
    /**
     * Initialize expand/collapse all functionality
     */
    function initExpandCollapseAll() {
        if (!expandAllBtn || !collapseAllBtn || !courseContainers.length) return;
        
        // Expand all courses
        expandAllBtn.addEventListener('click', function() {
            courseContainers.forEach(container => {
                const header = container.querySelector('.course-header');
                const content = container.querySelector('.course-content');
                
                if (header && content) {
                    header.classList.add('expanded');
                    content.style.display = 'block';
                    
                    // Ensure race details are loaded
                    const racesContainer = container.querySelector('.races-container');
                    if (racesContainer && racesContainer.getAttribute('data-loaded') === 'false') {
                        racesContainer.setAttribute('data-loaded', 'true');
                    }
                }
            });
        });
        
        // Collapse all courses
        collapseAllBtn.addEventListener('click', function() {
            courseContainers.forEach(container => {
                const header = container.querySelector('.course-header');
                const content = container.querySelector('.course-content');
                
                if (header && content) {
                    header.classList.remove('expanded');
                    content.style.display = 'none';
                }
            });
        });
    }
});