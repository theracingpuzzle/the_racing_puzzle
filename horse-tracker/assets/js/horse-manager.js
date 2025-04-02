/**
 * Horse Tracker - Core CRUD Operations
 */
const HorseManager = {
    /**
     * Initialize the horse manager
     */
    init: function() {
        this.bindEvents();
        this.loadHorses();
    },
    
    /**
     * Bind event handlers
     */
    bindEvents: function() {
        // Add horse form submission
        $('#addHorseForm').on('submit', this.handleAddHorse);
        
        // Edit horse buttons
        $(document).on('click', '.edit-horse', this.handleEditButtonClick);
        
        // Delete horse buttons
        $(document).on('click', '.delete-horse', this.handleDeleteButtonClick);
        
        // View horse details
        $(document).on('click', '.view-horse', this.handleViewHorseDetails);
        
        // Edit from detail modal
        $('#editFromDetail').on('click', this.handleEditFromDetailClick);
    },
    
    /**
     * Load horses from the server
     */
    loadHorses: function(filters = {}) {
        axios.get('api/get-horses.php', { params: filters })
            .then(response => {
                if (response.data.success) {
                    this.renderHorseTable(response.data.horses);
                    
                    // Update horse count
                    $('#horseCount').text(`${response.data.horses.length} horses total`);
                } else {
                    console.error('Error loading horses:', response.data.message);
                }
            })
            .catch(error => {
                console.error('Error fetching horses:', error);
            });
    },
    
    /**
     * Render the horse table with data
     */
    renderHorseTable: function(horses) {
        const tableBody = $('#horse-table-body');
        tableBody.empty();
        
        if (horses.length === 0) {
            $('#horse-table').hide();
            tableBody.after(
                `<div class="alert alert-info text-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    No horses tracked yet. Click "Add Horse" to get started!
                </div>`
            );
            return;
        }
        
        $('#horse-table').show();
        
        horses.forEach(horse => {
            const statusClass = this.getStatusClass(horse.Status);
            const statusLabel = this.getStatusLabel(horse.Status);
            
            const row = `
                <tr data-horse-id="${horse.Horse_ID}">
                    <td>
                        <span class="font-weight-bold">${horse.HorseName}</span>
                        ${horse.Notes ? '<i class="fas fa-sticky-note text-warning ml-1" title="Has notes"></i>' : ''}
                    </td>
                    <td>${horse.Trainer}</td>
                    <td>
                        <span class="text-muted">
                            <i class="far fa-clock mr-1"></i>
                            ${new Date(horse.LastUpdated).toLocaleDateString('en-US', {
                                month: 'short',
                                day: 'numeric',
                                year: 'numeric'
                            })}
                        </span>
                    </td>
                    <td>
                        <span class="badge ${statusClass}">${statusLabel}</span>
                    </td>
                    <td class="text-center">
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-info view-horse" 
                                    data-toggle="modal" 
                                    data-target="#horseDetailModal"
                                    data-horse-id="${horse.Horse_ID}"
                                    data-horse-name="${horse.HorseName}"
                                    data-trainer="${horse.Trainer}"
                                    title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-warning edit-horse"
                                    data-horse-id="${horse.Horse_ID}"
                                    title="Edit Horse">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger delete-horse"
                                    data-horse-id="${horse.Horse_ID}"
                                    title="Delete Horse">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            
            tableBody.append(row);
        });
    },
    
    /**
     * Handle adding a new horse
     */
    handleAddHorse: function(e) {
        e.preventDefault();
        
        const formData = {
            horseName: $('#horseName').val(),
            trainerName: $('#trainerName').val(),
            horseAge: $('#horseAge').val(),
            horseStatus: $('#horseStatus').val(),
            horseNotes: $('#horseNotes').val()
        };
        
        axios.post('api/add-horse.php', formData)
            .then(response => {
                if (response.data.success) {
                    // Close modal and refresh horses
                    $('#addHorseModal').modal('hide');
                    $('#addHorseForm')[0].reset();
                    HorseManager.loadHorses();
                    
                    // Show success message
                    alert('Horse added successfully!');
                } else {
                    alert('Error adding horse: ' + response.data.message);
                }
            })
            .catch(error => {
                console.error('Error adding horse:', error);
                alert('An error occurred while adding the horse.');
            });
    },
    
    /**
     * Handle edit button click
     */
    handleEditButtonClick: function() {
        const horseId = $(this).data('horse-id');
        
        // Load horse data
        axios.get(`api/get-horses.php?id=${horseId}`)
            .then(response => {
                if (response.data.success && response.data.horse) {
                    const horse = response.data.horse;
                    
                    // Populate edit form
                    $('#editHorseId').val(horse.Horse_ID);
                    $('#editHorseName').val(horse.HorseName);
                    $('#editTrainerName').val(horse.Trainer);
                    $('#editHorseAge').val(horse.Age);
                    $('#editHorseStatus').val(horse.Status);
                    $('#editHorseNotes').val(horse.Notes);
                    
                    // Show modal
                    $('#editHorseModal').modal('show');
                } else {
                    alert('Error loading horse data');
                }
            })
            .catch(error => {
                console.error('Error fetching horse:', error);
            });
    },
    
    /**
     * Handle deleting a horse
     */
    handleDeleteButtonClick: function() {
        const horseId = $(this).data('horse-id');
        const horseName = $(this).closest('tr').find('td:first-child span').text();
        
        if (confirm(`Are you sure you want to delete ${horseName}? This cannot be undone.`)) {
            axios.post('api/delete-horse.php', { horseId })
                .then(response => {
                    if (response.data.success) {
                        HorseManager.loadHorses();
                        alert('Horse deleted successfully');
                    } else {
                        alert('Error deleting horse: ' + response.data.message);
                    }
                })
                .catch(error => {
                    console.error('Error deleting horse:', error);
                    alert('An error occurred while deleting the horse.');
                });
        }
    },
    
    /**
     * Handle viewing horse details
     */
    handleViewHorseDetails: function() {
        const horseId = $(this).data('horse-id');
        
        // Load complete horse data
        axios.get(`api/get-horses.php?id=${horseId}&includeActivity=true`)
            .then(response => {
                if (response.data.success && response.data.horse) {
                    const horse = response.data.horse;
                    const activities = response.data.activities || [];
                    
                    // Update modal with horse details
                    $('#modalHorseTitle').text(horse.HorseName);
                    $('#modalHorseName').text(horse.HorseName);
                    $('#modalTrainerName').text(horse.Trainer);
                    $('#modalHorseAge').text(horse.Age || 'Not specified');
                    
                    // Status badge
                    const statusClass = HorseManager.getStatusClass(horse.Status);
                    const statusLabel = HorseManager.getStatusLabel(horse.Status);
                    $('#modalHorseStatus')
                        .attr('class', `badge ${statusClass}`)
                        .text(statusLabel);
                    
                    // Last updated
                    $('#modalLastUpdated').text('Last updated: ' + 
                        new Date(horse.LastUpdated).toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })
                    );
                    
                    // Notes
                    $('#modalHorseNotes').text(horse.Notes || 'No notes available.');
                    
                    // Activities
                    const activityList = $('#horseActivityList');
                    activityList.empty();
                    
                    if (activities.length === 0) {
                        activityList.append(`
                            <li class="list-group-item text-center text-muted">
                                No activity records found.
                            </li>
                        `);
                    } else {
                        activities.forEach(activity => {
                            activityList.append(`
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">${activity.ActivityType}</h6>
                                        <small class="text-muted">
                                            ${new Date(activity.ActivityDate).toLocaleDateString()}
                                        </small>
                                    </div>
                                    <p class="mb-1">${activity.Description}</p>
                                    <small>By: ${activity.RecordedBy}</small>
                                </li>
                            `);
                        });
                    }
                    
                    // Store current horse ID for edit
                    $('#editFromDetail').data('horse-id', horse.Horse_ID);
                } else {
                    alert('Error loading horse details');
                }
            })
            .catch(error => {
                console.error('Error fetching horse details:', error);
            });
    },
    
    /**
     * Handle edit from details modal
     */
    handleEditFromDetailClick: function() {
        const horseId = $(this).data('horse-id');
        
        // Close details modal
        $('#horseDetailModal').modal('hide');
        
        // Trigger edit click handler
        $(`.edit-horse[data-horse-id="${horseId}"]`).click();
    },
    
    /**
     * Get CSS class for status badge
     */
    getStatusClass: function(status) {
        switch(status) {
            case 'active': return 'badge-success';
            case 'injured': return 'badge-danger';
            case 'rest': return 'badge-warning';
            case 'training': return 'badge-info';
            default: return 'badge-secondary';
        }
    },
    
    /**
     * Get display label for status
     */
    getStatusLabel: function(status) {
        switch(status) {
            case 'active': return 'Active';
            case 'injured': return 'Injured';
            case 'rest': return 'Resting';
            case 'training': return 'In Training';
            default: return 'Unknown';
        }
    }
};

// Initialize on document ready
$(document).ready(function() {
    HorseManager.init();
});