/**
 * Date Slider Component - Controls the date navigation
 */
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const prevDateBtn = document.querySelector('.prev-date');
    const nextDateBtn = document.querySelector('.next-date');
    const dateSlider = document.querySelector('.date-slider');
    const dateItems = document.querySelectorAll('.date-item');
    
    // If no elements found, exit
    if (!dateSlider || !dateItems.length) return;
    
    // Get the first active date
    const activeDate = document.querySelector('.date-item.active');
    if (activeDate) {
        // Scroll to active date
        setTimeout(() => {
            scrollToActiveDate(activeDate);
        }, 100);
    }
    
    // Previous date button click
    if (prevDateBtn) {
        prevDateBtn.addEventListener('click', function() {
            navigateDate('prev');
        });
    }
    
    // Next date button click
    if (nextDateBtn) {
        nextDateBtn.addEventListener('click', function() {
            navigateDate('next');
        });
    }
    
    /**
     * Navigate to previous or next date
     * @param {string} direction - 'prev' or 'next'
     */
    function navigateDate(direction) {
        const activeDate = document.querySelector('.date-item.active');
        if (!activeDate) return;
        
        let targetDate;
        
        if (direction === 'prev') {
            targetDate = activeDate.previousElementSibling;
        } else {
            targetDate = activeDate.nextElementSibling;
        }
        
        // If target date exists, navigate to it
        if (targetDate && targetDate.classList.contains('date-item')) {
            window.location.href = targetDate.getAttribute('href');
        }
    }
    
    /**
     * Scroll date slider to show active date
     * @param {HTMLElement} activeElement - The active date element
     */
    function scrollToActiveDate(activeElement) {
        if (!activeElement) return;
        
        const sliderRect = dateSlider.getBoundingClientRect();
        const activeRect = activeElement.getBoundingClientRect();
        
        // Calculate the scroll position
        const scrollLeft = activeRect.left + dateSlider.scrollLeft - sliderRect.left - (sliderRect.width / 2) + (activeRect.width / 2);
        
        // Smooth scroll to position
        dateSlider.scrollTo({
            left: scrollLeft,
            behavior: 'smooth'
        });
    }
    
    // Enable touch/swipe navigation on mobile
    let startX, moveX;
    
    dateSlider.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
    });
    
    dateSlider.addEventListener('touchmove', function(e) {
        if (!startX) return;
        moveX = e.touches[0].clientX;
    });
    
    dateSlider.addEventListener('touchend', function() {
        if (!startX || !moveX) return;
        
        const diffX = startX - moveX;
        
        // Detect swipe with minimum threshold
        if (Math.abs(diffX) > 50) {
            if (diffX > 0) {
                // Swipe left - next date
                navigateDate('next');
            } else {
                // Swipe right - previous date
                navigateDate('prev');
            }
        }
        
        // Reset values
        startX = null;
        moveX = null;
    });
});