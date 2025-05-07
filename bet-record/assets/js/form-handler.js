/**
 * form-handler.js - Handles form validation and submission
 * Responsible for validating the betting form before submission and
 * properly handling the form submission process
 */
document.addEventListener('DOMContentLoaded', function() {
    const bettingForm = document.getElementById('bettingForm');
    const betTypeSelect = document.getElementById('bet_type');
    const selectionsCountGroup = document.getElementById('selectionsCountGroup');
    const numberOfSelectionsSelect = document.getElementById('numberOfSelections');
    const selectionsContainer = document.getElementById('selectionsContainer');
    const outcomeSelect = document.getElementById('outcome');
    
    // Initialize event listeners for form elements
    if (betTypeSelect) {
        betTypeSelect.addEventListener('change', handleBetTypeChange);
    }
    
    if (numberOfSelectionsSelect) {
        numberOfSelectionsSelect.addEventListener('change', updateSelections);
    }
    
    // Handle outcome change
    if (outcomeSelect) {
        outcomeSelect.addEventListener('change', function() {
            // Calculate returns when outcome changes
            if (window.calculateReturns) {
                window.calculateReturns();
            }
            
            // Update outcome badges visibility
            const badges = document.querySelectorAll('.outcome-badges .badge');
            badges.forEach(badge => {
                if (badge.classList.contains(`badge-${this.value.toLowerCase()}`)) {
                    badge.style.opacity = '1';
                } else {
                    badge.style.opacity = '0.3';
                }
            });
        });
    }
    
    // Form submission handler
if (bettingForm) {
    bettingForm.addEventListener('submit', function(e) {
        // Prevent default submission for validation
        e.preventDefault();
        
        console.log('Form submission intercepted for validation');
        
        // Validate form
        if (!validateForm()) {
            console.log('Form validation failed');
            return false;
        }
        
        console.log('Form validation passed, preparing to submit');
        
        // Add hidden fields for special data
        
        // For Lost outcomes, set returns to 0
        if (outcomeSelect && outcomeSelect.value === 'Lost') {
            let returnsField = document.querySelector('input[name="returns"]');
            if (!returnsField) {
                returnsField = document.createElement('input');
                returnsField.type = 'hidden';
                returnsField.name = 'returns';
                bettingForm.appendChild(returnsField);
            }
            returnsField.value = '0';
        }
        
        // Handle multi-selections if needed
        if (betTypeSelect && ['Accumulator', 'Cover/Insure'].includes(betTypeSelect.value)) {
            // Add additional selections data to form
            addAdditionalSelectionsToForm();
        }
        
        // Update the form action to point to the new file
        bettingForm.action = 'includes/process_bet.php';
        
        // Submit the form by sending it directly to the server
        console.log('Submitting form to server');
        
        // Use direct form submission
        bettingForm.submit();
    });
}
    
    // Form validation function
    function validateForm() {
        let isValid = true;
        
        // Check required fields in main form
        const requiredFields = bettingForm.querySelectorAll('[required]');
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
        
        // For multi-selection bets, check additional selections
        if (betTypeSelect && selectionsContainer && 
            (betTypeSelect.value === 'Accumulator' || betTypeSelect.value === 'Cover/Insure') && isValid) {
            
            const selections = selectionsContainer.querySelectorAll('.selection-box');
            
            for (let i = 1; i < selections.length; i++) {
                const selection = selections[i];
                const selectionField = selection.querySelector(`#selection_${i}`);
                const racecourseField = selection.querySelector(`#racecourse_${i}`);
                const oddsField = selection.querySelector(`#odds_${i}`);
                
                // Check required fields for each selection
                [selectionField, racecourseField, oddsField].forEach(field => {
                    if (!field || !field.value.trim()) {
                        if (field) {
                            field.style.borderColor = '#f44336';
                            
                            // Add shake animation
                            field.classList.add('shake');
                            setTimeout(() => {
                                field.classList.remove('shake');
                            }, 500);
                            
                            // Scroll to invalid field
                            field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        isValid = false;
                    } else if (field) {
                        field.style.borderColor = '';
                    }
                });
            }
        }
        
        // Validate odds format
        const oddsFields = document.querySelectorAll('[id^="odds_"]');
        oddsFields.forEach(field => {
            const odds = field.value.trim();
            if (odds) {
                const isValidOdds = /^\d+\/\d+$/.test(odds) || /^\d+(\.\d+)?$/.test(odds);
                if (!isValidOdds) {
                    field.style.borderColor = '#f44336';
                    isValid = false;
                    
                    // Add shake animation
                    field.classList.add('shake');
                    setTimeout(() => {
                        field.classList.remove('shake');
                    }, 500);
                    
                    // Scroll to invalid field
                    field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
        
        return isValid;
    }
    
    // Handle bet type change
    function handleBetTypeChange() {
        if (!betTypeSelect || !selectionsCountGroup || !selectionsContainer) return;
        
        if (betTypeSelect.value === 'Accumulator' || betTypeSelect.value === 'Cover/Insure') {
            selectionsCountGroup.style.display = 'block';
            updateSelections();
        } else {
            selectionsCountGroup.style.display = 'none';
            // Reset to single selection
            while (selectionsContainer.children.length > 1) {
                selectionsContainer.removeChild(selectionsContainer.lastChild);
            }
            const selectionTitle = document.querySelector('.selection-title');
            if (selectionTitle) {
                selectionTitle.textContent = 'Selection Details';
            }
        }
        
        // Recalculate returns when bet type changes
        if (window.calculateReturns) {
            window.calculateReturns();
        }
    }
    
    // Update selections based on numberOfSelections
    function updateSelections() {
        if (!numberOfSelectionsSelect || !selectionsContainer) return;
        
        const count = parseInt(numberOfSelectionsSelect.value);
        const currentCount = selectionsContainer.children.length;
        
        if (count > currentCount) {
            // Add more selections
            for (let i = currentCount; i < count; i++) {
                const newSelection = createSelectionBox(i);
                selectionsContainer.appendChild(newSelection);
            }
        } else if (count < currentCount) {
            // Remove excess selections
            while (selectionsContainer.children.length > count) {
                selectionsContainer.removeChild(selectionsContainer.lastChild);
            }
        }
        
        // Update titles
        const selections = selectionsContainer.querySelectorAll('.selection-box');
        selections.forEach((selection, index) => {
            const titleEl = selection.querySelector('.selection-title');
            if (titleEl) {
                titleEl.textContent = `Selection ${index + 1}`;
            }
        });
        
        // Add event listeners to new odds inputs
        document.querySelectorAll('[id^="odds_"]').forEach(input => {
            input.addEventListener('input', function() {
                if (window.calculateReturns) {
                    window.calculateReturns();
                }
            });
        });
        
        // Recalculate returns when selections change
        if (window.calculateReturns) {
            window.calculateReturns();
        }
    }
    
    // Create a new selection box
    function createSelectionBox(index) {
        if (!selectionsContainer || !selectionsContainer.children[0]) return null;
        
        const template = selectionsContainer.children[0].cloneNode(true);
        template.setAttribute('data-index', index);
        
        // Update all input IDs and clear values
        const inputs = template.querySelectorAll('input, select');
        inputs.forEach(input => {
            const baseId = input.id.split('_')[0];
            input.id = `${baseId}_${index}`;
            input.value = '';
            if (index > 0) {
                // Remove name attribute from additional selections
                input.removeAttribute('name');
            }
        });
        
        // Update the title
        const titleEl = template.querySelector('.selection-title');
        if (titleEl) {
            titleEl.textContent = `Selection ${index + 1}`;
        }
        
        // Add icon to odds field
        const oddsField = template.querySelector(`#odds_${index}`);
        if (oddsField && oddsField.parentElement && !oddsField.parentElement.querySelector('.input-icon')) {
            const iconDiv = document.createElement('div');
            iconDiv.className = 'input-with-icon';
            const icon = document.createElement('i');
            icon.className = 'fas fa-percentage input-icon';
            
            // Replace the input with the input-with-icon structure
            const parent = oddsField.parentElement;
            iconDiv.appendChild(icon);
            parent.replaceChild(iconDiv, oddsField);
            iconDiv.appendChild(oddsField);
        }
        
        // Add datalist to racecourse if available
        const racecourseField = template.querySelector(`#racecourse_${index}`);
        if (racecourseField && document.getElementById('racecourseList')) {
            racecourseField.setAttribute('list', 'racecourseList');
        }
        
        return template;
    }
    
    // Helper function to add additional selections to form
    function addAdditionalSelectionsToForm() {
        if (!selectionsContainer || !bettingForm) return;
        
        const selections = selectionsContainer.querySelectorAll('.selection-box');
        
        if (selections.length <= 1) return;
        
        const additionalSelections = [];
        
        for (let i = 1; i < selections.length; i++) {
            const selection = selections[i];
            const selectionField = selection.querySelector(`#selection_${i}`);
            const racecourseField = selection.querySelector(`#racecourse_${i}`);
            const jockeyField = selection.querySelector(`#jockey_${i}`);
            const trainerField = selection.querySelector(`#trainer_${i}`);
            const oddsField = selection.querySelector(`#odds_${i}`);
            
            if (selectionField && racecourseField && oddsField) {
                additionalSelections.push({
                    selection: selectionField.value,
                    racecourse: racecourseField.value,
                    jockey: jockeyField ? jockeyField.value || '' : '',
                    trainer: trainerField ? trainerField.value || '' : '',
                    odds: oddsField.value
                });
            }
        }
        
        // Only add the field if we have additional selections
        if (additionalSelections.length > 0) {
            // Add to hidden field
            let hiddenField = document.getElementById('additional_selections_data');
            if (!hiddenField) {
                hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = 'additional_selections';
                hiddenField.id = 'additional_selections_data';
                bettingForm.appendChild(hiddenField);
            }
            hiddenField.value = JSON.stringify(additionalSelections);
        }
    }
    
    // Expose functions to global scope
    window.validateForm = validateForm;
    window.updateSelections = updateSelections;
    window.handleBetTypeChange = handleBetTypeChange;
    window.createSelectionBox = createSelectionBox;
    window.addAdditionalSelectionsToForm = addAdditionalSelectionsToForm;
});