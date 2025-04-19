// racecards.js
// This script reads the racing JSON data and generates HTML race cards dynamically

document.addEventListener('DOMContentLoaded', function() {
    // Fetch the JSON data
    // In production, replace this URL with the path to your actual JSON file
    fetch('../racecards/assets/js/2025-04-19.json')
        .then(response => response.json())
        .then(data => {
            // Process the data
            generateRaceCards(data);
        })
        .catch(error => {
            console.error('Error loading race data:', error);
            document.getElementById('race-container').innerHTML = '<div class="loading">Error loading race data. Please try again later.</div>';
        });
});

function generateRaceCards(data) {
    const container = document.getElementById('race-container');
    container.innerHTML = ''; // Clear any existing content
    
    // Loop through each region (e.g., "IRE", "GB")
    for (const region in data) {
        // Loop through each course in the region
        for (const course in data[region]) {
            // Create a course container (will contain header and races)
            const courseContainer = document.createElement('div');
            courseContainer.className = 'course-container';
            
            // Create a course header
            const courseHeader = document.createElement('div');
            courseHeader.className = 'course-header collapsed';
            courseHeader.innerHTML = `
            <h2>${course} <span class="region-tag">&nbsp;${region}</span></h2>
            <span class="toggle-icon">+</span>
            `;

            courseContainer.appendChild(courseHeader);
            
            // Create a container for all races at this course
            const racesContainer = document.createElement('div');
            racesContainer.className = 'races-container';
            racesContainer.style.display = 'none'; // Initially hidden
            
            // Get all races for this course and sort them by time
            const courseRaces = data[region][course];
            const sortedRaceTimes = Object.keys(courseRaces).sort();
            
            // Loop through each race at the course in time order
            for (const raceTime of sortedRaceTimes) {
                const race = courseRaces[raceTime];
                
                // Create race card
                const raceCard = createRaceCard(race, raceTime);
                racesContainer.appendChild(raceCard);
            }
            
            // Add races container to course container
            courseContainer.appendChild(racesContainer);
            
            // Add the course container to the main container
            container.appendChild(courseContainer);
            
            // Add click event to course header
            courseHeader.addEventListener('click', function() {
                this.classList.toggle('collapsed');
                this.classList.toggle('expanded');
                
                const toggleIcon = this.querySelector('.toggle-icon');
                const racesContainer = this.nextElementSibling;
                
                if (this.classList.contains('collapsed')) {
                    racesContainer.style.display = 'none';
                    toggleIcon.textContent = '+';
                } else {
                    racesContainer.style.display = 'block';
                    toggleIcon.textContent = '−'; // en dash for minus sign
                }
            });
        }
    }
}

