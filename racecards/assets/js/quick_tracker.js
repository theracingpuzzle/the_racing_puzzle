// Add this to your JavaScript file or in a script tag at the end of your HTML

// DOM Elements for Quick Tracker
const quickTrackerModal = document.getElementById('quick-tracker-modal');
const quickTrackerForm = document.getElementById('quick-tracker-form');
const closeQuickTracker = document.querySelector('.close-quick-tracker');

// Close modal when clicking X
if (closeQuickTracker) {
    closeQuickTracker.addEventListener('click', () => {
        quickTrackerModal.style.display = 'none';
    });
}

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    if (e.target === quickTrackerModal) {
        quickTrackerModal.style.display = 'none';
    }
});

// Handle form submission for quick tracker
if (quickTrackerForm) {
    quickTrackerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const horseName = document.getElementById('quick-horse-name').value;
        const jockey = document.getElementById('quick-jockey').value;
        const trainer = document.getElementById('quick-trainer').value;
        const notes = document.getElementById('quick-notes').value;
        const notify = document.getElementById('quick-notify').checked;
        
        // Create horse object
        const horse = {
            id: Date.now().toString(),
            name: horseName,
            jockey,
            trainer,
            // nextRaceDate,
            lastRunNotes: notes,
            notify,
            dateAdded: new Date().toISOString()
        };
        
        // Save horse to tracker
        saveHorse(horse);
        
        // Show confirmation
        alert(`${horseName} has been added to your tracker!`);
        
        // Close modal
        quickTrackerModal.style.display = 'none';
    });
}

// Function to open quick tracker modal with pre-filled data
function openQuickTracker(horseName, jockey, trainer) {
    // Set today's date as default next race date
    const today = new Date().toISOString().split('T')[0];
    
    // Pre-fill form fields
    document.getElementById('quick-horse-name').value = horseName;
    document.getElementById('quick-jockey').value = jockey;
    document.getElementById('quick-trainer').value = trainer;
    document.getElementById('quick-notes').value = '';
    document.getElementById('quick-notify').checked = true;
    
    // Show modal
    quickTrackerModal.style.display = 'block';
}