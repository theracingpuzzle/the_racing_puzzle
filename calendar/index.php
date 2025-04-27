<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Horse Racing Calendar Viewer</title>
    <style>
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2e86c1;
            --accent-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-text: #333;
            --light-text: #fff;
            --border-radius: 6px;
            --success-color: #27ae60;
            --turf-color: #2ecc71;
            --aw-color: #e74c3c;
            --jumps-color: #9b59b6;
            --flat-color: #3498db;
            --afternoon-color: #f1c40f;
            --evening-color: #8e44ad;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-text);
            line-height: 1.6;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        header {
            background-color: var(--primary-color);
            color: var(--light-text);
            padding: 20px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-content {
            flex: 1;
        }

        .stats-container {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 10px;
            border-radius: var(--border-radius);
            margin-left: 15px;
            min-width: 250px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .stat-value {
            font-weight: bold;
        }

        h1 {
            margin-bottom: 10px;
        }

        .tabs {
            display: flex;
            margin-bottom: 20px;
            border-bottom: 2px solid var(--secondary-color);
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #e6e6e6;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            margin-right: 5px;
            font-weight: bold;
        }

        .tab.active {
            background-color: var(--secondary-color);
            color: var(--light-text);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s;
        }

        .tab-content.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 150px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        select, input, button {
            padding: 8px 12px;
            border-radius: var(--border-radius);
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: var(--accent-color);
            color: var(--light-text);
            cursor: pointer;
            border: none;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #e67e22;
        }

        .calendar-container {
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .month-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
            padding: 15px;
        }

        .month-header {
            background-color: var(--primary-color);
            color: var(--light-text);
            padding: 10px 15px;
            font-weight: bold;
            text-align: center;
            font-size: 18px;
        }

        .day-name {
            text-align: center;
            font-weight: bold;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .grid-day {
            min-height: 80px;
            border: 1px solid #eee;
            padding: 5px;
            position: relative;
        }

        .grid-day.other-month {
            background-color: #f9f9f9;
            color: #aaa;
        }

        .date-number {
            position: absolute;
            top: 5px;
            right: 5px;
            font-weight: bold;
            font-size: 14px;
        }

        .event-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 3px;
        }

        .event-list {
            margin-top: 20px;
            font-size: 12px;
        }

        .event-item {
            margin-bottom: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }

        .event-item:hover {
            text-decoration: underline;
        }

        .date-group {
            margin-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .date-header {
            background-color: var(--secondary-color);
            color: var(--light-text);
            padding: 10px 15px;
            font-weight: bold;
        }

        .race-list {
            list-style: none;
        }

        .race-item {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
        }

        .race-item:last-child {
            border-bottom: none;
        }

        .race-item:nth-child(even) {
            background-color: #f9f9f9;
        }

        .racecourse {
            font-weight: bold;
            flex: 1;
            min-width: 150px;
        }

        .race-details {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .detail {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }

        .time-of-day {
            background-color: #d6eaf8;
            color: #1a5276;
        }

        .surface {
            background-color: #d4efdf;
            color: #145a32;
        }

        .race-type {
            background-color: #fadbd8;
            color: #922b21;
        }

        .country {
            background-color: #e8daef;
            color: #6c3483;
        }

        .empty-state {
            padding: 50px;
            text-align: center;
            color: #7f8c8d;
        }

        .file-input-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .file-input-container label {
            margin-bottom: 0;
        }

        .tooltip {
            position: fixed;
            background-color: #333;
            color: #fff;
            padding: 10px;
            border-radius: 4px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            max-width: 300px;
            font-size: 14px;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip-content {
            margin-bottom: 5px;
        }

        .tooltip-racecourse {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tooltip-detail {
            display: flex;
            justify-content: space-between;
        }

        .search-container {
            position: relative;
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            padding-left: 35px;
            border-radius: var(--border-radius);
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 10px;
            color: #777;
        }

        .search-results {
            list-style: none;
            background-color: #fff;
            border-radius: var(--border-radius);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
            max-height: 200px;
            overflow-y: auto;
        }

        .search-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            cursor: pointer;
        }

        .search-item:hover {
            background-color: #f5f5f5;
        }

        .search-highlight {
            background-color: #ffeb3b;
            font-weight: bold;
        }

        .chart-container {
            height: 400px;
            margin-bottom: 20px;
            background-color: #fff;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
            
            .month-grid {
                display: none; /* Hide calendar on mobile */
            }
            
            .header {
                flex-direction: column;
            }
            
            .stats-container {
                margin-left: 0;
                margin-top: 15px;
                width: 100%;
            }
        }

        /* Export Button */
        .export-buttons {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .export-btn {
            background-color: var(--success-color);
        }

        /* Loading indicator */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 5px solid var(--secondary-color);
            border-top-color: transparent;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Legend */
        .legend {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 15px;
            background-color: #fff;
            padding: 10px;
            border-radius: var(--border-radius);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-right: 15px;
        }

        .legend-color {
            width: 15px;
            height: 15px;
            border-radius: 50%;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <h1>Enhanced Horse Racing Calendar</h1>
            <p>View upcoming races from AtTheRaces with enhanced visualization</p>
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

    <div class="file-input-container">
        <label for="csvFileInput">Load CSV File:</label>
        <input type="file" id="csvFileInput" accept=".csv">
        <button id="loadDemoData">Load Sample Data</button>
        <button id="loadFullData" class="export-btn">Load Full 2025 Data</button>
    </div>

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
                    <option value="UK">UK</option>
                    <option value="Ireland">Ireland</option>
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
            <div class="empty-state">
                <h3>No racing data loaded</h3>
                <p>Please upload a CSV file or click "Load Sample Data" or "Load Full 2025 Data"</p>
            </div>
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
                <!-- Calendar will be generated here -->
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>
        // Global variables
        let racingData = [];
        let processedData = [];
        let filteredData = [];
        let racecourseOptions = new Set();
        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        const dayNames = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        
        // Charts
        let racesByMonthChart = null;
        let racesByTypeChart = null;
        let topRacecoursesChart = null;

        // DOM elements
        const calendarContainer = document.getElementById('calendar-container');
        const csvFileInput = document.getElementById('csvFileInput');
        const loadDemoButton = document.getElementById('loadDemoData');
        const loadFullDataButton = document.getElementById('loadFullData');
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

        // Add event listeners
        document.addEventListener('DOMContentLoaded', initApp);
        csvFileInput.addEventListener('change', handleFileUpload);
        loadDemoButton.addEventListener('click', loadDemoData);
        loadFullDataButton.addEventListener('click', loadFullData);
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
            
            // Show empty state
            calendarContainer.innerHTML = `
                <div class="empty-state">
                    <h3>No racing data loaded</h3>
                    <p>Please upload a CSV file or click "Load Sample Data" or "Load Full 2025 Data"</p>
                </div>
            `;
        }

        // Parse CSV data
        function parseCSV(text) {
            const lines = text.trim().split('\n');
            const headers = lines[0].split(',').map(header => header.trim());
            
            return lines.slice(1).map(line => {
                const values = line.split(',').map(value => value.trim());
                const entry = {};
                headers.forEach((header, index) => {
                    entry[header] = values[index] || '';
                });
                return entry;
            });
        }

        // Process raw data to clean racecourse names and other fields
        function processRawData(data) {
            return data.map(race => {
                // Extract racecourse name without additional info
                let racecourse = race.Racecourse;
                const racecourseRegex = /^([^(]+)/;
                const match = racecourseRegex.exec(racecourse);
                
                if (match && match[1]) {
                    racecourse = match[1].trim();
                }
                
                return {
                    ...race,
                    Racecourse: racecourse
                };
            });
        }

        // Handle file upload
        function handleFileUpload(event) {
            showLoading();
            const file = event.target.files[0];
            if (!file) {
                hideLoading();
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const csvData = e.target.result;
                racingData = parseCSV(csvData);
                processedData = processRawData(racingData);
                setupRacecourseOptions();
                updateStats();
                applyFilters();
                hideLoading();
            };
            reader.readAsText(file);
        }

        // Load demo data
        function loadDemoData() {
            showLoading();
            // Sample data for when no CSV is loaded
            const demoData = [
                { "Date": "2025-01-01", "Racecourse": "Catterick", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-01-01", "Racecourse": "Cheltenham", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-01-01", "Racecourse": "Exeter", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-01-02", "Racecourse": "Lingfield", "Time of Day": "Afternoon", "Surface": "AW", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-01-02", "Racecourse": "Newcastle", "Time of Day": "Afternoon", "Surface": "AW", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-01-03", "Racecourse": "Wolverhampton", "Time of Day": "Evening", "Surface": "AW", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-01-03", "Racecourse": "Naas", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "Ireland" },
                { "Date": "2025-01-03", "Racecourse": "Fairyhouse", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "Ireland" },
                { "Date": "2025-01-04", "Racecourse": "Sandown", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-01-04", "Racecourse": "Kempton", "Time of Day": "Afternoon", "Surface": "AW", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-02-15", "Racecourse": "Ascot", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-03-10", "Racecourse": "Cheltenham", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-04-12", "Racecourse": "Aintree", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-06-18", "Racecourse": "Ascot", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-07-30", "Racecourse": "Goodwood", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-08-22", "Racecourse": "York", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-09-12", "Racecourse": "Doncaster", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Flat", "Country": "UK" },
                { "Date": "2025-11-07", "Racecourse": "Cheltenham", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" },
                { "Date": "2025-12-26", "Racecourse": "Kempton", "Time of Day": "Afternoon", "Surface": "Turf", "Race Type": "Jumps", "Country": "UK" }
            ];
            
            racingData = demoData;
            processedData = demoData; // Already clean
            setupRacecourseOptions();
            updateStats();
            applyFilters();
            hideLoading();
        }

        // Load full data from the CSV file
        function loadFullData() {
            showLoading();
            
            // Use the CSV data from the racing_calendar.csv file
            fetch('racing_calendar.csv')
                .then(response => response.text())
                .then(data => {
                    racingData = parseCSV(data);
                    processedData = processRawData(racingData);
                    setupRacecourseOptions();
                    updateStats();
                    applyFilters();
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error loading data:', error);
                    loadDemoData(); // Fall back to demo data if fetch fails
                    hideLoading();
                });
        }

        // Setup racecourse dropdown options
        function setupRacecourseOptions() {
            racecourseOptions = new Set();
            processedData.forEach(race => {
                if (race.Racecourse) {
                    racecourseOptions.add(race.Racecourse);
                }
            });

            // Clear existing options except the first one
            while (racecourseFilter.options.length > 1) {
                racecourseFilter.remove(1);
            }

            // Add new options
            Array.from(racecourseOptions).sort().forEach(racecourse => {
                const option = document.createElement('option');
                option.value = racecourse;
                option.textContent = racecourse;
                racecourseFilter.appendChild(option);
            });
        }

        // Apply filters to data
        function applyFilters() {
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
                        <p>Try adjusting your filters or load different data</p>
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
                    const timeClass = race["Time of Day"] === 'Afternoon' ? 'afternoon-color' : 'evening-color';
                    
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
                                <span class="detail time-of-day" style="background-color: var(--${timeClass});">${race["Time of Day"]}</span>
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
                    dayDiv.textContent = prevDay;
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
                        for (const [racecourse, count] of Object.entries(racecourseCount)) {
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
                    dayDiv.textContent = nextMonthDay;
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
                type: 'horizontalBar',
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