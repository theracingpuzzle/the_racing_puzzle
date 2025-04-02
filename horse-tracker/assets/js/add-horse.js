// DOM Elements
const addHorseForm = document.getElementById('add-horse-form');
const horsesContainer = document.getElementById('horses-container');
const searchInput = document.getElementById('search-horses');
const sortBySelect = document.getElementById('sort-by');
const notificationModal = document.getElementById('notification-modal');
const upcomingRacesList = document.getElementById('upcoming-races-list');
const closeModal = document.querySelector('.close-modal');

// Storage key
const STORAGE_KEY = 'trackedHorses';

// Track if we're editing a horse
let editingHorseId = null;

// Initialize the app
document.addEventListener('DOMContentLoaded', () => {
    loadHorses();
    checkForUpcomingRaces();
    
    // Set up event listeners
    addHorseForm.addEventListener('submit', handleFormSubmit);
    searchInput.addEventListener('input', filterHorses);
    sortBySelect.addEventListener('change', sortHorses);
    closeModal.addEventListener('click', () => {
        notificationModal.style.display = 'none';
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', (e) => {
        if (e.target === notificationModal) {
            notificationModal.style.display = 'none';
        }
    });
    
    // Check for races daily
    setInterval(checkForUpcomingRaces, 86400000); // 24 hours

     // Also check for races when page is visited
     checkForUpcomingRaces();
});

// Handle form submissions (both adding and editing)
function handleFormSubmit(e) {
    e.preventDefault();
    
    const horseName = document.getElementById('horse-name').value;
    const trainer = document.getElementById('trainer').value;
    const jockey = document.getElementById('jockey').value;
    const nextRaceDate = document.getElementById('next-race-date').value;
    const lastRunNotes = document.getElementById('last-run-notes').value;
    const notify = document.getElementById('notification').checked;
    
    if (!horseName) {
        alert('Please enter a horse name');
        return;
    }
    
    const horse = {
        id: editingHorseId || Date.now().toString(),
        name: horseName,
        trainer,
        jockey,
        nextRaceDate,
        lastRunNotes,
        notify,
        dateAdded: editingHorseId ? getHorseById(editingHorseId).dateAdded : new Date().toISOString()
    };
    
    saveHorse(horse);
    
    // Reset form and editing state
    addHorseForm.reset();
    editingHorseId = null;
    document.querySelector('button[type="submit"]').textContent = 'Add Horse';
    
    // Refresh display
    loadHorses();
}

// Save horse to localStorage
function saveHorse(horse) {
    const horses = getAllHorses();
    
    // Check if we're editing an existing horse
    const existingIndex = horses.findIndex(h => h.id === horse.id);
    
    if (existingIndex !== -1) {
        horses[existingIndex] = horse;
    } else {
        horses.push(horse);
    }
    
    localStorage.setItem(STORAGE_KEY, JSON.stringify(horses));
}

// Get all horses from localStorage
function getAllHorses() {
    const horses = localStorage.getItem(STORAGE_KEY);
    return horses ? JSON.parse(horses) : [];
}

// Get a specific horse by ID
function getHorseById(id) {
    const horses = getAllHorses();
    return horses.find(horse => horse.id === id);
}

// Delete a horse
function deleteHorse(id) {
    if (confirm('Are you sure you want to remove this horse from your tracker?')) {
        let horses = getAllHorses();
        horses = horses.filter(horse => horse.id !== id);
        localStorage.setItem(STORAGE_KEY, JSON.stringify(horses));
        loadHorses();
    }
}

// Load and display horses
function loadHorses() {
    const horses = getAllHorses();
    displayHorses(horses);
}

