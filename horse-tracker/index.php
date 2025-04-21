<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Tracker</title>
    <meta name="description" content="Track your favorite race horses and receive notifications for upcoming races">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Link to Horse Tracker CSS -->
    <link rel="stylesheet" href="assets/css/horse_tracker.css">
</head>

<?php include_once "../includes/menu_bar.php"; ?>

<body>
    <div class="container">
        <header>
            <h1>Horse Tracker</h1>
            <p>Track your favourite race horses and receive notifications for upcoming races</p>
        </header>

        <main>
            <div class="horse-list-section">
                <div class="list-controls">
                    <div class="filters">
                        <input type="text" id="search-horses" placeholder="Search horses...">
                        <select id="sort-by">
                            <option value="name">Sort by Name</option>
                            <option value="date">Sort by Next Race Date</option>
                            <option value="added">Sort by Date Added</option>
                        </select>
                    </div>
                    <div class="data-actions">
                        <button id="add-horse-btn" class="btn"><i class="fas fa-plus"></i> Add Horse</button>
                        <button id="view-calendar" class="btn secondary-btn" onclick="window.location.href='../calendar/index.php'">
  <i class="fas fa-calendar"></i> Calendar
</button>

                    </div>
                </div>
                
                <div id="horses-list">
                    <div class="loading">Loading horses...</div>
                </div>
            </div>
            
            <!-- Calendar Section (Hidden by default) -->
            <section class="calendar-section" style="display: none;">
                <h2>Race Calendar</h2>
                <div id="calendar-container">
                    <!-- Calendar will be populated by JavaScript -->
                    <div class="loading">Loading calendar...</div>
                </div>
            </section>
        </main>

        <footer>
            
        </footer>
    </div>

    <!-- Add Horse Modal -->
    <div id="add-horse-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="horse-form-title">Add New Horse</h2>
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
                <!-- <div class="form-group">
                    <label for="horse-jockey">Jockey:</label>
                    <input type="text" id="horse-jockey">
                </div> -->
                <!-- <div class="form-group">
                    <label for="next-race-date">Next Race Date:</label>
                    <input type="datetime-local" id="next-race-date">
                </div> -->
                <div class="form-group">
                    <label for="last-run-notes">Last Run Notes:</label>
                    <textarea id="last-run-notes" rows="4"></textarea>
                </div>
                <div class="form-group">
                    <label for="notification">
                        <input type="checkbox" id="notification" checked>
                        Notify for next race
                    </label>
                </div>
                <button type="submit" class="btn">Save Horse</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM elements
            const horsesList = document.getElementById('horses-list');
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
                            horsesList.innerHTML = '<div class="no-results">No horses found</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading horses:', error);
                        horsesList.innerHTML = '<div class="error">Error loading horses. Please try again.</div>';
                    });
            }
            
            // Display horses in the list
            function displayHorses(horses) {
                horsesList.innerHTML = '';
                
                horses.forEach(horse => {
                    const horseItem = document.createElement('div');
                    horseItem.className = 'horse-item';
                    horseItem.dataset.id = horse.id;
                    
                    const raceDate = horse.formatted_race_date || 'Not scheduled';
                    
                    horseItem.innerHTML = `
                        <div class="horse-header">
                            <h3>${horse.name}</h3>
                            <div class="race-date">Next race: ${raceDate}</div>
                        </div>
                        <div class="horse-details">
                            <div>
                                <p><strong>Trainer:</strong> ${horse.trainer || 'Not specified'}</p>
                                <div class="notes-section">
                                    <h4>Last Run Notes:</h4>
                                    <p>${horse.last_run_notes || 'No notes available'}</p>
                                </div>
                                <div class="notification-toggle">
                                    <span>Notify for next race:</span>
                                    <label class="switch">
                                        <input type="checkbox" ${parseInt(horse.notify) === 1 ? 'checked' : ''}>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn edit-btn" data-id="${horse.id}"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn delete-btn" data-id="${horse.id}"><i class="fas fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    horsesList.appendChild(horseItem);
                });
                
                // Add click handlers to horse headers
                document.querySelectorAll('.horse-header').forEach(header => {
                    header.addEventListener('click', function() {
                        const details = this.nextElementSibling;
                        details.classList.toggle('active');
                    });
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
                            // document.getElementById('horse-trainer').value = horse.trainer || '';
                            // document.getElementById('horse-jockey').value = horse.jockey || '';
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
                
                fetch('horse-actions.php', {
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
                formData.append('jockey', document.getElementById('horse-jockey').value);
                formData.append('next_race_date', document.getElementById('next-race-date').value);
                formData.append('last_run_notes', document.getElementById('last-run-notes').value);
                
                if (document.getElementById('notification').checked) {
                    formData.append('notify', '1');
                }
                
                fetch('horse-actions.php', {
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