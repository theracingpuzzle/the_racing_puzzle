// Replace your existing toggle button event handler with this one
document.addEventListener('DOMContentLoaded', function() {
    // Toggle between card and table view for each race
    const toggleButtons = document.querySelectorAll('.toggle-button');
    console.log(`Found ${toggleButtons.length} toggle buttons`);
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Find the parent race card
            const parentRaceCard = this.closest('.race-card');
            if (!parentRaceCard) {
                console.error('Could not find parent race card');
                return;
            }
            
            // Determine if this is the card view button
            const isCardView = this.querySelector('i').classList.contains('fa-th');
            console.log(`Toggle button clicked. Is Card View: ${isCardView}`);
            
            // Toggle active class on buttons
            const siblingButtons = this.parentElement.querySelectorAll('.toggle-button');
            siblingButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Get the grid and table containers
            const runnersGrid = parentRaceCard.querySelector('.runners-grid');
            const runnersTable = parentRaceCard.querySelector('.runners-table');
            
            if (!runnersGrid || !runnersTable) {
                console.error('Could not find runners grid or table');
                return;
            }
            
            console.log('Before toggle - Grid display:', runnersGrid.style.display);
            console.log('Before toggle - Table display:', runnersTable.style.display);
            
            // Use a more aggressive approach with !important
            if (isCardView) {
                // Card View - Show grid, hide table
                runnersGrid.style.setProperty('display', 'grid', 'important');
                runnersTable.style.setProperty('display', 'none', 'important');
                
                // Also try with classList just to be safe
                runnersGrid.classList.remove('hidden-view');
                runnersTable.classList.add('hidden-view');
            } else {
                // Table View - Show table, hide grid
                runnersGrid.style.setProperty('display', 'none', 'important');
                runnersTable.style.setProperty('display', 'block', 'important');
                
                // Also try with classList just to be safe
                runnersGrid.classList.add('hidden-view');
                runnersTable.classList.remove('hidden-view');
            }
            
            console.log('After toggle - Grid display:', runnersGrid.style.display);
            console.log('After toggle - Table display:', runnersTable.style.display);
            
            // Force a reflow (can help with stubborn display issues)
            void parentRaceCard.offsetWidth;
        });
    });
    
    // Add a CSS class for hiding elements
    const style = document.createElement('style');
    style.textContent = `
        .hidden-view {
            display: none !important;
            visibility: hidden !important;
        }
    `;
    document.head.appendChild(style);


    // COURSE TOGGLE FUNCTIONALITY
    // Course container toggle (expand/collapse)
    const courseHeaders = document.querySelectorAll('.course-header');
    console.log(`Found ${courseHeaders.length} course headers`);
    
    courseHeaders.forEach(header => {
        // Make sure initial state is set correctly
        const racesContainer = header.nextElementSibling;
        const toggleIcon = header.querySelector('.toggle-icon');
        
        console.log(`Setting initial state for: ${header.querySelector('h2').textContent.trim()}`);
        
        // Force initial state to collapsed
        header.classList.remove('expanded');
        racesContainer.style.display = 'none';
        toggleIcon.textContent = '▶';
        
        // Debug click handler
        header.addEventListener('click', function(e) {
            console.log('Course header clicked');
            
            const racesContainer = this.nextElementSibling;
            const toggleIcon = this.querySelector('.toggle-icon');
            const courseName = this.querySelector('h2').textContent.trim();
            
            console.log(`Toggle clicked for: ${courseName}`);
            console.log(`Current expanded state: ${this.classList.contains('expanded')}`);
            
            // Toggle expanded class and change display
            if (this.classList.contains('expanded')) {
                // Collapse - was expanded, now toggle off
                console.log('Collapsing course');
                this.classList.remove('expanded');
                racesContainer.style.display = 'none';
                toggleIcon.textContent = '▶';
                this.setAttribute('data-action', 'Click to expand');
            } else {
                // Expand - was collapsed, now toggle on
                console.log('Expanding course');
                this.classList.add('expanded');
                racesContainer.style.display = 'block'; // This is key
                toggleIcon.textContent = '▼';
                this.setAttribute('data-action', 'Click to collapse');
                
                // Simulate loading race data if not yet loaded
                if (racesContainer.dataset.loaded !== 'true') {
                    console.log(`Loading race data for: ${courseName}`);
                    showLoadingIndicator(racesContainer, courseName);
                    setTimeout(() => {
                        hideLoadingIndicator(racesContainer);
                        
                        // Make sure all race cards within this container are properly displayed
                        const raceCards = racesContainer.querySelectorAll('.race-card');
                        raceCards.forEach(card => {
                            card.style.display = 'block';
                            card.style.visibility = 'visible';
                            card.style.opacity = '1';
                        });
                        
                        racesContainer.dataset.loaded = 'true';
                    }, 800);
                }
            }
        });
    });
    
    // Helper functions for loading indicators
    function showLoadingIndicator(container, courseName) {
        // Only add if it doesn't exist
        if (!container.querySelector('.loading-indicator')) {
            const loadingIndicator = document.createElement('div');
            loadingIndicator.className = 'loading-indicator';
            loadingIndicator.innerHTML = `
                <div class="loading-spinner"></div>
                <p>Loading races for ${courseName}...</p>
            `;
            container.insertBefore(loadingIndicator, container.firstChild);
        }
    }
    
    function hideLoadingIndicator(container) {
        const indicator = container.querySelector('.loading-indicator');
        if (indicator) {
            indicator.remove();
        }
    }

    // Toggle active filter button
    const filterButtons = document.querySelectorAll('.filter-button');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Add filter logic here
            const filterValue = this.textContent.trim();
            console.log(`Filtering by: ${filterValue}`);
            // Implement filtering logic
        });
    });

    // Date slider functionality
    const dateItems = document.querySelectorAll('.date-item');
    dateItems.forEach(item => {
        item.addEventListener('click', function() {
            dateItems.forEach(date => date.classList.remove('active'));
            this.classList.add('active');
            
            // Add date change logic here
            const day = this.querySelector('.day-number').textContent;
            const month = this.querySelector('.day-month').textContent;
            console.log(`Date changed to: ${day} ${month}`);
            // Implement date change logic
        });
    });
    
    // Ensure race cards are properly initialized
    initializeRaceCards();
    
    function initializeRaceCards() {
        // Make sure all initial races are hidden but properly styled
        const racesContainers = document.querySelectorAll('.races-container');
        racesContainers.forEach(container => {
            container.style.display = 'none';
            
            // Ensure race cards have the right styling for when they become visible
            const raceCards = container.querySelectorAll('.race-card');
            raceCards.forEach(card => {
                // Set default styling that will apply when container is shown
                card.style.display = 'block';
                card.style.visibility = 'visible';
                card.style.opacity = '1';
            });
        });
    }
    
    console.log('Race card initialization complete');
});

