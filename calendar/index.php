<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Calendar 2025</title>

    <link rel="stylesheet" href="calendar.css">
    <style>
        
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Horse Racing Calendar 2025</h1>
            <p>View upcoming races with enhanced visualization</p>
        </div>
        <div class="stats-container" id="stats-container">
            <div class="stat-item">
                <span>Total Races:</span>
                <span class="stat-value" id="total-races">0</span>
            </div>
            <div class="stat-item">
                <span>Unique Racecourses:</span>
                <span class="stat-value" id="unique-racecourses">0</span>
            </div>
            <div class="stat-item">
                <span>Next Race:</span>
                <span class="stat-value" id="next-race">-</span>
            </div>
        </div>
    </header>

    <div class="search-container">
        <span class="search-icon">🔍</span>
        <input type="text" id="searchInput" class="search-input" placeholder="Search for races, racecourses, dates...">
        <div id="searchResults" class="search-results" style="display: none;"></div>
    </div>

    <div class="tabs">
        <div class="tab active" data-tab="list">List View</div>
        <div class="tab" data-tab="calendar">Calendar View</div>
        <div class="tab" data-tab="stats">Statistics</div>
    </div>

    <div class="tab-content active" id="list-tab">
        <div class="controls">
            <div class="filter-group">
                <label for="dateFilter">Filter by Date:</label>
                <input type="date" id="dateFilter">
            </div>
            
            <div class="filter-group">
                <label for="monthFilter">Filter by Month:</label>
                <select id="monthFilter">
                    <option value="">All Months</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="racecourseFilter">Filter by Racecourse:</label>
                <select id="racecourseFilter">
                    <option value="">All Racecourses</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="surfaceFilter">Filter by Surface:</label>
                <select id="surfaceFilter">
                    <option value="">All Surfaces</option>
                    <option value="Turf">Turf</option>
                    <option value="AW">All-Weather</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="raceTypeFilter">Filter by Race Type:</label>
                <select id="raceTypeFilter">
                    <option value="">All Types</option>
                    <option value="Flat">Flat</option>
                    <option value="Jumps">Jumps</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="timeOfDayFilter">Filter by Time:</label>
                <select id="timeOfDayFilter">
                    <option value="">All Times</option>
                    <option value="Afternoon">Afternoon</option>
                    <option value="Evening">Evening</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="countryFilter">Filter by Country:</label>
                <select id="countryFilter">
                    <option value="">All Countries</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label>&nbsp;</label>
                <button id="resetFilters">Reset Filters</button>
            </div>
        </div>

        <div class="export-buttons">
            <button id="exportCSV" class="export-btn">Export Filtered Data (CSV)</button>
            <button id="printView" class="export-btn">Print View</button>
        </div>

        <div class="legend">
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--turf-color);"></span>
                <span>Turf</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--aw-color);"></span>
                <span>All-Weather</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--jumps-color);"></span>
                <span>Jumps</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--flat-color);"></span>
                <span>Flat</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--afternoon-color);"></span>
                <span>Afternoon</span>
            </div>
            <div class="legend-item">
                <span class="legend-color" style="background-color: var(--evening-color);"></span>
                <span>Evening</span>
            </div>
        </div>

        <div id="calendar-container" class="calendar-container">
            <!-- Calendar data will be rendered here -->
        </div>
    </div>

    <div class="tab-content" id="calendar-tab">
        <div class="controls">
            <div class="filter-group">
                <label for="calendarMonthSelector">Month:</label>
                <select id="calendarMonthSelector">
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="calendarYearSelector">Year:</label>
                <select id="calendarYearSelector">
                    <option value="2025">2025</option>
                </select>
            </div>
        </div>
        
        <div class="calendar-container">
            <div class="month-header" id="current-month-display">January 2025</div>
            <div class="month-grid" id="month-grid">
                <!-- Calendar grid will be generated here -->
            </div>
        </div>
    </div>

    <div class="tab-content" id="stats-tab">
        <div class="chart-container">
            <canvas id="racesByMonthChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="racesByTypeChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="topRacecoursesChart"></canvas>
        </div>
    </div>

    <div id="tooltip" class="tooltip"></div>
    <div id="loading" class="loading" style="display: none;">
        <div class="spinner"></div>
    </div>
    
    <div class="data-info">
        <p><strong>Note:</strong> This page displays a sample of races for demonstration purposes. To add more races, you can modify the <code>racingCalendarData</code> JavaScript object.</p>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>

    // Global variables
