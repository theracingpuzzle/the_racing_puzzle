// region-filter.js - Handle filtering racecourses by region

document.addEventListener('DOMContentLoaded', function() {
    console.log('Region filter script loaded');
    
    // Filter buttons functionality
    const filterButtons = document.querySelectorAll('.filter-button');
    
    // Log the initial state
    console.log('Initial state - Buttons found:', filterButtons.length);
    filterButtons.forEach(btn => {
        console.log('Button:', btn.textContent.trim(), 'Region:', btn.getAttribute('data-region'));
    });
    
    // Count the course containers and their regions
    const courseContainers = document.querySelectorAll('.course-container');
    console.log('Found', courseContainers.length, 'course containers');
    
    courseContainers.forEach(container => {
        console.log('Course:', container.querySelector('.course-header h2').textContent.trim(), 
                   'Region:', container.getAttribute('data-region'));
    });
    
    // Function to normalize region values for comparison
    function normalizeRegion(region) {
        if (!region) return '';
        
        // Handle common region variations
        region = region.trim().toUpperCase();
        
        // Map common UK variants
        if (region === 'UNITED KINGDOM' || region === 'GREAT BRITAIN' || 
            region === 'ENGLAND' || region === 'SCOTLAND' || 
            region === 'WALES' || region === 'NORTHERN IRELAND' || 
            region === 'GB' || region === 'GBR') {
            return 'UK';
        }
        
        // Map Ireland variants
        if (region === 'IRE' || region === 'EIRE' || region === 'IRL') {
            return 'IRELAND';
        }
        
        // Map France variants
        if (region === 'FRA') {
            return 'FRANCE';
        }
        
        return region;
    }
    
    // Function to filter courses based on region
    function filterCourses(region) {
        console.log('Filtering for region:', region);
        const normalizedFilterRegion = normalizeRegion(region);
        
        let visibleCount = 0;
        
        courseContainers.forEach(container => {
            const courseRegion = normalizeRegion(container.getAttribute('data-region'));
            console.log('Course region:', courseRegion, 'Filter region:', normalizedFilterRegion);
            
            // Show/hide based on filter
            if (normalizedFilterRegion === 'ALL' || normalizedFilterRegion === courseRegion) {
                container.style.display = 'block';
                visibleCount++;
            } else {
                container.style.display = 'none';
            }
        });
        
        console.log('Visible courses after filtering:', visibleCount);
    }
    
    // Initially filter for UK (because the UK button is active by default)
    // We need to wrap this in a short timeout to ensure the DOM is fully ready
    setTimeout(() => {
        const activeButton = document.querySelector('.filter-button.active');
        const initialRegion = activeButton ? activeButton.getAttribute('data-region') : 'UK';
        console.log('Initial filter region:', initialRegion);
        
        // Show in debug console
        if (typeof debugLog === 'function') {
            debugLog('Initial filtering by: ' + initialRegion);
            
            // Count courses by region to help diagnose issues
            const regionSummary = {};
            courseContainers.forEach(container => {
                const r = container.getAttribute('data-region') || 'Unknown';
                regionSummary[r] = (regionSummary[r] || 0) + 1;
            });
            
            debugLog('Regions found:');
            Object.keys(regionSummary).forEach(r => {
                debugLog(`- ${r}: ${regionSummary[r]} courses`);
            });
        }
        
        filterCourses(initialRegion);
    }, 100);
    
    // Add click event listeners to filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            console.log('Filter button clicked:', this.textContent.trim());
            
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            // Get the region to filter by
            const region = this.getAttribute('data-region');
            console.log('Filtering for:', region);
            
            // Filter courses based on selected region
            filterCourses(region);
        });
    });
    
    // Add search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            console.log('Search term:', searchTerm);
            
            // If search is empty, reapply current region filter
            if (searchTerm === '') {
                const activeRegion = document.querySelector('.filter-button.active').getAttribute('data-region');
                filterCourses(activeRegion);
                return;
            }
            
            // Get the current active region for combined filtering
            const activeRegion = normalizeRegion(document.querySelector('.filter-button.active').getAttribute('data-region'));
            console.log('Active region for search:', activeRegion);
            
            // Count matches for logging
            let matchCount = 0;
            
            // Search across all course containers
            courseContainers.forEach(container => {
                const courseName = container.querySelector('.course-header h2').textContent.toLowerCase();
                const courseRegion = normalizeRegion(container.getAttribute('data-region'));
                
                // First check if it matches the active region filter
                const matchesRegion = (activeRegion === 'ALL' || courseRegion === activeRegion);
                
                // Then check if it matches the search term
                const matchesSearch = courseName.includes(searchTerm);
                
                // Only show if both conditions are met
                if (matchesRegion && matchesSearch) {
                    container.style.display = 'block';
                    matchCount++;
                } else {
                    container.style.display = 'none';
                }
            });
            
            console.log('Search matches:', matchCount);
        });
    }
});