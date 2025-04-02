// Function to toggle sidebar collapse
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    
    if (sidebar && mainContent) {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('content-collapsed');
        
        // Store the sidebar collapse state in local storage
        const isCollapsed = sidebar.classList.contains('collapsed');
        localStorage.setItem('sidebarCollapsed', isCollapsed);
    }
}

// Check if the sidebar state is stored in local storage and apply it
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    const toggleBtn = document.getElementById('toggleSidebar');
    
    if (sidebar && mainContent) {
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.classList.add('content-collapsed');
        }
    }
    
    // Add event listener to the toggle button
    if (toggleBtn) {
        toggleBtn.addEventListener('click', toggleSidebar);
    }
    
    // For mobile devices - auto collapse sidebar on smaller screens
    function handleResponsiveLayout() {
        if (window.innerWidth < 768 && sidebar && !sidebar.classList.contains('collapsed')) {
            toggleSidebar();
        }
    }
    
    // Check on initial load
    handleResponsiveLayout();
    
    // Listen for window resize
    window.addEventListener('resize', handleResponsiveLayout);
});