let racingCalendarData = {}; // Initialize as empty object
let racingData = []; // Start with empty array
let processedData = [];
let filteredData = [];
let racecourseOptions = new Set();
let countryOptions = new Set();
const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

// Charts
let racesByMonthChart = null;
let racesByTypeChart = null;
let topRacecoursesChart = null;

// DOM elements
const calendarContainer = document.getElementById('calendar-container');
const dateFilter = document.getElementById('dateFilter');
const monthFilter = document.getElementById('monthFilter');
const racecourseFilter = document.getElementById('racecourseFilter');
const surfaceFilter = document.getElementById('surfaceFilter');
const raceTypeFilter = document.getElementById('raceTypeFilter');
const timeOfDayFilter = document.getElementById('timeOfDayFilter');
const countryFilter = document.getElementById('countryFilter');
const resetFiltersButton = document.getElementById('resetFilters');
const exportCSVButton = document.getElementById('exportCSV');
const printViewButton = document.getElementById('printView');
const calendarMonthSelector = document.getElementById('calendarMonthSelector');
const calendarYearSelector = document.getElementById('calendarYearSelector');
const currentMonthDisplay = document.getElementById('current-month-display');
const monthGrid = document.getElementById('month-grid');
const tabs = document.querySelectorAll('.tab');
const tabContents = document.querySelectorAll('.tab-content');
const searchInput = document.getElementById('searchInput');
const searchResults = document.getElementById('searchResults');
const tooltip = document.getElementById('tooltip');
const loading = document.getElementById('loading');
const statsContainer = document.getElementById('stats-container');
const totalRacesElement = document.getElementById('total-races');
const uniqueRacecoursesElement = document.getElementById('unique-racecourses');
const nextRaceElement = document.getElementById('next-race');

// Function to load CSV data
function loadCSVData() {
    showLoading();
    
    // Use fetch to get the CSV file
    fetch('racing_calendar.csv')
        .then(response => response.text())
        .then(csvData => {
            // Parse CSV data
            const lines = csvData.trim().split('\n');
            const headers = lines[0].split(',').map(header => header.trim());
            
            // Convert CSV to array of objects
            racingData = lines.slice(1).map(line => {
                const values = line.split(',').map(value => value.trim());
                const entry = {};
                headers.forEach((header, index) => {
                    entry[header] = values[index] || '';
                });
                return entry;
            });
            
            // Group by month for easier filtering
            racingCalendarData = {};
            racingData.forEach(race => {
                const month = race.Date.split('-')[1];
                if (!racingCalendarData[month]) {
                    racingCalendarData[month] = [];
                }
                racingCalendarData[month].push(race);
            });
            
            // Set processed data
            processedData = racingData;
            
            // Initialize the app with the loaded data
            setupFilterOptions();
            applyFilters();
            updateStats();
            updateCalendarView();
            
            hideLoading();
        })
        .catch(error => {
            console.error('Error loading CSV:', error);
            hideLoading();
            alert('Failed to load racing calendar data. Please try again later.');
        });
}

// Add event listeners
document.addEventListener('DOMContentLoaded', initApp);
dateFilter.addEventListener('change', applyFilters);
monthFilter.addEventListener('change', applyFilters);
racecourseFilter.addEventListener('change', applyFilters);
surfaceFilter.addEventListener('change', applyFilters);
raceTypeFilter.addEventListener('change', applyFilters);
timeOfDayFilter.addEventListener('change', applyFilters);
countryFilter.addEventListener('change', applyFilters);
resetFiltersButton.addEventListener('click', resetFilters);
exportCSVButton.addEventListener('click', exportFilteredData);
printViewButton.addEventListener('click', printCurrentView);
calendarMonthSelector.addEventListener('change', updateCalendarView);
calendarYearSelector.addEventListener('change', updateCalendarView);
searchInput.addEventListener('input', handleSearch);
searchInput.addEventListener('focus', function() {
    if (searchResults.children.length > 0) {
        searchResults.style.display = 'block';
    }
});
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
        searchResults.style.display = 'none';
    }
});