// Display horses in the UI
function displayHorses(horses) {
    horsesContainer.innerHTML = '';
    
    if (horses.length === 0) {
        horsesContainer.innerHTML = '<p class="no-horses">No horses added yet. Add your first horse above!</p>';
        return;
    }
    
    horses.forEach(horse => {
        const card = document.createElement('div');
        card.className = 'horse-card';
        
        // Format the date if it exists
        let formattedDate = '';
        if (horse.nextRaceDate) {
            const date = new Date(horse.nextRaceDate);
            formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        card.innerHTML = `
            <h3>${horse.name}</h3>
            ${horse.trainer ? `<p><strong>Trainer:</strong> ${horse.trainer}</p>` : ''}
            ${horse.jockey ? `<p><strong>Jockey:</strong> ${horse.jockey}</p>` : ''}
            ${formattedDate ? `<p class="next-race"><strong>Next Race:</strong> ${formattedDate}</p>` : ''}
            ${horse.lastRunNotes ? `
                <div class="notes">
                    <strong>Last Run Notes:</strong>
                    <p>${horse.lastRunNotes}</p>
                </div>
            ` : ''}
            <div class="notification-toggle">
                <label>
                    <input type="checkbox" ${horse.notify ? 'checked' : ''} onchange="toggleNotification('${horse.id}')">
                    Notifications
                </label>
            </div>
            <div class="card-actions">
                <button class="edit-btn" onclick="editHorse('${horse.id}')">Edit</button>
                <button class="delete-btn" onclick="deleteHorse('${horse.id}')">Delete</button>
            </div>
        `;
        
        horsesContainer.appendChild(card);
    });
}

// Toggle notification for a horse
function toggleNotification(id) {
    const horses = getAllHorses();
    const horse = horses.find(h => h.id === id);
    
    if (horse) {
        horse.notify = !horse.notify;
        localStorage.setItem(STORAGE_KEY, JSON.stringify(horses));
    }
}

// Edit a horse
function editHorse(id) {
    const horse = getHorseById(id);
    
    if (horse) {
        document.getElementById('horse-name').value = horse.name;
        document.getElementById('trainer').value = horse.trainer || '';
        document.getElementById('jockey').value = horse.jockey || '';
        document.getElementById('next-race-date').value = horse.nextRaceDate || '';
        document.getElementById('last-run-notes').value = horse.lastRunNotes || '';
        document.getElementById('notification').checked = horse.notify;
        
        // Change button text
        document.querySelector('button[type="submit"]').textContent = 'Update Horse';
        
        // Set editing state
        editingHorseId = id;
        
        // Scroll to form
        document.querySelector('.add-horse-section').scrollIntoView({ behavior: 'smooth' });
    }
}

// Filter horses based on search input
function filterHorses() {
    const searchTerm = searchInput.value.toLowerCase();
    const horses = getAllHorses();
    
    const filteredHorses = horses.filter(horse => {
        return (
            horse.name.toLowerCase().includes(searchTerm) ||
            (horse.trainer && horse.trainer.toLowerCase().includes(searchTerm)) ||
            (horse.jockey && horse.jockey.toLowerCase().includes(searchTerm))
        );
    });
    
    displayHorses(filteredHorses);
}

// Sort horses based on selected option
function sortHorses() {
    const sortBy = sortBySelect.value;
    const horses = getAllHorses();
    
    let sortedHorses = [...horses];
    
    switch (sortBy) {
        case 'name':
            sortedHorses.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'date':
            sortedHorses.sort((a, b) => {
                // Handle empty dates
                if (!a.nextRaceDate) return 1;
                if (!b.nextRaceDate) return -1;
                return new Date(a.nextRaceDate) - new Date(b.nextRaceDate);
            });
            break;
        case 'trainer':
            sortedHorses.sort((a, b) => {
                // Handle empty trainer names
                if (!a.trainer) return 1;
                if (!b.trainer) return -1;
                return a.trainer.localeCompare(b.trainer);
            });
            break;
        default:
            break;
    }
    
    displayHorses(sortedHorses);
}

// Check for upcoming races (within 24 hours)
function checkForUpcomingRaces() {
    const horses = getAllHorses();
    const now = new Date();
    const tomorrow = new Date(now);
    tomorrow.setDate(tomorrow.getDate() + 1);
    
    const upcomingRaces = horses.filter(horse => {
        if (!horse.nextRaceDate || !horse.notify) return false;
        
        const raceDate = new Date(horse.nextRaceDate);
        return raceDate >= now && raceDate <= tomorrow;
    });
    
    if (upcomingRaces.length > 0) {
        // Display notification modal
        upcomingRacesList.innerHTML = '';
        
        upcomingRaces.forEach(horse => {
            const raceDate = new Date(horse.nextRaceDate);
            const formattedDate = raceDate.toLocaleString('en-US', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            const raceItem = document.createElement('div');
            raceItem.className = 'upcoming-race';
            raceItem.innerHTML = `
                <h3>${horse.name}</h3>
                <p><strong>Race Time:</strong> ${formattedDate}</p>
                ${horse.trainer ? `<p><strong>Trainer:</strong> ${horse.trainer}</p>` : ''}
                ${horse.jockey ? `<p><strong>Jockey:</strong> ${horse.jockey}</p>` : ''}
                ${horse.lastRunNotes ? `<p><strong>Last Run Notes:</strong> ${horse.lastRunNotes}</p>` : ''}
            `;
            
            upcomingRacesList.appendChild(raceItem);
        });
        
        notificationModal.style.display = 'block';
    }
}

// Request notification permission
function requestNotificationPermission() {
    if (!("Notification" in window)) {
        alert("This browser does not support desktop notifications");
        return;
    }
    
    Notification.requestPermission().then(permission => {
        if (permission === "granted") {
            alert("You will now receive notifications for upcoming races!");
        }
    });
}

// Check if browser supports notifications and request permission
if ("Notification" in window) {
    document.addEventListener('DOMContentLoaded', () => {
        // Add notification permission button
        const addHorseSection = document.querySelector('.add-horse-section');
        const permissionBtn = document.createElement('button');
        permissionBtn.className = 'btn';
        permissionBtn.style.marginLeft = '10px';
        permissionBtn.textContent = 'Enable Notifications';
        permissionBtn.addEventListener('click', requestNotificationPermission);
        
        document.querySelector('button[type="submit"]').after(permissionBtn);
    });
}