function createRaceCard(race, raceTime) {
    // Format race time for display (e.g. "14:30" to "2:30 PM")
    const formattedTime = formatRaceTime(raceTime);
    
    // Create the main race card element
    const raceCard = document.createElement('div');
    raceCard.className = 'race-card';
    raceCard.id = `race-${race.race_id}`;
    
    // Create race header
    const raceHeader = document.createElement('div');
    raceHeader.className = 'race-header';
    raceHeader.innerHTML = `
        <h3>${race.course} ${formattedTime}</h3>
        <h4>${race.race_name}</h4>
    `;

    // Gives me the date in English format
    const originalDate = new Date(race.date);
const formattedDate = `${originalDate.getDate().toString().padStart(2, '0')}/${(originalDate.getMonth() + 1).toString().padStart(2, '0')}/${originalDate.getFullYear()}`;

    
    // Create race info
    const raceInfo = document.createElement('div');
    raceInfo.className = 'race-info';
    raceInfo.innerHTML = `
        <div>
        <p><strong>Date:</strong> ${formattedDate}</p>
            <p><strong>Distance:</strong> ${race.distance_round} (${race.distance})</p>
            <p><strong>Type:</strong> ${race.type}</p>
        </div>
        <div>
            <p><strong>Age Band:</strong> ${race.age_band}</p>
            <p><strong>Rating Band:</strong> ${race.rating_band}</p>
            <p><strong>Prize Money:</strong> ${race.prize}</p>
        </div>
        <div>
            <p><strong>Going:</strong> ${race.going_detailed}</p>
            <p><strong>Weather:</strong> ${race.weather}</p>
            <p><strong>Field Size:</strong> ${race.field_size} runners</p>
        </div>
    `;
    
    // Create a container for the table (for scrolling)
    const runnersTableContainer = document.createElement('div');
    runnersTableContainer.className = 'race-runners-container';
    
    // Create table for runners
    const runnersTable = document.createElement('table');
    runnersTable.className = 'race-runners';
    
    // Table header
    const tableHeader = document.createElement('thead');
    tableHeader.innerHTML = `
        <tr>
            <th>No.</th>
            <th>Draw</th>
            <th>Silks</th>
            <th>Horse</th>
            <th>Age</th>
            <th>Weight</th>
            <th>OR</th>
            <th>Jockey</th>
            <th>Trainer</th>
            <th>Form</th>
            <th>Comment</th>
            <th>Tracker</th>
        </tr>
    `;
    runnersTable.appendChild(tableHeader);
    
    // Table body
    const tableBody = document.createElement('tbody');
    
    // Sort runners by number
    const sortedRunners = [...race.runners].sort((a, b) => a.number - b.number);
    
    // Add each runner to the table
    sortedRunners.forEach(runner => {
        const row = document.createElement('tr');
        
        // Convert weight from pounds to stones and pounds
        const stonesPounds = convertPoundsToStonesPounds(runner.lbs);
        
        // Determine headgear text
        let headgearText = '';
        if (runner.headgear) {
            const headgearMap = {
                't': 'Tongue Tie',
                'h': 'Hood',
                'p': 'Cheekpieces',
                'b': 'Blinkers',
                'v': 'Visor'
            };
            const headgear = runner.headgear.split('').map(h => headgearMap[h] || h).join(', ');
            headgearText = `<span class="headgear">(${headgear})</span>`;
        }
        
        // Handle silks image
        const silksImg = runner.silk_url ? 
            `<img src="${runner.silk_url}" alt="Racing silks for ${runner.name}" class="silks-image">` : 
            `<div class="no-silks">N/A</div>`;
        
        row.innerHTML = `
            <td>${runner.number}</td>
            <td>${runner.draw || '-'}</td>
            <td class="silks-cell">${silksImg}</td>
            <td class="horse-name">${runner.name} ${headgearText}</td>
            <td>${runner.age}</td>
            <td>${stonesPounds}</td>
            <td>${runner.ofr || '-'}</td>
            <td class="jockey">${runner.jockey}</td>
            <td>${runner.trainer}</td>
            <td>${runner.form || '-'}</td>
            <td class="comment">${runner.comment || ''}</td>
            <td class="tracker-cell">
            <button class="tracker-btn" title="Add to tracker" 
    onclick="openQuickTracker('${runner.name}', '${runner.jockey || ''}', '${runner.trainer || ''}')">
    🔍
</button>
        </td>
        `;
        
        tableBody.appendChild(row);
    });
    
    runnersTable.appendChild(tableBody);
    runnersTableContainer.appendChild(runnersTable);
    
    // Add a legend for headgear
    const headgearLegend = document.createElement('div');
    headgearLegend.className = 'headgear-legend';
    headgearLegend.innerHTML = `
        <p><strong>Key to Headgear:</strong> t = Tongue Tie, h = Hood, p = Cheekpieces, b = Blinkers, v = Visor</p>
    `;
    
    // Assemble the race card
    raceCard.appendChild(raceHeader);
    raceCard.appendChild(raceInfo);
    raceCard.appendChild(runnersTableContainer);
    raceCard.appendChild(headgearLegend);
    
    return raceCard;
}

// Helper function to convert pounds to stones and pounds format
function convertPoundsToStonesPounds(pounds) {
    if (!pounds) return '-';
    const stones = Math.floor(pounds / 14);
    const remainingPounds = pounds % 14;
    return `${stones}-${remainingPounds}`;
}

// Helper function to format race time (24-hour to 12-hour)
function formatRaceTime(timeString) {
    // Check if the time already has AM or PM
    if (timeString.match(/\d{1,2}:\d{2} (AM|PM)/i)) {
        return timeString; // Already in 12-hour format, return as is
    }

    // Otherwise, assume it's in 12-hour format but missing AM/PM
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours, 10);

    // Determine AM/PM based on a reasonable assumption (e.g., morning races)
    const ampm = hour >= 1 && hour < 12 ? 'AM' : 'PM';

    return `${hour}:${minutes} ${ampm}`;
}
