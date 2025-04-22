document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM fully loaded");
    
    // Small delay to ensure all elements are properly rendered
    setTimeout(function() {
        // Toggle race course expansion
        initCourseToggle();
        
        // Initialize view toggle buttons
        initViewToggleButtons();
        
        // Log element counts for debugging
        logElementCounts();
        
        // Add debug button in development mode
        addDebugButton();
    }, 100);
});

/**
 * Initialize course headers to expand/collapse race containers
 */
function initCourseToggle() {
    const courseHeaders = document.querySelectorAll('.course-header');
    console.log(`Found ${courseHeaders.length} course headers`);
    
    courseHeaders.forEach(header => {
        header.addEventListener('click', function() {
            console.log("Course header clicked");
            this.classList.toggle('expanded');
            const racesContainer = this.nextElementSibling;
            
            // Make sure we found the races container
            if (!racesContainer) {
                console.error("Could not find races container");
                return;
            }
            
            // Toggle display
            if (racesContainer.style.display === 'none') {
                racesContainer.style.display = 'block';
            } else {
                racesContainer.style.display = 'none';
            }
        });
    });
}

/**
 * Initialize toggle buttons for switching between card and table views
 */
function initViewToggleButtons() {
    // Use more specific selector to ensure we get the correct buttons
    const viewButtons = document.querySelectorAll('.view-toggle .toggle-button');
    console.log(`Found ${viewButtons.length} toggle buttons`);
    
    if (viewButtons.length === 0) {
        console.warn("No toggle buttons found. Check selector or page structure.");
    }
    
    // Add click listeners to each card/table toggle button
    viewButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default behavior
            console.log("Toggle button clicked: ", this.textContent.trim());
            
            // Find the race card container
            const raceCard = this.closest('.race-card');
            console.log("Found race card: ", raceCard !== null);
            
            if (!raceCard) {
                console.error("Could not find parent race card");
                return;
            }
            
            // Find the view buttons in this race card
            const buttons = raceCard.querySelectorAll('.toggle-button');
            
            // Remove active class from all buttons
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Find the grid and table views
            const grid = raceCard.querySelector('.runners-grid');
            const table = raceCard.querySelector('.runners-table');
            
            console.log("Found grid: ", grid !== null);
            console.log("Found table: ", table !== null);
            
            if (!grid || !table) {
                console.error("Could not find grid or table views");
                return;
            }
            
            // Determine which view to show based on button text
            const buttonText = this.textContent.trim().toLowerCase();
            
            if (buttonText.includes('card')) {
                // Show grid, hide table
                grid.style.display = 'grid';
                table.style.display = 'none';
                console.log("Showing card view");
            } else if (buttonText.includes('table')) {
                // Show table, hide grid
                grid.style.display = 'none';
                table.style.display = 'block';
                console.log("Showing table view");
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', function() {
    // Course header click handlers
    document.querySelectorAll('.course-header').forEach(header => {
        header.addEventListener('click', function() {
            const courseContainer = this.closest('.course-container');
            const racesContainer = courseContainer.querySelector('.races-container');
            const toggleIcon = this.querySelector('.toggle-icon');
            
            // Toggle expanded/collapsed class
            this.classList.toggle('collapsed');
            this.classList.toggle('expanded');
            
            // Toggle races container visibility
            if (racesContainer.style.display === 'none') {
                racesContainer.style.display = 'block';
                toggleIcon.textContent = '▼'; // Change icon to indicate expanded
            } else {
                racesContainer.style.display = 'none';
                toggleIcon.textContent = '▶'; // Change icon to indicate collapsed
            }
        });
    });
});

