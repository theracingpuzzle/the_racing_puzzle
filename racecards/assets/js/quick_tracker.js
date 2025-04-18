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

// Optional: Close modal with ESC key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        quickTrackerModal.style.display = 'none';
    }
});

// Function to save horse to the database
function saveHorse(horse) {
    const formData = new FormData();

    formData.append('action', 'add');
    formData.append('name', horse.name);
    formData.append('trainer', horse.trainer);
    formData.append('last_run_notes', horse.lastRunNotes);

    fetch('../horse-tracker/horse-action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof loadHorses === 'function') {
                loadHorses();
            }
            alert(`${horse.name} has been added to your tracker!`);
        } else {
            alert(data.message || 'Error saving horse');
        }
    })
    .catch(error => {
        console.error('Error saving horse:', error);
        alert('Error saving horse. Please try again.');
    });
}

// Handle form submission for quick tracker
if (quickTrackerForm) {
    quickTrackerForm.addEventListener('submit', (e) => {
        e.preventDefault();

        const horseName = document.getElementById('quick-horse-name').value;
        const trainer = document.getElementById('quick-trainer').value;
        const notes = document.getElementById('quick-notes').value;
        

        const horse = {
            id: Date.now().toString(),
            name: horseName,
            trainer,
            lastRunNotes: notes,
            dateAdded: new Date().toISOString()
        };

        saveHorse(horse); // Actually save the horse

        // Reset form and close modal
        quickTrackerForm.reset();
        quickTrackerModal.style.display = 'none';
    });
}

// Function to open quick tracker modal with pre-filled data
function openQuickTracker(horseName, jockey, trainer) {
    const today = new Date().toISOString().split('T')[0];

    document.getElementById('quick-horse-name').value = horseName;
    document.getElementById('quick-trainer').value = trainer;
    document.getElementById('quick-notes').value = '';
    

    quickTrackerModal.style.display = 'block';
}
