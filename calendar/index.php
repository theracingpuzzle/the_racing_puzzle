<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Calendar Viewer</title>
    <style>
        :root {
            --primary-color: #1a5276;
            --secondary-color: #2e86c1;
            --accent-color: #f39c12;
            --light-bg: #f8f9fa;
            --dark-text: #333;
            --light-text: #fff;
            --border-radius: 6px;
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
        }

        h1 {
            margin-bottom: 10px;
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
            gap: 15px;
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
        }

        @media (max-width: 768px) {
            .controls {
                flex-direction: column;
            }
            
            .filter-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Horse Racing Calendar</h1>
        <p>View upcoming races from AtTheRaces</p>
    </header>

    <div class="file-input-container">
        <label for="csvFileInput">Load CSV File:</label>
        <input type="file" id="csvFileInput" accept=".csv">
        <button id="loadDemoData">Load Demo Data</button>
    </div>

    <div class="controls">
        <div class="filter-group">
            <label for="dateFilter">Filter by Date:</label>
            <input type="date" id="dateFilter">
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

    <div id="calendar-container" class="calendar-container">
        <div class="empty-state">
            <h3>No racing data loaded</h3>
            <p>Please upload a CSV file or click "Load Demo Data"</p>
        </div>
    </div>

    <script>
        // Demo data for when no CSV is loaded
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
            { "Date": "2025-01-04", "Racecourse": "Kempton", "Time of Day": "Afternoon", "Surface": "AW", "Race Type": "Flat", "Country": "UK" }
        ];

        let racingData = [];
        let filteredData = [];
        let racecourseOptions = new Set();

        // DOM elements
        const calendarContainer = document.getElementById('calendar-container');
        const csvFileInput = document.getElementById('csvFileInput');
        const loadDemoButton = document.getElementById('loadDemoData');
        const dateFilter = document.getElementById('dateFilter');
        const racecourseFilter = document.getElementById('racecourseFilter');
        const surfaceFilter = document.getElementById('surfaceFilter');
        const raceTypeFilter = document.getElementById('raceTypeFilter');
        const countryFilter = document.getElementById('countryFilter');
        const resetFiltersButton = document.getElementById('resetFilters');

        // Add event listeners
        csvFileInput.addEventListener('change', handleFileUpload);
        loadDemoButton.addEventListener('click', loadDemoData);
        dateFilter.addEventListener('change', applyFilters);
        racecourseFilter.addEventListener('change', applyFilters);
        surfaceFilter.addEventListener('change', applyFilters);
        raceTypeFilter.addEventListener('change', applyFilters);
        countryFilter.addEventListener('change', applyFilters);
        resetFiltersButton.addEventListener('click', resetFilters);

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

        // Handle file upload
        function handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const csvData = e.target.result;
                racingData = parseCSV(csvData);
                setupRacecourseOptions();
                applyFilters();
            };
            reader.readAsText(file);
        }

        // Load demo data
        function loadDemoData() {
            racingData = demoData;
            setupRacecourseOptions();
            applyFilters();
        }

        // Setup racecourse dropdown options
        function setupRacecourseOptions() {
            racecourseOptions = new Set();
            racingData.forEach(race => {
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
            const racecourseValue = racecourseFilter.value;
            const surfaceValue = surfaceFilter.value;
            const raceTypeValue = raceTypeFilter.value;
            const countryValue = countryFilter.value;

            filteredData = racingData.filter(race => {
                if (dateValue && race.Date !== dateValue) return false;
                if (racecourseValue && race.Racecourse !== racecourseValue) return false;
                if (surfaceValue && race.Surface !== surfaceValue) return false;
                if (raceTypeValue && race.Race_Type !== raceTypeValue) return false;
                if (countryValue && race.Country !== countryValue) return false;
                return true;
            });

            renderCalendar();
        }

        // Reset all filters
        function resetFilters() {
            dateFilter.value = '';
            racecourseFilter.value = '';
            surfaceFilter.value = '';
            raceTypeFilter.value = '';
            countryFilter.value = '';
            applyFilters();
        }

        // Render calendar with filtered data
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
                    html += `
                        <li class="race-item">
                            <div class="racecourse">${race.Racecourse}</div>
                            <div class="race-details">
                                <span class="detail time-of-day">${race["Time of Day"]}</span>
                                <span class="detail surface">${race.Surface}</span>
                                <span class="detail race-type">${race["Race Type"]}</span>
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
        }

        // Format date for display
        function formatDate(dateStr) {
            if (!dateStr) return '';
            
            const date = new Date(dateStr);
            if (isNaN(date.getTime())) return dateStr;
            
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('en-GB', options);
        }

        // Initialize with demo data
        document.addEventListener('DOMContentLoaded', function() {
            calendarContainer.innerHTML = `
                <div class="empty-state">
                    <h3>No racing data loaded</h3>
                    <p>Please upload a CSV file or click "Load Demo Data"</p>
                </div>
            `;
        });
    </script>
</body>
</html>