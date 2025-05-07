/**
 * filters.js - Handles search and filtering functionality
 * Responsible for filtering bet records based on search terms and criteria
 */
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchBets = document.getElementById('searchBets');
    if (searchBets) {
        searchBets.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Check if we're in desktop or mobile view
            const rows = document.querySelectorAll('tbody tr');
            const cards = document.querySelectorAll('.bet-card');
            
            let matchCount = 0;
            
            // Filter table rows (desktop view)
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Filter cards (mobile view)
            cards.forEach(card => {
                const text = card.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    card.style.display = '';
                    matchCount++;
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Show/hide no results message
            const noResults = document.getElementById('noResults');
            if (noResults) {
                if ((rows.length > 0 || cards.length > 0) && matchCount === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                }
            }
        });
    }
    
    // Filter by outcome
    const filterButtons = document.querySelectorAll('.filter-outcome');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Add active class to clicked button
            this.classList.add('active');
            
            const outcome = this.getAttribute('data-outcome');
            
            // Filter both desktop and mobile views
            filterByOutcome(outcome);
        });
    });
    
    // Filter function that works for both desktop and mobile views
    function filterByOutcome(outcome) {
        // Filter table rows (desktop view)
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            if (outcome === 'all') {
                row.style.display = '';
            } else {
                const rowOutcome = row.querySelector('.badge');
                if (rowOutcome && rowOutcome.textContent.toLowerCase() === outcome) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
        
        // Filter cards (mobile view)
        const cards = document.querySelectorAll('.bet-card');
        cards.forEach(card => {
            if (outcome === 'all') {
                card.style.display = '';
            } else {
                // Cards have data-outcome attribute
                const cardOutcome = card.getAttribute('data-outcome');
                if (cardOutcome === outcome) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            }
        });
    }
    
    // Date filter functionality
    const dateFilter = document.getElementById('dateFilter');
    if (dateFilter) {
        dateFilter.addEventListener('change', function() {
            const timeframe = this.value;
            
            if (timeframe === 'all') {
                // Show all rows and cards
                document.querySelectorAll('tbody tr, .bet-card').forEach(el => {
                    el.style.display = '';
                });
                return;
            }
            
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            // Filter table rows (desktop view)
            filterByDate(timeframe, today, 'tbody tr', 'td:first-child');
            
            // Filter cards (mobile view)
            filterByDate(timeframe, today, '.bet-card', '.bet-date');
        });
    }
    
    // Helper function to filter by date
    function filterByDate(timeframe, today, elementSelector, dateSelector) {
        const elements = document.querySelectorAll(elementSelector);
        
        elements.forEach(element => {
            const dateEl = element.querySelector(dateSelector);
            if (!dateEl) return;
            
            const dateText = dateEl.textContent.trim();
            if (!dateText) return;
            
            // Parse date (format: dd/mm/yyyy)
            const [day, month, year] = dateText.split('/');
            const elementDate = new Date(`${year}-${month}-${day}`);
            
            let show = false;
            
            switch(timeframe) {
                case 'today':
                    show = elementDate.toDateString() === today.toDateString();
                    break;
                case 'week':
                    const weekStart = new Date(today);
                    weekStart.setDate(today.getDate() - today.getDay()); // Start of week (Sunday)
                    show = elementDate >= weekStart;
                    break;
                case 'month':
                    show = elementDate.getMonth() === today.getMonth() && 
                           elementDate.getFullYear() === today.getFullYear();
                    break;
                case 'year':
                    show = elementDate.getFullYear() === today.getFullYear();
                    break;
                default:
                    show = true;
            }
            
            element.style.display = show ? '' : 'none';
        });
    }
    
    // Initialize date filter if available
    if (dateFilter) {
        dateFilter.value = 'all';
    }
    
    // Expose functions to global scope
    window.filterByOutcome = filterByOutcome;
    window.filterByDate = filterByDate;
});