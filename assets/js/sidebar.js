// Improved sidebar.js with better functionality and accessibility

// Function to toggle sidebar collapse
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (sidebar && mainContent) {
        const isCollapsing = !sidebar.classList.contains('collapsed');
        
        // Update sidebar state
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('content-collapsed');
        
        // Update toggle icon
        if (toggleIcon) {
            toggleIcon.className = isCollapsing ? 'fas fa-chevron-right' : 'fas fa-chevron-left';
        }
        
        // Update ARIA attributes
        sidebar.setAttribute('aria-expanded', !isCollapsing);
        
        // Store the sidebar collapse state in local storage
        localStorage.setItem('sidebarCollapsed', isCollapsing);
        
        // Announce state change for screen readers
        const announcement = document.getElementById('sidebar-announcement');
        if (announcement) {
            announcement.textContent = isCollapsing ? 'Sidebar collapsed' : 'Sidebar expanded';
        }
    }
}

// Function to handle mobile sidebar
function toggleMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const overlay = document.getElementById('mobileOverlay');
    
    if (sidebar && mainContent && overlay) {
        sidebar.classList.toggle('mobile-hidden');
        mainContent.classList.toggle('content-mobile-full');
        overlay.classList.toggle('active');
    }
}

// Close mobile sidebar when clicking outside
function handleOutsideClick(event) {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobileOverlay');
    
    if (sidebar && overlay && overlay.classList.contains('active')) {
        // If clicked outside the sidebar
        if (!sidebar.contains(event.target) && !event.target.closest('#mobileToggle')) {
            toggleMobileSidebar();
        }
    }
}

// Create tooltips
function createTooltips() {
    const navItems = document.querySelectorAll('.nav-item');
    
    navItems.forEach(item => {
        const link = item.querySelector('a');
        const text = item.querySelector('.nav-text').textContent;
        
        // Create tooltip element
        const tooltip = document.createElement('span');
        tooltip.className = 'tooltip';
        tooltip.textContent = text;
        
        // Add tooltip to nav item
        item.appendChild(tooltip);
    });
}

// Setup keyboard navigation
function setupKeyboardNavigation() {
    const focusableElements = document.querySelectorAll('#sidebar a, #sidebar button');
    
    focusableElements.forEach(element => {
        element.addEventListener('keydown', (e) => {
            // Space or Enter activates buttons
            if ((e.key === ' ' || e.key === 'Enter') && element.tagName === 'BUTTON') {
                e.preventDefault();
                element.click();
            }
        });
    });
}

// Handle theme toggle (optional feature)
function toggleTheme() {
    const body = document.body;
    const currentTheme = localStorage.getItem('theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    body.classList.remove(`theme-${currentTheme}`);
    body.classList.add(`theme-${newTheme}`);
    
    localStorage.setItem('theme', newTheme);
    
    // Update icon if needed
    const themeIcon = document.getElementById('themeIcon');
    if (themeIcon) {
        themeIcon.className = newTheme === 'light' ? 'fas fa-moon' : 'fas fa-sun';
    }
}

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');
    const mobileToggleBtn = document.getElementById('mobileToggle');
    const overlay = document.getElementById('mobileOverlay');
    const themeToggle = document.getElementById('themeToggle');
    
    // Create screen reader announcement element
    const announcement = document.createElement('div');
    announcement.id = 'sidebar-announcement';
    announcement.className = 'sr-only';
    announcement.setAttribute('role', 'status');
    announcement.setAttribute('aria-live', 'polite');
    document.body.appendChild(announcement);
    
    // Apply stored sidebar state
    if (sidebar && mainContent) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('content-collapsed');
            
            // Update toggle icon if it exists
            const toggleIcon = document.getElementById('toggleIcon');
            if (toggleIcon) {
                toggleIcon.className = 'fas fa-chevron-right';
            }
            
            sidebar.setAttribute('aria-expanded', 'false');
        } else {
            sidebar.setAttribute('aria-expanded', 'true');
        }
    }
    
    // Apply stored theme (optional feature)
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme) {
        document.body.classList.add(`theme-${storedTheme}`);
        
        // Update theme icon if it exists
        const themeIcon = document.getElementById('themeIcon');
        if (themeIcon && storedTheme === 'dark') {
            themeIcon.className = 'fas fa-sun';
        }
    }
    
    // Add event listeners
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }
    
    if (mobileToggleBtn) {
        mobileToggleBtn.addEventListener('click', toggleMobileSidebar);
    }
    
    if (overlay) {
        overlay.addEventListener('click', toggleMobileSidebar);
    }
    
    if (themeToggle) {
        themeToggle.addEventListener('click', toggleTheme);
    }
    
    // Document click for mobile outside clicks
    document.addEventListener('click', handleOutsideClick);
    
    // Create tooltips for the sidebar items
    createTooltips();
    
    // Setup keyboard navigation
    setupKeyboardNavigation();
    
    // Handle responsive layout
    function handleResponsiveLayout() {
        if (window.innerWidth < 992) {
            // For tablet/mobile devices
            if (sidebar && !sidebar.classList.contains('mobile-hidden')) {
                sidebar.classList.add('mobile-hidden');
                mainContent.classList.add('content-mobile-full');
            }
        } else {
            // For desktop devices
            if (sidebar && sidebar.classList.contains('mobile-hidden')) {
                sidebar.classList.remove('mobile-hidden');
                mainContent.classList.remove('content-mobile-full');
            }
            
            // Also remove active overlay if any
            if (overlay && overlay.classList.contains('active')) {
                overlay.classList.remove('active');
            }
        }
    }
    
    // Check on initial load
    handleResponsiveLayout();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsiveLayout);
});