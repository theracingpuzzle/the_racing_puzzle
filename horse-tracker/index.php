<?php
// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();
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
    <link rel="stylesheet" href="../assets/css/main.css">
    
    <link rel="stylesheet" href="assets/css/horse-tracker.css">


    <link rel="stylesheet" href="../test/horse-tracker-modal.css">
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
                            <button id="openModalBtn" class="btn btn-primary"></i> Add New Horse </button>
                                <button id="view-calendar" class="btn btn-secondary">
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

<!-- Horse Modal HTML Structure -->
<div id="horseModal" class="horse-modal">
        <div class="horse-modal-content">
            <div class="horse-modal-header">
                <h2><i class="fas fa-horse"></i> Horse Details</h2>
                <span class="horse-modal-close"><i class="fas fa-times"></i></span>
            </div>
            <div class="horse-modal-body">
                <form id="horseForm" method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="horse_name" class="required-field">Horse Name</label>
                            <input type="text" id="horse_name" name="horse_name" required placeholder="Enter horse name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="trainer" class="required-field">Trainer</label>
                            <input type="text" id="trainer" name="trainer" required placeholder="Enter trainer name">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="comments">Comments</label>
                            <textarea id="comments" name="comments" rows="4" placeholder="Enter any notes or comments about this horse"></textarea>
                        </div>
                    </div>

                    <!-- Additional Information (Optional Fields) -->
                    <div class="collapsible-section">
                        <div class="collapsible-header">
                            <h3>Additional Information</h3>
                            <i class="fas fa-chevron-down"></i>
                        </div>
                        <div class="collapsible-content">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="age">Age</label>
                                    <input type="number" id="age" name="age" min="1" max="30" placeholder="Horse age">
                                </div>
                                <div class="form-group">
                                    <label for="breed">Breed</label>
                                    <input type="text" id="breed" name="breed" placeholder="Horse breed">
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="color">Color</label>
                                    <select id="color" name="color">
                                        <option value="">-- Select Color --</option>
                                        <option value="Bay">Bay</option>
                                        <option value="Black">Black</option>
                                        <option value="Chestnut">Chestnut</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Brown">Brown</option>
                                        <option value="White">White</option>
                                        <option value="Palomino">Palomino</option>
                                        <option value="Roan">Roan</option>
                                        <option value="Dun">Dun</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select id="gender" name="gender">
                                        <option value="">-- Select Gender --</option>
                                        <option value="Colt">Colt</option>
                                        <option value="Filly">Filly</option>
                                        <option value="Gelding">Gelding</option>
                                        <option value="Mare">Mare</option>
                                        <option value="Stallion">Stallion</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Comments Section -->
                    <div class="quick-comments-box">
                        <h3 class="quick-comments-title">Quick Notes</h3>
                        <div class="quick-comment-buttons">
                            <button type="button" class="quick-comment-btn" data-comment="Strong finish, showed good potential">Strong finish</button>
                            <button type="button" class="quick-comment-btn" data-comment="Started well but faded in the final stretch">Started well but faded</button>
                            <button type="button" class="quick-comment-btn" data-comment="Struggled with the going, consider different ground next time">Struggled with going</button>
                            <button type="button" class="quick-comment-btn" data-comment="Traveled well throughout the race">Traveled well</button>
                            <button type="button" class="quick-comment-btn" data-comment="Needs more distance, consider longer races">Needs more distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Better suited to shorter distances">Better at shorter distance</button>
                            <button type="button" class="quick-comment-btn" data-comment="Watch for next time, showed improvement">Watch for next time</button>
                            <button type="button" class="quick-comment-btn" data-comment="Add blinkers/headgear next time">Add blinkers next time</button>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" id="cancelButton" class="btn btn-cancel">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" name="submit" class="btn btn-submit">
                            <i class="fas fa-check"></i> Save Horse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include '../test/bottom-nav.php'; ?>

    <!-- <script src="../test/horse-tracker-modal.js"></script> -->

    <script>
    
    document.addEventListener('DOMContentLoaded', function() {
    // Elements for horse listing
    const horsesList = document.getElementById('horses-list');
    const horsesBody = horsesList.querySelector('.card-body');
    const searchInput = document.getElementById('search-horses');
    const sortSelect = document.getElementById('sort-by');
    const calendarBtn = document.getElementById('view-calendar');
    const horseListSection = document.querySelector('.horse-list-section');
    const calendarSection = document.querySelector('.calendar-section');
    
    // Horse modal elements
    const modal = document.getElementById('horseModal');
    const horseForm = document.getElementById('horseForm');
    const closeBtn = document.querySelector('.horse-modal-close');
    const cancelBtn = document.getElementById('cancelButton');
    const openModalBtn = document.getElementById('openModalBtn');
    
    // Collapsible section elements
    const collapsibleSection = document.querySelector('.collapsible-section');
    const collapsibleHeader = document.querySelector('.collapsible-header');
    
    // Quick comment buttons
    const quickCommentBtns = document.querySelectorAll('.quick-comment-btn');
    const commentsTextarea = document.getElementById('comments');
    
    // Initialize the page
    loadHorses('name', '');
    
    // Function to load horses from the server
    function loadHorses(sort = 'name', search = '') {
        horsesBody.innerHTML = '<div class="loading">Loading horses...</div>';
        
        fetch(`get-horses.php?sort=${encodeURIComponent(sort)}&search=${encodeURIComponent(search)}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    displayHorses(data.horses);
                } else {
                    horsesBody.innerHTML = '<div class="alert alert-danger">Failed to load horses</div>';
                }
            })
            .catch(error => {
                console.error('Error fetching horses:', error);
                horsesBody.innerHTML = '<div class="alert alert-danger">Error loading horses. Please try again.</div>';
            });
    }
    
    // Function to display horses in the UI
    function displayHorses(horses) {
        if (horses.length === 0) {
            horsesBody.innerHTML = '<div class="empty-state">No horses found. Add your first horse to start tracking!</div>';
            return;
        }
        
        let html = '<div class="horse-grid">';
        
        horses.forEach(horse => {
            html += `
                <div class="horse-card" data-id="${horse.id}">
                    <div class="horse-card-header">
                        <h3 class="horse-name">${horse.name}</h3>
                        <div class="horse-actions">
                            <button class="btn-icon edit-horse" data-id="${horse.id}" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon delete-horse" data-id="${horse.id}" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="horse-card-body">
    ${horse.silk_url ? `
    <div class="horse-silk-container">
        <img src="${horse.silk_url}" 
             alt="Jockey Silk" 
             class="horse-silk-image">
    </div>` : ''}
    <p><strong>Trainer:</strong> ${horse.trainer || 'Not specified'}</p>
    ${horse.jockey ? `<p><strong>Jockey:</strong> ${horse.jockey}</p>` : ''}
    <p><strong>Next Race:</strong> ${horse.formatted_race_date || 'Not scheduled'}</p>
    ${horse.last_run_notes ? `<p class="horse-notes"><strong>Notes:</strong> ${horse.last_run_notes}</p>` : ''}
</div>
                    <div class="horse-card-footer">
                        <span class="horse-added-date">Added: ${formatDate(horse.date_added)}</span>
                        <label class="toggle-switch">
                            <input type="checkbox" class="notify-toggle" data-id="${horse.id}" ${horse.notify == 1 ? 'checked' : ''}>
                            <span class="toggle-slider"></span>
                            <span class="toggle-label">Notify</span>
                        </label>
                    </div>
                </div>
            `;
        });
        
        html += '</div>';
        horsesBody.innerHTML = html;
        
        // Add event listeners to the new horse cards
        attachHorseCardListeners();
    }
    
    // Format date helper
    function formatDate(dateString) {
        if (!dateString) return 'Unknown';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-GB', { 
            day: 'numeric', 
            month: 'short', 
            year: 'numeric' 
        });
    }
    
    // Attach event listeners to horse cards
    function attachHorseCardListeners() {
        // Edit horse buttons
        document.querySelectorAll('.edit-horse').forEach(btn => {
            btn.addEventListener('click', function() {
                const horseId = this.getAttribute('data-id');
                loadHorseForEdit(horseId);
            });
        });
        
        // Delete horse buttons
        document.querySelectorAll('.delete-horse').forEach(btn => {
            btn.addEventListener('click', function() {
                const horseId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this horse?')) {
                    deleteHorse(horseId);
                }
            });
        });
        
        // Notification toggles
        document.querySelectorAll('.notify-toggle').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const horseId = this.getAttribute('data-id');
                const notifyStatus = this.checked ? 1 : 0;
                updateNotificationStatus(horseId, notifyStatus);
            });
        });
    }
    
    // Function to load a horse for editing
    function loadHorseForEdit(horseId) {
        fetch(`get-horses.php?id=${horseId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateHorseForm(data.horse);
                    openModal();
                } else {
                    alert('Could not load horse details. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error loading horse for edit:', error);
                alert('An error occurred while loading horse details.');
            });
    }
    
    // Function to populate the form with horse data
    function populateHorseForm(horse) {
        document.getElementById('horse_name').value = horse.name || '';
        document.getElementById('trainer').value = horse.trainer || '';
        document.getElementById('comments').value = horse.last_run_notes || '';
        
        // Add a hidden field for the horse ID if editing
        let idField = document.getElementById('horse_id');
        if (!idField) {
            idField = document.createElement('input');
            idField.type = 'hidden';
            idField.id = 'horse_id';
            idField.name = 'id';
            horseForm.appendChild(idField);
        }
        idField.value = horse.id;
    }
    
    // Function to delete a horse
    function deleteHorse(horseId) {
    fetch('delete-horse.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${horseId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Reload the horses list
            loadHorses(sortSelect.value, searchInput.value);
            showNotification('Horse deleted successfully!', 'success');
        } else {
            alert('Failed to delete horse: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting horse:', error);
        alert('An error occurred while deleting the horse.');
    });
}
    
    // Function to update notification status
    function updateNotificationStatus(horseId, notifyStatus) {
        fetch('../test/update-notification.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `horse_id=${horseId}&notify=${notifyStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                alert('Failed to update notification status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error updating notification status:', error);
            alert('An error occurred while updating notification status.');
        });
    }
    
    // Add button click handler to open modal
    openModalBtn.addEventListener('click', function() {
        // Clear the form when opening for a new horse
        horseForm.reset();
        
        // Remove any existing horse_id field
        const existingIdField = document.getElementById('horse_id');
        if (existingIdField) {
            existingIdField.remove();
        }
        
        openModal();
    });
    
    // Function to open the modal
    function openModal() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    }
    
    // Close button click handler
    closeBtn.onclick = function() {
        closeModal();
    };
    
    // Cancel button click handler
    cancelBtn.onclick = function() {
        closeModal();
    };
    
    // Function to close the modal with a fade effect
    function closeModal() {
        modal.style.opacity = '0';
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.opacity = '1';
            document.body.style.overflow = 'auto'; // Re-enable scrolling
        }, 300);
    }
    
    // Close when clicking outside the modal
    window.onclick = function(event) {
        if (event.target == modal) {
            closeModal();
        }
    };
    
    // Toggle collapsible sections
    collapsibleHeader.addEventListener('click', function() {
        collapsibleSection.classList.toggle('active');
    });
    
    // Handle quick comment buttons
    quickCommentBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const commentText = this.getAttribute('data-comment');
            
            // Toggle active state visually
            this.classList.toggle('active');
            
            // Handle adding/removing comments from textarea
            if (this.classList.contains('active')) {
                // Add the comment to textarea
                if (commentsTextarea.value) {
                    // If there's already text, add a new line
                    commentsTextarea.value += '\n• ' + commentText;
                } else {
                    // If textarea is empty, just add the comment
                    commentsTextarea.value = '• ' + commentText;
                }
            } else {
                // Remove the comment from textarea
                const commentWithBullet = '• ' + commentText;
                const commentPattern = new RegExp('\\n?' + commentWithBullet.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
                commentsTextarea.value = commentsTextarea.value.replace(commentPattern, '');
                
                // Clean up any empty bullet at the beginning
                commentsTextarea.value = commentsTextarea.value.replace(/^• $/, '');
            }
        });
    });
    
    // Form submission handler
horseForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return false;
    }
    
    // Prepare form data for submission
    const formData = new FormData();
    
    // Add action parameter based on whether this is an edit or add operation
    const isEdit = document.getElementById('horse_id') !== null && 
                   document.getElementById('horse_id').value !== '';
    
    // Set the action type
    formData.append('action', isEdit ? 'update' : 'add');
    
    // Map form field names to the expected names in horse-action.php
    formData.append('name', document.getElementById('horse_name').value);
    formData.append('trainer', document.getElementById('trainer').value);
    formData.append('last_run_notes', document.getElementById('comments').value);
    
    // If editing, add the ID
    if (isEdit) {
        console.log("Editing horse with ID:", document.getElementById('horse_id').value);
        formData.append('id', document.getElementById('horse_id').value);
    }
    
    // Send the form data to the server
    fetch('horse-action.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the modal
            closeModal();
            
            // Reload the horses list
            loadHorses(sortSelect.value, searchInput.value);
            
            // Show a success message
            const message = isEdit ? 'Horse updated successfully!' : 'Horse added successfully!';
            showNotification(message, 'success');
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
});
    
    // Form validation
    function validateForm() {
        let isValid = true;
        
        // Check required fields
        const requiredFields = horseForm.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#f44336';
                isValid = false;
                
                // Add shake animation
                field.classList.add('shake');
                setTimeout(() => {
                    field.classList.remove('shake');
                }, 500);
            } else {
                field.style.borderColor = '';
            }
        });
        
        // Validate numeric fields
        const ageField = document.getElementById('age');
        if (ageField && ageField.value && (isNaN(ageField.value) || parseInt(ageField.value) < 0)) {
            ageField.style.borderColor = '#f44336';
            isValid = false;
            
            // Add shake animation
            ageField.classList.add('shake');
            setTimeout(() => {
                ageField.classList.remove('shake');
            }, 500);
        }
        
        return isValid;
    }
    
    // Clear field error on input
    horseForm.addEventListener('input', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
            e.target.style.borderColor = '';
        }
    });
    
    // Function to show notification
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
            </div>
            <button class="notification-close"><i class="fas fa-times"></i></button>
        `;
        
        // Add to document
        document.body.appendChild(notification);
        
        // Show with animation
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            hideNotification(notification);
        }, 5000);
        
        // Add close button handler
        notification.querySelector('.notification-close').addEventListener('click', function() {
            hideNotification(notification);
        });
    }
    
    // Function to hide notification
    function hideNotification(notification) {
        notification.classList.remove('show');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
    
    // Search and sort handlers
    searchInput.addEventListener('input', function() {
        loadHorses(sortSelect.value, this.value);
    });
    
    sortSelect.addEventListener('change', function() {
        loadHorses(this.value, searchInput.value);
    });
    
    // Calendar toggle
    calendarBtn.addEventListener('click', function() {
        if (calendarSection.style.display === 'none') {
            horseListSection.style.display = 'none';
            calendarSection.style.display = 'block';
            calendarBtn.innerHTML = '<i class="fas fa-list"></i> List View';
            // Load calendar data here if implemented
        } else {
            calendarSection.style.display = 'none';
            horseListSection.style.display = 'block';
            calendarBtn.innerHTML = '<i class="fas fa-calendar"></i> Calendar';
        }
    });
});
            
    </script>
</body>
</html>