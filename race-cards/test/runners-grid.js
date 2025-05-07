/**
 * Runners Grid Component - Handles runner cards interactions
 */
document.addEventListener('DOMContentLoaded', function() {
    // Initialize track horse buttons
    initTrackHorseButtons();
    
    /**
     * Initialize track horse buttons functionality
     */
    function initTrackHorseButtons() {
        const trackButtons = document.querySelectorAll('.track-horse-btn');
        
        if (!trackButtons.length) return;
        
        trackButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Get horse data
                const horseName = this.getAttribute('data-horse-name');
                const jockey = this.getAttribute('data-jockey');
                const trainer = this.getAttribute('data-trainer');
                
                // Open tracker modal with horse data
                openTrackerModal(horseName, jockey, trainer);
            });
        });
    }
    
    /**
     * Open the horse tracker modal with pre-filled data
     * @param {string} horseName - The horse name
     * @param {string} jockey - The jockey name
     * @param {string} trainer - The trainer name
     */
    function openTrackerModal(horseName, jockey, trainer) {
        const modal = document.getElementById('horseTrackerModal');
        
        if (!modal) return;
        
        // Fill form fields with data
        if (horseName) {
            document.getElementById('horse_name').value = horseName;
        }
        
        if (jockey) {
            document.getElementById('jockey').value = jockey;
        }
        
        if (trainer) {
            document.getElementById('trainer').value = trainer;
        }
        
        // Show modal
        modal.style.display = 'block';
    }
    
    // Initialize modal close functionality
    const modal = document.getElementById('horseTrackerModal');
    
    if (modal) {
        // Close button
        const closeBtn = modal.querySelector('.horse-modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeModal);
        }
        
        // Cancel button
        const cancelBtn = document.getElementById('cancelButton');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', closeModal);
        }
        
        // Close when clicking outside modal
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
        
        // Initialize quick comment buttons
        const quickCommentBtns = modal.querySelectorAll('.quick-comment-btn');
        const commentsTextarea = document.getElementById('comments');
        
        if (quickCommentBtns.length && commentsTextarea) {
            quickCommentBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const commentText = this.getAttribute('data-comment');
                    
                    // Toggle active state
                    this.classList.toggle('active');
                    
                    // Handle adding/removing comments
                    if (this.classList.contains('active')) {
                        // Add comment to textarea
                        if (commentsTextarea.value) {
                            commentsTextarea.value += '\n• ' + commentText;
                        } else {
                            commentsTextarea.value = '• ' + commentText;
                        }
                    } else {
                        // Remove comment from textarea
                        const commentWithBullet = '• ' + commentText;
                        const commentPattern = new RegExp('\\n?' + commentWithBullet.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
                        commentsTextarea.value = commentsTextarea.value.replace(commentPattern, '');
                        
                        // Clean up any empty bullet at beginning
                        commentsTextarea.value = commentsTextarea.value.replace(/^• $/, '');
                    }
                });
            });
        }
    }
    
    /**
     * Close the tracker modal
     */
    function closeModal() {
        const modal = document.getElementById('horseTrackerModal');
        
        if (!modal) return;
        
        // Hide with fade effect
        modal.style.opacity = '0';
        
        setTimeout(() => {
            modal.style.display = 'none';
            modal.style.opacity = '1';
            
            // Reset form
            const form = modal.querySelector('form');
            if (form) {
                form.reset();
            }
        }, 300);
    }
});