// Tab switching
tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const tabId = tab.getAttribute('data-tab');
        
        // Remove active class from all tabs and contents
        tabs.forEach(t => t.classList.remove('active'));
        tabContents.forEach(c => c.classList.remove('active'));
        
        // Add active class to current tab and content
        tab.classList.add('active');
        document.getElementById(`${tabId}-tab`).classList.add('active');
        
        // If switching to stats tab, initialize or update charts
        if (tabId === 'stats' && processedData.length > 0) {
            initCharts();
        }
        
        // If switching to calendar tab, update the calendar view
        if (tabId === 'calendar' && processedData.length > 0) {
            updateCalendarView();
        }
    });
});

// Initialize the application
function initApp() {
    // Set current month in calendar selector
    const currentDate = new Date();
    calendarMonthSelector.value = currentDate.getMonth() + 1;
    
    // Load the CSV data
    loadCSVData();
}

// Setup filter options based on available data
function setupFilterOptions() {
    // Get unique values for racecourses
    racecourseOptions = new Set();
    countryOptions = new Set();
    
    processedData.forEach(race => {
        if (race.Racecourse) {
            racecourseOptions.add(race.Racecourse);
        }
        if (race.Country) {
            countryOptions.add(race.Country);
        }
    });
    
    // Clear existing options except the first one
    while (racecourseFilter.options.length > 1) {
        racecourseFilter.remove(1);
    }
    
    while (countryFilter.options.length > 1) {
        countryFilter.remove(1);
    }
    
    // Add racecourse options
    Array.from(racecourseOptions).sort().forEach(racecourse => {
        const option = document.createElement('option');
        option.value = racecourse;
        option.textContent = racecourse;
        racecourseFilter.appendChild(option);
    });
    
    // Add country options
    Array.from(countryOptions).sort().forEach(country => {
        const option = document.createElement('option');
        option.value = country;
        option.textContent = country;
        countryFilter.appendChild(option);
    });
}

// Apply filters to data
function applyFilters() {
    showLoading();
    
    const dateValue = dateFilter.value;
    const monthValue = monthFilter.value;
    const racecourseValue = racecourseFilter.value;
    const surfaceValue = surfaceFilter.value;
    const raceTypeValue = raceTypeFilter.value;
    const timeValue = timeOfDayFilter.value;
    const countryValue = countryFilter.value;

    filteredData = processedData.filter(race => {
        if (dateValue && race.Date !== dateValue) return false;
        if (monthValue && !race.Date.startsWith(`2025-${monthValue}`)) return false;
        if (racecourseValue && race.Racecourse !== racecourseValue) return false;
        if (surfaceValue && race.Surface !== surfaceValue) return false;
        if (raceTypeValue && race["Race Type"] !== raceTypeValue) return false;
        if (timeValue && race["Time of Day"] !== timeValue) return false;
        if (countryValue && race.Country !== countryValue) return false;
        return true;
    });

    renderCalendar();
    updateStats();
    
    hideLoading();
}

// Reset all filters
function resetFilters() {
    dateFilter.value = '';
    monthFilter.value = '';
    racecourseFilter.value = '';
    surfaceFilter.value = '';
    raceTypeFilter.value = '';
    timeOfDayFilter.value = '';
    countryFilter.value = '';
    applyFilters();
}

// Render calendar with filtered data (List View)
function renderCalendar() {
    if (filteredData.length === 0) {
        calendarContainer.innerHTML = `
            <div class="empty-state">
                <h3>No races found</h3>
                <p>Try adjusting your filters</p>
            </div>
        `;
        return;
    }

    // Group by date
    const groupedByDate = {};
    filteredData.forEach(race => {
        if (!groupedByDate[race.Date]) {
            groupedByDate[race.Date] = [];
        }
        groupedByDate[race.Date].push(race);
    });

    // Sort dates
    const sortedDates = Object.keys(groupedByDate).sort();

    let html = '';
    sortedDates.forEach(date => {
        const formattedDate = formatDate(date);
        html += `
            <div class="date-group">
                <div class="date-header">${formattedDate}</div>
                <ul class="race-list">
        `;

        groupedByDate[date].forEach(race => {
            // Add color coding based on race properties
            const surfaceClass = race.Surface === 'Turf' ? 'turf-color' : 'aw-color';
            const raceTypeClass = race["Race Type"] === 'Jumps' ? 'jumps-color' : 'flat-color';
            const timeClass = race["Time of Day"] === 'Afternoon' ? 'afternoon' : 'evening';
            
            html += `
    <li class="race-item" 
        data-racecourse="${race.Racecourse}"
        data-date="${race.Date}"
        data-surface="${race.Surface}"
        data-type="${race["Race Type"]}"
        data-time="${race["Time of Day"]}"
        data-country="${race.Country}">
        <div class="racecourse">${race.Racecourse}</div>
        <div class="race-details">
            <span class="detail time-of-day ${timeClass}">${race["Time of Day"]}</span>
            <span class="detail surface" style="background-color: var(--${surfaceClass});">${race.Surface}</span>
            <span class="detail race-type" style="background-color: var(--${raceTypeClass});">${race["Race Type"]}</span>
            <span class="detail country">${race.Country}</span>
        </div>
    </li>
`;
        });

        html += `
                </ul>
            </div>
        `;
    });

    calendarContainer.innerHTML = html;
    
    // Add tooltip event listeners
    document.querySelectorAll('.race-item').forEach(item => {
        item.addEventListener('mouseenter', showTooltip);
        item.addEventListener('mouseleave', hideTooltip);
    });
}

