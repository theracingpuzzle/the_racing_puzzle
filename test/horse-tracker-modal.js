/**
 * Opens the horse tracker modal and pre-populates the form fields
 * with the provided horse name, jockey, and trainer data.
 * 
 * @param {string} horseName - The name of the horse to track
 * @param {string} jockey - The jockey's name
 * @param {string} trainer - The trainer's name
 * @param {string} horseId - Optional horse ID
 * @param {string} silkUrl - Optional URL to the jockey's silks image
 */
function openQuickTracker(horseName, jockey, trainer, horseId, silkUrl) {
    // Add debugging logs
    console.log('Populating horse tracker with the following information:');
    console.log('Horse Name:', horseName);
    console.log('Jockey:', jockey);
    console.log('Trainer:', trainer);
    console.log('Horse ID:', horseId);
    console.log('Silk URL:', silkUrl);
    
    // Get the horse modal
    const horseModal = document.getElementById('horseModal');
    
    if (!horseModal) {
        console.error('Horse modal not found in the DOM');
        return;
    }
    
    // Reset the form before populating
    const horseForm = document.getElementById('horseForm');
    if (horseForm) {
        horseForm.reset();
    }
    
    // Pre-populate the form fields with the provided data
    const horseNameField = document.getElementById('horse_name');
    const trainerField = document.getElementById('trainer');
    const commentsField = document.getElementById('comments');
    
    // Create hidden fields for additional data if they don't exist
    let horseIdField = document.getElementById('horse_id');
    if (!horseIdField && horseId) {
        horseIdField = document.createElement('input');
        horseIdField.type = 'hidden';
        horseIdField.id = 'horse_id';
        horseIdField.name = 'horse_id';
        horseForm.appendChild(horseIdField);
    }
    
    let silkUrlField = document.getElementById('silk_url');
    if (!silkUrlField && silkUrl) {
        silkUrlField = document.createElement('input');
        silkUrlField.type = 'hidden';
        silkUrlField.id = 'silk_url';
        silkUrlField.name = 'silk_url';
        horseForm.appendChild(silkUrlField);
    }
    
    // Create hidden field for action
    let actionField = document.getElementById('action');
    if (!actionField) {
        actionField = document.createElement('input');
        actionField.type = 'hidden';
        actionField.id = 'action';
        actionField.name = 'action';
        actionField.value = 'add';
        horseForm.appendChild(actionField);
    }
    
    // Set values for the fields
    if (horseNameField) {
        horseNameField.value = horseName || '';
    }
    
    if (trainerField) {
        trainerField.value = trainer || '';
    }
    
    // Add jockey information to comments if provided
    if (commentsField && jockey) {
        commentsField.value = `Jockey: ${jockey}`;
    }
    
    // Set values for hidden fields
    if (horseIdField && horseId) {
        horseIdField.value = horseId;
    }
    
    if (silkUrlField && silkUrl) {
        silkUrlField.value = silkUrl;
    }
    
    // Show the modal with proper styling
    horseModal.style.display = 'block';
    horseModal.style.opacity = '1';
    document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    
    // Focus on the first input field (optional)
    if (horseNameField) {
        horseNameField.focus();
    }
}

// The main initialization code - only one DOMContentLoaded event
document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('horseModal');
    const closeBtn = document.querySelector('.horse-modal-close');
    const cancelBtn = document.getElementById('cancelButton');
    const horseForm = document.getElementById('horseForm');
    
    if (!modal || !horseForm) {
        console.error('Required elements not found');
        return;
    }
    
    // Check if openModalBtn exists before adding event listener
    const openModalBtn = document.getElementById('openModalBtn');
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
        });
    }
    
    // Collapsible section elements
    const collapsibleSection = document.querySelector('.collapsible-section');
    const collapsibleHeader = document.querySelector('.collapsible-header');
    
    // Close button click handler
    if (closeBtn) {
        closeBtn.onclick = function() {
            closeModal();
        };
    }
    
    // Cancel button click handler
    if (cancelBtn) {
        cancelBtn.onclick = function() {
            closeModal();
        };
    }
    
    // Function to close the modal with a fade effect
    function closeModal() {
        if (!modal) return;
        
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
    if (collapsibleHeader && collapsibleSection) {
        collapsibleHeader.addEventListener('click', function() {
            collapsibleSection.classList.toggle('active');
        });
    }
    
    // Handle quick comment buttons
    const quickCommentBtns = document.querySelectorAll('.quick-comment-btn');
    const commentsTextarea = document.getElementById('comments');
    
    if (quickCommentBtns.length > 0 && commentsTextarea) {
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
    }
    
    // Form submission handler
    horseForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent default form submission
        
        // Validate form
        if (!validateForm()) {
            return false;
        }
        
        // Create FormData object
        const formData = new FormData(horseForm);
        
        // Make sure we have the required fields for horse-action.php
        // Map the form field names to what the PHP script expects
        const formDataForSubmit = new FormData();
        formDataForSubmit.append('action', formData.get('action') || 'add');
        formDataForSubmit.append('name', formData.get('horse_name'));
        formDataForSubmit.append('trainer', formData.get('trainer'));
        formDataForSubmit.append('last_run_notes', formData.get('comments'));
        
        // Add optional fields if they exist
        if (formData.get('horse_id')) {
            formDataForSubmit.append('horse_id', formData.get('horse_id'));
        }
        
        if (formData.get('silk_url')) {
            formDataForSubmit.append('silk_url', formData.get('silk_url'));
        } else {
            // Default empty silk URL
            formDataForSubmit.append('silk_url', '');
        }
        
        // Submit the form data using fetch API
        fetch('../horse-tracker/horse-action.php', {
            method: 'POST',
            body: formDataForSubmit
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Horse added to your tracker successfully!');
                
                // Reset the form
                horseForm.reset();
                
                // Close the modal
                closeModal();
            } else {
                // Show error message
                alert('Error: ' + (data.message || 'Failed to add horse to tracker'));
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            alert('An error occurred while saving the horse. Please try again.');
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
        const numericFields = [document.getElementById('age')];
        numericFields.forEach(field => {
            if (field && field.value && (isNaN(field.value) || parseInt(field.value) < 0)) {
                field.style.borderColor = '#f44336';
                isValid = false;
                
                // Add shake animation
                field.classList.add('shake');
                setTimeout(() => {
                    field.classList.remove('shake');
                }, 500);
            }
        });
        
        return isValid;
    }
    
    // Clear field error on input
    horseForm.addEventListener('input', function(e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT' || e.target.tagName === 'TEXTAREA') {
            e.target.style.borderColor = '';
        }
    });
});