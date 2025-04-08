// Betting System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const betForm = document.getElementById('betForm');
    if (betForm) {
        betForm.addEventListener('submit', validateForm);
    }
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Format odds input
    const oddsInput = document.getElementById('odds');
    if (oddsInput) {
        oddsInput.addEventListener('blur', function() {
            formatOdds(this);
        });
    }
    
    // Confirm delete
    const deleteButtons = document.querySelectorAll('.delete-bet');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this bet record?')) {
                e.preventDefault();
            }
        });
    });
    
    // Highlight newly added row
    if (location.hash === '#new-record') {
        const firstRow = document.querySelector('tbody tr:first-child');
        if (firstRow) {
            firstRow.classList.add('bg-light');
            setTimeout(() => {
                firstRow.classList.remove('bg-light');
                // Remove the hash without refreshing
                history.replaceState(null, document.title, location.pathname + location.search);
            }, 3000);
        }
    }
    
    // Calculate potential returns
    const stakeInput = document.getElementById('stake');
    if (stakeInput) {
        stakeInput.addEventListener('input', calculateReturns);
        oddsInput.addEventListener('input', calculateReturns);
    }
    
    // Date picker initialization (if using flatpickr)
    if (typeof flatpickr !== 'undefined') {
        flatpickr(".date-picker", {
            dateFormat: "Y-m-d",
            maxDate: "today"
        });
    }
    
    // Search functionality
    const searchInput = document.getElementById('searchBets');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            searchBets(this.value);
        });
    }
    
    // Filter by outcome
    const outcomeFilters = document.querySelectorAll('.filter-outcome');
    outcomeFilters.forEach(filter => {
        filter.addEventListener('click', function() {
            const outcome = this.getAttribute('data-outcome');
            filterByOutcome(outcome);
            
            // Update active state
            outcomeFilters.forEach(f => f.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

/**
 * Form validation function
 */
function validateForm(e) {
    const form = e.target;
    
    // Check if form is valid
    if (!form.checkValidity()) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    form.classList.add('was-validated');
    
    // Additional custom validation
    const odds = document.getElementById('odds').value;
    const oddsPattern = /^\d+\/\d+$|^\d+\.\d+$|^\d+$/;
    
    if (!oddsPattern.test(odds)) {
        e.preventDefault();
        const oddsInput = document.getElementById('odds');
        oddsInput.setCustomValidity('Please enter odds in format like "5/1" or "6.0"');
        oddsInput.reportValidity();
    }
}

/**
 * Format odds input to standard format
 */
function formatOdds(input) {
    let value = input.value.trim();
    
    // If decimal format (e.g. 6.0), convert to fraction
    if (/^\d+\.\d+$/.test(value)) {
        const decimal = parseFloat(value);
        // Convert decimal to fraction
        if (decimal > 1) {
            const numerator = Math.round((decimal - 1) * 100);
            const denominator = 100;
            // Simplify fraction
            const gcd = findGCD(numerator, denominator);
            input.value = `${numerator/gcd}/${denominator/gcd}`;
        }
    }
    
    // Make sure there's a slash for fraction format
    if (/^\d+$/.test(value)) {
        input.value = `${value}/1`;
    }
}

/**
 * Calculate greatest common divisor for fraction simplification
 */
function findGCD(a, b) {
    if (b === 0) return a;
    return findGCD(b, a % b);
}

/**
 * Calculate potential returns based on stake and odds
 */
function calculateReturns() {
    const stake = parseFloat(document.getElementById('stake').value) || 0;
    const oddsValue = document.getElementById('odds').value;
    const returnsDisplay = document.getElementById('potentialReturns');
    
    if (!returnsDisplay || !stake || !oddsValue) return;
    
    let returns = 0;
    
    // Handle fraction odds (e.g. 5/1)
    if (oddsValue.includes('/')) {
        const [numerator, denominator] = oddsValue.split('/').map(Number);
        if (denominator > 0) {
            returns = stake + (stake * numerator / denominator);
        }
    } 
    // Handle decimal odds (e.g. 6.0)
    else if (!isNaN(parseFloat(oddsValue))) {
        returns = stake * parseFloat(oddsValue);
    }
    
    returnsDisplay.textContent = returns.toFixed(2);
}

/**
 * Search bets in the table
 */
function searchBets(query) {
    query = query.toLowerCase();
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(query) ? '' : 'none';
    });
    
    // Show message if no results
    const noResults = document.getElementById('noResults');
    const visibleRows = document.querySelectorAll('tbody tr[style=""]').length;
    
    if (noResults) {
        noResults.style.display = visibleRows === 0 ? 'block' : 'none';
    }
}

/**
 * Filter bets by outcome
 */
function filterByOutcome(outcome) {
    const rows = document.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
        if (outcome === 'all') {
            row.style.display = '';
            return;
        }
        
        const rowOutcome = row.querySelector('td:nth-child(9)').textContent.trim().toLowerCase();
        row.style.display = rowOutcome === outcome.toLowerCase() ? '' : 'none';
    });
}

/**
 * Toggle stats view
 */
function toggleStats() {
    const statsContainer = document.getElementById('statsContainer');
    if (statsContainer) {
        statsContainer.classList.toggle('d-none');
    }
}

/**
 * Update UI for edit mode
 */
function toggleEditMode(id) {
    const viewRow = document.getElementById(`bet-row-${id}`);
    const editRow = document.getElementById(`bet-edit-row-${id}`);
    
    if (viewRow && editRow) {
        viewRow.classList.toggle('d-none');
        editRow.classList.toggle('d-none');
    }
}

/**
 * Cancel edit and revert to view mode
 */
function cancelEdit(id) {
    toggleEditMode(id);
}

/**
 * Export table to CSV
 */
function exportToCSV() {
    const table = document.querySelector('table');
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    for (let i = 0; i < rows.length; i++) {
        const row = [], cols = rows[i].querySelectorAll('td, th');
        
        for (let j = 0; j < cols.length; j++) {
            // Get text content and clean it
            let data = cols[j].textContent.replace(/(\r\n|\n|\r)/gm, '').trim();
            // Quote fields with commas
            data = data.includes(',') ? `"${data}"` : data;
            row.push(data);
        }
        
        csv.push(row.join(','));
    }
    
    // Create CSV file and download
    const csvContent = `data:text/csv;charset=utf-8,${csv.join('\n')}`;
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'bet_records.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Add this to your existing bet-record.js file

document.addEventListener('DOMContentLoaded', function() {
    // Handle racecourse "Other" option
    const racecourseSelect = document.getElementById('racecourse');
    const newRacecourseContainer = document.getElementById('newRacecourseContainer');
    const newRacecourseInput = document.getElementById('newRacecourse');
    
    if (racecourseSelect) {
        racecourseSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                newRacecourseContainer.classList.remove('d-none');
                newRacecourseInput.setAttribute('required', 'required');
            } else {
                newRacecourseContainer.classList.add('d-none');
                newRacecourseInput.removeAttribute('required');
            }
        });
    }
    
    // Update form submission to use the custom racecourse when "Other" is selected
    const betForm = document.getElementById('betForm');
    if (betForm) {
        betForm.addEventListener('submit', function(e) {
            if (racecourseSelect.value === 'other' && newRacecourseInput.value.trim()) {
                // Create a hidden input with the new racecourse value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'racecourse';
                hiddenInput.value = newRacecourseInput.value.trim();
                
                // Create another hidden input to flag this as a new racecourse
                const flagInput = document.createElement('input');
                flagInput.type = 'hidden';
                flagInput.name = 'new_racecourse';
                flagInput.value = '1';
                
                // Replace the select element's name temporarily
                racecourseSelect.name = 'original_racecourse';
                
                // Append the hidden inputs to the form
                this.appendChild(hiddenInput);
                this.appendChild(flagInput);
            }
        });
    }
});