// Format date for display
function formatDate(dateStr) {
    if (!dateStr) return '';
    
    const date = new Date(dateStr);
    if (isNaN(date.getTime())) return dateStr;
    
    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
    return date.toLocaleDateString('en-GB', options);
}

// Update calendar view (Month View)
function updateCalendarView() {
    const month = parseInt(calendarMonthSelector.value) - 1; // 0-based month
    const year = parseInt(calendarYearSelector.value);
    
    // Update month header
    currentMonthDisplay.textContent = `${monthNames[month]} ${year}`;
    
    // Generate calendar grid
    generateCalendarGrid(month, year);
}

// Generate calendar grid for a specific month
function generateCalendarGrid(month, year) {
    // Clear the grid
    monthGrid.innerHTML = '';
    
    // Add day names
    dayNames.forEach(day => {
        const dayNameDiv = document.createElement('div');
        dayNameDiv.className = 'day-name';
        dayNameDiv.textContent = day;
        monthGrid.appendChild(dayNameDiv);
    });
    
    // Get the first day of the month
    const firstDay = new Date(year, month, 1);
    const startingDay = firstDay.getDay(); // 0 = Sunday, 1 = Monday, etc.
    
    // Get the number of days in the month
    const lastDay = new Date(year, month + 1, 0);
    const totalDays = lastDay.getDate();
    
    // Get the previous month's last days to fill in the first week
    const prevMonthLastDay = new Date(year, month, 0).getDate();
    
    // Generate grid cells
    let dayCount = 1;
    let nextMonthDay = 1;
    
    // Calculate total number of cells needed (max 6 weeks)
    const totalCells = 42; // 6 weeks × 7 days
    
    for (let i = 0; i < totalCells; i++) {
        const dayDiv = document.createElement('div');
        dayDiv.className = 'grid-day';
        
        if (i < startingDay) {
            // Previous month days
            const prevDay = prevMonthLastDay - startingDay + i + 1;
            dayDiv.innerHTML = `<div class="date-number">${prevDay}</div>`;
            dayDiv.className += ' other-month';
            dayDiv.setAttribute('data-date', `${year}-${String(month).padStart(2, '0')}-${String(prevDay).padStart(2, '0')}`);
        } else if (dayCount <= totalDays) {
            // Current month days
            dayDiv.innerHTML = `<div class="date-number">${dayCount}</div>`;
            const currentDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(dayCount).padStart(2, '0')}`;
            dayDiv.setAttribute('data-date', currentDate);
            
            // Add events for this day
            const eventsForDay = processedData.filter(race => race.Date === currentDate);
            if (eventsForDay.length > 0) {
                const eventList = document.createElement('div');
                eventList.className = 'event-list';
                
                // Group by racecourse to avoid too many entries
                const racecourseCount = {};
                eventsForDay.forEach(race => {
                    if (!racecourseCount[race.Racecourse]) {
                        racecourseCount[race.Racecourse] = 1;
                    } else {
                        racecourseCount[race.Racecourse]++;
                    }
                });
                
                // Display racecourses (limit to 3 for space)
                let count = 0;
                for (const [racecourse, raceCount] of Object.entries(racecourseCount)) {
                    if (count < 3) {
                        const eventItem = document.createElement('div');
                        eventItem.className = 'event-item';
                        eventItem.textContent = racecourse;
                        eventItem.setAttribute('data-date', currentDate);
                        eventItem.setAttribute('data-racecourse', racecourse);
                        eventList.appendChild(eventItem);
                    }
                    count++;
                    if (count >= 3) break;
                }
                
                // Add "more" indicator if there are more racecourses
                if (Object.keys(racecourseCount).length > 3) {
                    const moreIndicator = document.createElement('div');
                    moreIndicator.className = 'event-item';
                    moreIndicator.textContent = `+${Object.keys(racecourseCount).length - 3} more`;
                    eventList.appendChild(moreIndicator);
                }
                
                dayDiv.appendChild(eventList);
            }
            
            dayCount++;
        } else {
            // Next month days
            dayDiv.innerHTML = `<div class="date-number">${nextMonthDay}</div>`;
            dayDiv.className += ' other-month';
            nextMonthDay++;
        }
        
        monthGrid.appendChild(dayDiv);
    }
    
    // Add event listeners to calendar events
    document.querySelectorAll('.event-item').forEach(item => {
        item.addEventListener('click', function() {
            const eventDate = this.getAttribute('data-date');
            const racecourse = this.getAttribute('data-racecourse');
            
            // Set filters to show this specific event
            dateFilter.value = eventDate;
            if (racecourse) {
                racecourseFilter.value = racecourse;
            }
            
            // Switch to list view and apply filters
            tabs[0].click(); // Switch to list tab
            applyFilters();
        });
    });
}

// Initialize or update charts for the Statistics tab
function initCharts() {
    // Destroy existing charts if they exist
    if (racesByMonthChart) racesByMonthChart.destroy();
    if (racesByTypeChart) racesByTypeChart.destroy();
    if (topRacecoursesChart) topRacecoursesChart.destroy();
    
    // Prepare data for charts
    const racesByMonth = countRacesByMonth();
    const racesByType = countRacesByType();
    const topRacecourses = getTopRacecourses(10);
    
    // Chart 1: Races by Month
    const ctx1 = document.getElementById('racesByMonthChart').getContext('2d');
    racesByMonthChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: monthNames,
            datasets: [{
                label: 'Number of Races',
                data: monthNames.map((_, index) => racesByMonth[index + 1] || 0),
                backgroundColor: 'rgba(46, 134, 193, 0.7)',
                borderColor: 'rgba(46, 134, 193, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Races by Month'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Races'
                    }
                }
            }
        }
    });
    
    // Chart 2: Races by Type
    const ctx2 = document.getElementById('racesByTypeChart').getContext('2d');
    racesByTypeChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: Object.keys(racesByType),
            datasets: [{
                data: Object.values(racesByType),
                backgroundColor: [
                    'rgba(155, 89, 182, 0.7)',
                    'rgba(52, 152, 219, 0.7)',
                    'rgba(46, 204, 113, 0.7)',
                    'rgba(231, 76, 60, 0.7)'
                ],
                borderColor: [
                    'rgba(155, 89, 182, 1)',
                    'rgba(52, 152, 219, 1)',
                    'rgba(46, 204, 113, 1)',
                    'rgba(231, 76, 60, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Races by Type and Surface'
                }
            }
        }
    });
    
    // Chart 3: Top Racecourses
    const ctx3 = document.getElementById('topRacecoursesChart').getContext('2d');
    topRacecoursesChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: topRacecourses.map(item => item.racecourse),
            datasets: [{
                label: 'Number of Races',
                data: topRacecourses.map(item => item.count),
                backgroundColor: 'rgba(243, 156, 18, 0.7)',
                borderColor: 'rgba(243, 156, 18, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Top 10 Racecourses by Number of Races'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Races'
                    }
                }
            }
        }
    });
}

// Count races by month
function countRacesByMonth() {
    const counts = {};
    processedData.forEach(race => {
        const month = parseInt(race.Date.split('-')[1]);
        counts[month] = (counts[month] || 0) + 1;
    });
    return counts;
}

// Count races by type and surface
function countRacesByType() {
    const counts = {};
    processedData.forEach(race => {
        const key = `${race["Race Type"]} (${race.Surface})`;
        counts[key] = (counts[key] || 0) + 1;
    });
    return counts;
}

// Get top racecourses by number of races
function getTopRacecourses(limit = 10) {
    const counts = {};
    processedData.forEach(race => {
        counts[race.Racecourse] = (counts[race.Racecourse] || 0) + 1;
    });
    
    return Object.entries(counts)
        .map(([racecourse, count]) => ({ racecourse, count }))
        .sort((a, b) => b.count - a.count)
        .slice(0, limit);
}

// Show tooltip for race item
function showTooltip(event) {
    const item = event.currentTarget;
    const rect = item.getBoundingClientRect();
    
    const racecourse = item.getAttribute('data-racecourse');
    const date = formatDate(item.getAttribute('data-date'));
    const surface = item.getAttribute('data-surface');
    const type = item.getAttribute('data-type');
    const time = item.getAttribute('data-time');
    const country = item.getAttribute('data-country');
    
    tooltip.innerHTML = `
        <div class="tooltip-racecourse">${racecourse}</div>
        <div class="tooltip-content">${date}</div>
        <div class="tooltip-detail">
            <span>Surface:</span>
            <span>${surface}</span>
        </div>
        <div class="tooltip-detail">
            <span>Race Type:</span>
            <span>${type}</span>
        </div>
        <div class="tooltip-detail">
            <span>Time:</span>
            <span>${time}</span>
        </div>
        <div class="tooltip-detail">
            <span>Country:</span>
            <span>${country}</span>
        </div>
    `;
    
    tooltip.style.left = `${rect.left + window.scrollX}px`;
    tooltip.style.top = `${rect.bottom + window.scrollY + 10}px`;
    tooltip.style.opacity = '1';
}

// Hide tooltip
function hideTooltip() {
    tooltip.style.opacity = '0';
}

// Handle search
function handleSearch() {
    const query = searchInput.value.trim().toLowerCase();
    if (query.length < 2) {
        searchResults.style.display = 'none';
        return;
    }
    
    // Search in processed data
    const results = processedData.filter(race => {
        return race.Racecourse.toLowerCase().includes(query) ||
               race.Date.includes(query) ||
               race["Race Type"].toLowerCase().includes(query) ||
               race.Surface.toLowerCase().includes(query) ||
               race.Country.toLowerCase().includes(query);
    }).slice(0, 10); // Limit to 10 results
    
    if (results.length === 0) {
        searchResults.style.display = 'none';
        return;
    }
    
    // Generate search results
    searchResults.innerHTML = '';
    results.forEach(race => {
        const item = document.createElement('div');
        item.className = 'search-item';
        
        // Highlight matching text
        let racecourseText = race.Racecourse;
        if (race.Racecourse.toLowerCase().includes(query)) {
            const index = race.Racecourse.toLowerCase().indexOf(query);
            racecourseText = 
                race.Racecourse.substring(0, index) +
                `<span class="search-highlight">${race.Racecourse.substring(index, index + query.length)}</span>` +
                race.Racecourse.substring(index + query.length);
        }
        
        item.innerHTML = `
            <strong>${racecourseText}</strong> - ${formatDate(race.Date)} 
            <div>${race["Race Type"]} (${race.Surface})</div>
        `;
        
        item.addEventListener('click', () => {
            // Set filters based on this result
            dateFilter.value = race.Date;
            racecourseFilter.value = race.Racecourse;
            
            // Apply filters and hide search results
            applyFilters();
            searchResults.style.display = 'none';
            searchInput.value = '';
        });
        
        searchResults.appendChild(item);
    });
    
    searchResults.style.display = 'block';
}

// Export filtered data as CSV
function exportFilteredData() {
    if (filteredData.length === 0) {
        alert('No data to export. Please adjust your filters.');
        return;
    }
    
    const headers = Object.keys(filteredData[0]);
    const csvContent = [
        headers.join(','),
        ...filteredData.map(row => headers.map(header => row[header]).join(','))
    ].join('\n');
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'racing_calendar_export.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}

// Print current view
function printCurrentView() {
    window.print();
}

// Update statistics display
function updateStats() {
    if (processedData.length === 0) return;
    
    // Total races
    totalRacesElement.textContent = processedData.length;
    
    // Unique racecourses
    uniqueRacecoursesElement.textContent = racecourseOptions.size;
    
    // Next race
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const upcomingRaces = processedData
        .filter(race => new Date(race.Date) >= today)
        .sort((a, b) => new Date(a.Date) - new Date(b.Date));
    
    if (upcomingRaces.length > 0) {
        const nextRace = upcomingRaces[0];
        nextRaceElement.textContent = `${nextRace.Racecourse} (${formatDate(nextRace.Date).split(',')[0]})`;
    } else {
        nextRaceElement.textContent = 'None scheduled';
    }
}

// Show loading indicator
function showLoading() {
    loading.style.display = 'flex';
}

// Hide loading indicator
function hideLoading() {
    loading.style.display = 'none';
}
    
    
    </script>