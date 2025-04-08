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
        
        // Open the modal instead of scrolling
        addHorseModal.style.display = 'block';
    }
}