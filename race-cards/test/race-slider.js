/**
 * Race Slider Component - Controls the race time navigation and runner display
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize each course container
    const courseContainers = document.querySelectorAll('.course-container');
    
    courseContainers.forEach(container => {
        initRaceTimesSlider(container);
        initRunnersToggle(container);
    });
    
    /**
     * Initialize race times slider for a course
     * @param {HTMLElement} container - The course container
     */
    function initRaceTimesSlider(container) {
        const raceTimeItems = container.querySelectorAll('.race-time-item');
        const raceDetails = container.querySelectorAll('.race-details');
        
        if (!raceTimeItems.length || !raceDetails.length) return;
        
        // Show first race details by default
        if (raceDetails[0]) {
            raceDetails[0].style.display = 'block';
            raceTimeItems[0].classList.add('active');
        }
        
        // Add click event to race time items
        raceTimeItems.forEach(item => {
            item.addEventListener('click', function() {
                const timeValue = this.getAttribute('data-time');
                
                // Remove active class from all items
                raceTimeItems.forEach(i => i.classList.remove('active'));
                
                // Add active class to clicked item
                this.classList.add('active');
                
                // Hide all race details
                raceDetails.forEach(detail => {
                    detail.style.display = 'none';
                });
                
                // Show selected race details
                const selectedRaceDetail = container.querySelector(`.race-details[data-time="${timeValue}"]`);
                if (selectedRaceDetail) {
                    selectedRaceDetail.style.display = 'block';
                }
            });
        });
        
        // Enable horizontal scrolling with mouse wheel
        const raceTimesSlider = container.querySelector('.race-times-slider');
        if (raceTimesSlider) {
            raceTimesSlider.addEventListener('wheel', function(e) {
                // Prevent vertical scrolling
                if (e.deltaY !== 0) {
                    e.preventDefault();
                    
                    // Scroll horizontally instead
                    this.scrollLeft += e.deltaY;
                }
            });
        }
    }
    
    /**
     * Initialize runners toggle for a course
     * @param {HTMLElement} container - The course container
     */
    function initRunnersToggle(container) {
        const showRunnersButtons = container.querySelectorAll('.show-runners-btn');
        
        if (!showRunnersButtons.length) return;
        
        showRunnersButtons.forEach(button => {
            button.addEventListener('click', function() {
                const timeValue = this.getAttribute('data-time');
                const runnersContainer = container.querySelector(`.runners-container[data-time="${timeValue}"]`);
                
                if (runnersContainer) {
                    // Toggle runners container visibility
                    if (runnersContainer.style.display === 'none' || !runnersContainer.style.display) {
                        runnersContainer.style.display = 'block';
                        this.classList.add('active');
                        this.querySelector('.toggle-text').textContent = 'Hide Runners';
                    } else {
                        runnersContainer.style.display = 'none';
                        this.classList.remove('active');
                        this.querySelector('.toggle-text').textContent = 'Show Runners';
                    }
                }
            });
        });
    }
});