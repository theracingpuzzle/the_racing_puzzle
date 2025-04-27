<?php
// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Continue with the rest of your dashboard code
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Tracker</title>
    <meta name="description" content="Track your favorite race horses and receive notifications for upcoming races">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@700&family=Source+Sans+Pro:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Link to main.css instead of horse_tracker.css -->
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>

<?php include '../test/app-header.php'; ?>

    <div class="container mt-20">
        <div class="d-flex justify-between align-center mb-10">
            <h2>Horse Tracker</h2>
            <p>Track your favourite race horses and receive notifications for upcoming races</p>
        </div>

        <main>
            <div class="horse-list-section">
                <div class="card mb-20">
                    <div class="card-body">
                        <div class="d-flex justify-between align-center">
                            <div class="d-flex gap-20" style="width: 60%;">
                                <input type="text" id="search-horses" placeholder="Search horses...">
                                <select id="sort-by">
                                    <option value="name">Sort by Name</option>
                                    <option value="date">Sort by Next Race Date</option>
                                    <option value="added">Sort by Date Added</option>
                                </select>
                            </div>
                            <div class="d-flex gap-10">
                                <button id="add-horse-btn" class="btn btn-primary"><i class="fas fa-plus"></i> Add Horse</button>
                                <button id="view-calendar" class="btn btn-secondary" onclick="window.location.href='../calendar/index.php'">
                                    <i class="fas fa-calendar"></i> Calendar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div id="horses-list" class="card">
                    <div class="card-header">
                        <h4>Your Tracked Horses</h4>
                    </div>
                    <div class="card-body">
                        <div class="loading">Loading horses...</div>
                    </div>
                </div>
            </div>
            
            <!-- Calendar Section (Hidden by default) -->
            <section class="calendar-section" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <h4>Race Calendar</h4>
                    </div>
                    <div class="card-body" id="calendar-container">
                        <div class="loading">Loading calendar...</div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="mt-20">
            <!-- Footer content if needed -->
        </footer>
    </div>

    <!-- Add Horse Modal -->
    <div id="add-horse-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="horse-form-title">Add New Horse</h3>
                <span class="modal-close close-modal">&times;</span>
            </div>
            <form id="add-horse-form">
                <input type="hidden" id="horse-id">
                <div class="form-group">
                    <label for="horse-name">Horse Name:</label>
                    <input type="text" id="horse-name" required>
                </div>
                <div class="form-group">
                    <label for="horse-trainer">Trainer:</label>
                    <input type="text" id="horse-trainer">
                </div>
                <div class="form-group">
                    <label for="last-run-notes">Last Run Notes:</label>
                    <textarea id="last-run-notes" rows="4"></textarea>
                </div>
                <!-- <div class="form-group">
                    <label for="notification" style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" id="notification" checked style="width: auto;">
                        Notify for next race
                    </label>
                </div> -->
                <div class="d-flex justify-between mt-20">
                    <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Horse</button>
                </div>
            </form>
        </div>
    </div>

    <?php include '../test/bottom-nav.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const horsesList = document.getElementById('horses-list').querySelector('.card-body');
            const searchInput = document.getElementById('search-horses');
            const sortSelect = document.getElementById('sort-by');
            const addHorseBtn = document.getElementById('add-horse-btn');
            const addHorseModal = document.getElementById('add-horse-modal');
            const closeButtons = document.querySelectorAll('.close-modal');
            const addHorseForm = document.getElementById('add-horse-form');
            const formTitle = document.getElementById('horse-form-title');
            const calendarBtn = document.getElementById('view-calendar');
            const horseListSection = document.querySelector('.horse-list-section');
            const calendarSection = document.querySelector('.calendar-section');
            
            // Load horses from the database
            function loadHorses(sort = 'name', search = '') {
                horsesList.innerHTML = '<div class="loading">Loading horses...</div>';
                
                let url = `get-horses.php?sort=${sort}`;
                if (search) {
                    url += `&search=${encodeURIComponent(search)}`;
                }
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.horses && data.horses.length > 0) {
                            displayHorses(data.horses);
                        } else {
                            horsesList.innerHTML = '<div class="text-center mb-20 mt-20">No horses found</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading horses:', error);
                        horsesList.innerHTML = '<div class="text-center mb-20 mt-20">Error loading horses. Please try again.</div>';
                    });
            }
            
            // Display horses in the list
            function displayHorses(horses) {
                horsesList.innerHTML = '';
                
                horses.forEach(horse => {
                    const horseItem = document.createElement('div');
                    horseItem.className = 'd-flex justify-between align-center';
                    horseItem.style.padding = '15px';
                    horseItem.style.borderBottom = '1px solid var(--border-color)';
                    horseItem.dataset.id = horse.id;
                    
                    const raceDate = horse.formatted_race_date || 'Not scheduled';
                    const status = horse.race_status || 'Unknown';
                    
                    let statusClass = 'badge-info';
                    if (status.toLowerCase() === 'upcoming') statusClass = 'badge-warning';
                    if (status.toLowerCase() === 'completed') statusClass = 'badge-success';
                    
                    horseItem.innerHTML = `
                        <div class="d-flex align-center gap-20">
                            <img src="path-to-default-horse-image.png" alt="${horse.name}" style="width: 60px; height: 60px; border-radius: 50%;">
                            <div>
                                <h5 style="margin-bottom: 5px;">${horse.name}</h5>
                                <div class="d-flex gap-10">
                                    <span class="badge ${statusClass}">${status}</span>
                                    <span style="color: var(--text-medium);">${horse.trainer || 'Trainer not specified'}</span>
                                </div>
                            </div>
                        </div>
                        <div style="text-align: right;">
                            <div style="margin-bottom: 8px;">
                                <strong>Next race:</strong> ${raceDate}
                            </div>
                            <div class="d-flex gap-10" style="justify-content: flex-end;">
                                <button class="btn btn-secondary edit-btn" data-id="${horse.id}"><i class="fas fa-edit"></i> Edit</button>
                                <button class="btn btn-primary delete-btn" data-id="${horse.id}"><i class="fas fa-trash"></i> Delete</button>
                            </div>
                        </div>
                    `;
                    
                    horsesList.appendChild(horseItem);
                    
                    // Add expandable details section
                    if (horse.last_run_notes) {
                        const detailsSection = document.createElement('div');
                        detailsSection.className = 'horse-details';
                        detailsSection.style.display = 'none';
                        detailsSection.style.padding = '15px';
                        detailsSection.style.backgroundColor = 'var(--medium-bg)';
                        detailsSection.style.borderBottom = '1px solid var(--border-color)';
                        
                        detailsSection.innerHTML = `
                            <div class="notes-section">
                                <h6>Last Run Notes:</h6>
                                <p>${horse.last_run_notes}</p>
                            </div>
                            <div class="mt-10">
                                <span>Notify for next race:</span>
                                <label class="switch" style="margin-left: 10px;">
                                    <input type="checkbox" ${parseInt(horse.notify) === 1 ? 'checked' : ''}>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        `;
                        
                        horsesList.appendChild(detailsSection);
                        
                        // Make horse item clickable to show details
                        horseItem.style.cursor = 'pointer';
                        horseItem.addEventListener('click', function(e) {
                            // Don't toggle if clicking on buttons
                            if (e.target.closest('.btn')) return;
                            detailsSection.style.display = detailsSection.style.display === 'none' ? 'block' : 'none';
                        });
                    }
                });
                
                // Add click handlers to edit buttons
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const horseId = this.dataset.id;
                        editHorse(horseId);
                    });
                });
                
                // Add click handlers to delete buttons
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const horseId = this.dataset.id;
                        if (confirm('Are you sure you want to delete this horse?')) {
                            deleteHorse(horseId);
                        }
                    });
                });
            }
            
            // Edit horse - populate form and show modal
            function editHorse(horseId) {
                // Get horse details
                fetch(`get-horses.php?id=${horseId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.horse) {
                            const horse = data.horse;
                            
                            // Populate form fields
                            document.getElementById('horse-id').value = horse.id;
                            document.getElementById('horse-name').value = horse.name;
                            document.getElementById('horse-trainer').value = horse.trainer || '';
                            document.getElementById('next-race-date').value = horse.next_race_date ? formatDateForInput(horse.next_race_date) : '';
                            document.getElementById('last-run-notes').value = horse.last_run_notes || '';
                            document.getElementById('notification').checked = parseInt(horse.notify) === 1;
                            
                            // Change form title and show modal
                            formTitle.textContent = 'Edit Horse';
                            addHorseModal.style.display = 'block';
                        } else {
                            alert('Horse not found');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading horse details:', error);
                        alert('Error loading horse details. Please try again.');
                    });
            }
            
            // Delete horse
            function deleteHorse(horseId) {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('id', horseId);
                
                fetch('horse-action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        loadHorses(sortSelect.value, searchInput.value);
                    } else {
                        alert(data.message || 'Error deleting horse');
                    }
                })
                .catch(error => {
                    console.error('Error deleting horse:', error);
                    alert('Error deleting horse. Please try again.');
                });
            }
            
            // Format date for datetime-local input
            function formatDateForInput(dateString) {
                const date = new Date(dateString);
                return date.toISOString().slice(0, 16);
            }
            
            // Add event listeners
            addHorseBtn.addEventListener('click', function() {
                // Reset form for adding new horse
                addHorseForm.reset();
                document.getElementById('horse-id').value = '';
                formTitle.textContent = 'Add New Horse';
                addHorseModal.style.display = 'block';
            });
            
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.modal').style.display = 'none';
                });
            });
            
            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = 'none';
                }
            });
            
            // Search and sort handlers
            searchInput.addEventListener('input', function() {
                loadHorses(sortSelect.value, this.value);
            });
            
            sortSelect.addEventListener('change', function() {
                loadHorses(this.value, searchInput.value);
            });
            
            // Form submission
            addHorseForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const horseId = document.getElementById('horse-id').value;
                const formData = new FormData();
                
                formData.append('action', horseId ? 'update' : 'add');
                if (horseId) {
                    formData.append('id', horseId);
                }
                
                formData.append('name', document.getElementById('horse-name').value);
                formData.append('trainer', document.getElementById('horse-trainer').value);
                formData.append('next_race_date', document.getElementById('next-race-date').value);
                formData.append('last_run_notes', document.getElementById('last-run-notes').value);
                
                if (document.getElementById('notification').checked) {
                    formData.append('notify', '1');
                }
                
                fetch('horse-action.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        addHorseModal.style.display = 'none';
                        loadHorses(sortSelect.value, searchInput.value);
                    } else {
                        alert(data.message || 'Error saving horse');
                    }
                })
                .catch(error => {
                    console.error('Error saving horse:', error);
                    alert('Error saving horse. Please try again.');
                });
            });
            
            // Calendar toggle
            calendarBtn.addEventListener('click', function() {
                if (calendarSection.style.display === 'none') {
                    horseListSection.style.display = 'none';
                    calendarSection.style.display = 'block';
                    calendarBtn.innerHTML = '<i class="fas fa-list"></i> List View';
                    // Load calendar data here if needed
                } else {
                    calendarSection.style.display = 'none';
                    horseListSection.style.display = 'block';
                    calendarBtn.innerHTML = '<i class="fas fa-calendar"></i> Calendar';
                }
            });
            
            // Initial load
            loadHorses();
        });
    </script>
</body>
</html>