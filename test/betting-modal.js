document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('bettingModal');
    const closeBtn = document.querySelector('.betting-modal-close');
    const cancelBtn = document.getElementById('cancelButton');
    const betTypeSelect = document.getElementById('bet_type');
    const selectionsCountGroup = document.getElementById('selectionsCountGroup');
    const numberOfSelectionsSelect = document.getElementById('numberOfSelections');
    const selectionsContainer = document.getElementById('selectionsContainer');
    const stakeInput = document.getElementById('stake');
    const oddsInputs = document.querySelectorAll('[id^="odds_"]');
    const potentialReturnsEl = document.getElementById('potentialReturns');
    const potentialProfitEl = document.getElementById('potentialProfit');
    const openModalBtn = document.getElementById('openModalBtn');
    const outcomeSelect = document.getElementById('outcome');
    
    // Add button click handler
    openModalBtn.addEventListener('click', function() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Prevent scrolling behind modal
    });
    
    // Close button click handler
    closeBtn.onclick = function() {
        closeModal();
    };
    
    // Cancel button click handler
    cancelBtn.onclick = function() {
        closeModal();
    };
    
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
    
    // Handle bet type change
    betTypeSelect.addEventListener('change', function() {
        if (this.value === 'Accumulator' || this.value === 'Cover/Insure') {
            selectionsCountGroup.style.display = 'block';
            updateSelections();
        } else {
            selectionsCountGroup.style.display = 'none';
            // Reset to single selection
            while (selectionsContainer.children.length > 1) {
                selectionsContainer.removeChild(selectionsContainer.lastChild);
            }
            document.querySelector('.selection-title').textContent = 'Selection Details';
        }
        calculateReturns(); // Recalculate returns when bet type changes
    });
    
    // Handle number of selections change
    numberOfSelectionsSelect.addEventListener('change', updateSelections);
    
    // Handle outcome change
    outcomeSelect.addEventListener('change', function() {
        calculateReturns();
        
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
    
    // Initialize outcome badges
    const badges = document.querySelectorAll('.outcome-badges .badge');
    badges.forEach(badge => {
        if (badge.classList.contains('badge-pending')) {
            badge.style.opacity = '1';
        } else {
            badge.style.opacity = '0.3';
        }
    });
    
    function updateSelections() {
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
            selection.querySelector('.selection-title').textContent = `Selection ${index + 1}`;
        });
        
        // Add event listeners to new odds inputs
        document.querySelectorAll('[id^="odds_"]').forEach(input => {
            input.addEventListener('input', calculateReturns);
        });
        
        calculateReturns(); // Recalculate returns when selections change
    }
    
    function createSelectionBox(index) {
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
        template.querySelector('.selection-title').textContent = `Selection ${index + 1}`;
        
        // Add icon to odds field
        const oddsField = template.querySelector(`#odds_${index}`);
        if (oddsField && !oddsField.parentElement.querySelector('.input-icon')) {
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
        
        return template;
    }
    
    // Calculate potential returns
    function calculateReturns() {
        const stake = parseFloat(stakeInput.value) || 0;
        const betType = betTypeSelect.value;
        const outcome = outcomeSelect.value;
        
        // Update returns/profit display colors based on outcome
        if (outcome === 'Lost') {
            potentialReturnsEl.style.color = '#f44336';
            potentialProfitEl.style.color = '#f44336';
            
            potentialReturnsEl.textContent = '£0.00';
            potentialProfitEl.textContent = '-£' + stake.toFixed(2);
            return;
        } else {
            potentialReturnsEl.style.color = '#4caf50';
            potentialProfitEl.style.color = '#4caf50';
        }
        
        let totalOdds = 1;
        let validOddsCount = 0;
        
        // Get all odds inputs
        const oddsInputs = document.querySelectorAll('[id^="odds_"]');
        
        // For Cover/Insure bets, we need the odds of all selections individually
        if (betType === 'Cover/Insure') {
            let totalReturn = 0;
            
            oddsInputs.forEach((input) => {
                const odds = input.value.trim();
                if (odds) {
                    let selectionOdds = 0;
                    
                    if (odds.includes('/')) {
                        const [numerator, denominator] = odds.split('/');
                        selectionOdds = (parseFloat(numerator) / parseFloat(denominator)) + 1;
                    } else {
                        selectionOdds = parseFloat(odds);
                    }
                    
                    // Each selection gets an equal portion of the stake
                    const selectionStake = stake / oddsInputs.length;
                    totalReturn += selectionStake * selectionOdds;
                    validOddsCount++;
                }
            });
            
            if (validOddsCount > 0 && stake > 0) {
                potentialReturnsEl.textContent = `£${totalReturn.toFixed(2)}`;
                potentialProfitEl.textContent = `£${(totalReturn - stake).toFixed(2)}`;
            } else {
                potentialReturnsEl.textContent = '£0.00';
                potentialProfitEl.textContent = '£0.00';
            }
        } else {
            // For other bet types
            oddsInputs.forEach(input => {
                const odds = input.value.trim();
                if (odds) {
                    if (odds.includes('/')) {
                        const [numerator, denominator] = odds.split('/');
                        totalOdds *= (parseFloat(numerator) / parseFloat(denominator)) + 1;
                    } else {
                        totalOdds *= parseFloat(odds);
                    }
                    validOddsCount++;
                }
            });
            
            const isBetValid = (betType !== 'Accumulator' && validOddsCount >= 1) || 
                            (betType === 'Accumulator' && validOddsCount >= parseInt(numberOfSelectionsSelect.value));
            
            if (stake > 0 && isBetValid) {
                const returns = stake * totalOdds;
                potentialReturnsEl.textContent = `£${returns.toFixed(2)}`;
                potentialProfitEl.textContent = `£${(returns - stake).toFixed(2)}`;
            } else {
                potentialReturnsEl.textContent = '£0.00';
                potentialProfitEl.textContent = '£0.00';
            }
        }
    }
    
    // Add event listeners for return calculation
    stakeInput.addEventListener('input', calculateReturns);
    oddsInputs.forEach(input => {
        input.addEventListener('input', calculateReturns);
    });
    
    // Form submission handler for testing
    const bettingForm = document.getElementById('bettingForm');
    bettingForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent actual form submission for this test
        
        // Validate form
        if (!validateForm()) {
            return false;
        }
        
        // Update returns to 0 if outcome is "Lost"
        if (outcomeSelect.value === 'Lost') {
            const returnsHiddenField = document.createElement('input');
            returnsHiddenField.type = 'hidden';
            returnsHiddenField.name = 'returns';
            returnsHiddenField.value = '0';
            this.appendChild(returnsHiddenField);
        }
        
        // Collect form data
        const formData = new FormData(bettingForm);
        let formValues = {};
        
        for (let [key, value] of formData.entries()) {
            formValues[key] = value;
        }
        
        // Add multiple selections data if applicable
        if (betTypeSelect.value === 'Accumulator' || betTypeSelect.value === 'Cover/Insure') {
                    formValues.additional_selections = [];
                    const selections = selectionsContainer.querySelectorAll('.selection-box');
                    
                    for (let i = 1; i < selections.length; i++) {
                        const selection = selections[i];
                        formValues.additional_selections.push({
                            selection: selection.querySelector(`#selection_${i}`).value,
                            racecourse: selection.querySelector(`#racecourse_${i}`).value,
                            jockey: selection.querySelector(`#jockey_${i}`).value,
                            trainer: selection.querySelector(`#trainer_${i}`).value,
                            odds: selection.querySelector(`#odds_${i}`).value
                        });
                    }
                }
                
                // Display the form data (for testing purposes)
                const formResults = document.getElementById('formResults');
                const resultsContent = document.getElementById('resultsContent');
                
                resultsContent.textContent = JSON.stringify(formValues, null, 2);
                formResults.style.display = 'block';
                
                // Show success animation
                formResults.scrollIntoView({ behavior: 'smooth' });
                
                // Close the modal
                closeModal();
            });
            
            // Form validation
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
                if ((betTypeSelect.value === 'Accumulator' || betTypeSelect.value === 'Cover/Insure') && isValid) {
                    const selections = selectionsContainer.querySelectorAll('.selection-box');
                    
                    for (let i = 1; i < selections.length; i++) {
                        const selection = selections[i];
                        const selectionField = selection.querySelector(`#selection_${i}`);
                        const racecourseField = selection.querySelector(`#racecourse_${i}`);
                        const oddsField = selection.querySelector(`#odds_${i}`);
                        
                        // Check required fields for each selection
                        [selectionField, racecourseField, oddsField].forEach(field => {
                            if (!field.value.trim()) {
                                field.style.borderColor = '#f44336';
                                isValid = false;
                                
                                // Add shake animation
                                field.classList.add('shake');
                                setTimeout(() => {
                                    field.classList.remove('shake');
                                }, 500);
                                
                                // Scroll to invalid field
                                field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
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
            
            // Clear field error on input
            bettingForm.addEventListener('input', function(e) {
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'SELECT') {
                    e.target.style.borderColor = '';
                }
            });
            
            // Add shake animation style
            const styleSheet = document.createElement('style');
            styleSheet.textContent = `
                @keyframes shake {
                    0%, 100% { transform: translateX(0); }
                    10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
                    20%, 40%, 60%, 80% { transform: translateX(5px); }
                }
                .shake {
                    animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
                }
            `;
            document.head.appendChild(styleSheet);
        });