<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>RaceSwipe - Horse Racing Tinder</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      background-color: #f5f5f5;
      color: #333;
      font-size: 16px;
    }
    
    .app-container {
      max-width: 480px;
      margin: 0 auto;
      padding: 20px;
      background: white;
      min-height: 100vh;
      position: relative;
    }
    
    .app-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
      margin-bottom: 20px;
    }
    
    .app-logo {
      font-weight: bold;
      font-size: 24px;
      color: #e94057;
    }
    
    .app-logo span {
      color: #8a2be2;
    }
    
    .header-icons {
      display: flex;
      gap: 15px;
      font-size: 20px;
    }
    
    .race-selector {
      margin-bottom: 20px;
    }
    
    .race-selector select {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ddd;
      background: white;
      font-size: 16px;
      color: #333;
      appearance: none;
      background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23131313%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
      background-repeat: no-repeat;
      background-position: right 12px top 50%;
      background-size: 12px auto;
    }
    
    .race-info {
      background: linear-gradient(to right, #8a2be2, #e94057);
      color: white;
      padding: 15px;
      border-radius: 15px;
      margin-bottom: 20px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .race-title {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    .race-details {
      display: flex;
      justify-content: space-between;
      font-size: 14px;
    }
    
    .race-class {
      background: #8a2be2;
      color: white;
      padding: 2px 8px;
      border-radius: 10px;
      font-size: 12px;
      display: inline-block;
      margin-top: 5px;
    }
    
    .card-container {
      position: relative;
      height: 600px;
      perspective: 1000px;
    }
    
    .card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 15px 35px rgba(0,0,0,0.2);
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      transition: transform 0.6s ease, opacity 0.6s ease;
      transform-style: preserve-3d;
    }
    
    .card.swiped-left {
      transform: translateX(-200%) rotate(-30deg);
      opacity: 0;
    }
    
    .card.swiped-right {
      transform: translateX(200%) rotate(30deg);
      opacity: 0;
    }
    
    .card-header {
      background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.1));
      height: 200px;
      display: flex;
      align-items: flex-end;
      padding: 15px;
      position: relative;
      background-size: cover;
      background-position: center;
    }
    
    .horse-silks {
      position: absolute;
      top: 15px;
      right: 15px;
      width: 50px;
      height: 50px;
      border-radius: 5px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .horse-number {
      position: absolute;
      top: 15px;
      left: 15px;
      background: white;
      color: #333;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      font-weight: bold;
      font-size: 20px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    .card-body {
      padding: 20px;
      max-height: 400px;
      overflow-y: auto;
    }
    
    .horse-name {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
    }
    
    .headgear {
      display: inline-block;
      background: #f5f5f5;
      color: #666;
      padding: 3px 8px;
      border-radius: 12px;
      font-size: 12px;
      margin-left: 10px;
    }
    
    .trainer-jockey {
      color: #666;
      font-size: 16px;
      margin-bottom: 15px;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .stat-box {
      background: #f9f9f9;
      padding: 10px;
      border-radius: 10px;
    }
    
    .stat-label {
      font-size: 12px;
      color: #888;
      text-transform: uppercase;
    }
    
    .stat-value {
      font-size: 16px;
      font-weight: bold;
      color: #333;
    }
    
    .analysis {
      background: #f5f7ff;
      padding: 15px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.4;
      margin-bottom: 20px;
    }
    
    .medical-info {
      background: #fff5f5;
      padding: 15px;
      border-radius: 10px;
      font-size: 14px;
      line-height: 1.4;
      margin-bottom: 20px;
    }
    
    .tags-container {
      margin-bottom: 20px;
    }
    
    .form-tag {
      display: inline-block;
      background: #eee;
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 14px;
      margin-right: 5px;
      margin-bottom: 5px;
    }
    
    .actions {
      display: flex;
      justify-content: space-around;
      padding: 20px;
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: white;
      border-top: 1px solid #eee;
    }
    
    .btn {
      width: 65px;
      height: 65px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      border: none;
      box-shadow: 0 5px 15px rgba(0,0,0,0.15);
      cursor: pointer;
      transition: transform 0.2s;
    }
    
    .btn:hover {
      transform: scale(1.05);
    }
    
    .btn-dislike {
      background: white;
      color: #fd5068;
      font-size: 30px;
    }
    
    .btn-superlike {
      background: #5ae4ff;
      color: white;
      font-size: 25px;
    }
    
    .btn-like {
      background: #3de27f;
      color: white;
      font-size: 30px;
    }
    
    .remaining-count {
      text-align: center;
      font-size: 14px;
      color: #999;
      margin-top: 20px;
    }
    
    .loader {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255,255,255,0.9);
      z-index: 1000;
      justify-content: center;
      align-items: center;
    }
    
    .loader-inner {
      width: 50px;
      height: 50px;
      border: 5px solid #f3f3f3;
      border-top: 5px solid #e94057;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="app-container">
    <div class="app-header">
      <div class="app-logo">Race<span>Swipe</span></div>
      <div class="header-icons">
        <div>🏇</div>
        <div>👑</div>
      </div>
    </div>
    
    <div class="race-selector">
      <select id="race-dropdown">
        <option value="">Select a race</option>
      </select>
    </div>
    
    <div id="race-info" class="race-info">
      <div class="race-title">Virgin Bet Daily Price Boosts Magnolia Stakes</div>
      <div class="race-details">
        <div>Kempton (AW), GB • 2:55PM</div>
        <div>1m2f • Standard To Slow • £28,355</div>
      </div>
      <div class="race-class">Listed Race • Class 1</div>
    </div>
    
    <div class="card-container" id="card-container">
      <!-- Cards will be dynamically inserted here -->
    </div>
    
    <div class="remaining-count" id="remaining-count">
      1 of 8 horses in this race
    </div>
  </div>
  
  <div class="loader" id="loader">
    <div class="loader-inner"></div>
  </div>

  <script>
    // Sample data structure that would be loaded from file
    let racingData = {
      "GB": {
        "Kempton (AW)": {
          "2:55": {
            "course": "Kempton (AW)",
            "course_id": 1079,
            "race_id": 891786,
            "date": "2025-04-21",
            "off_time": "2:55",
            "race_name": "Virgin Bet Daily Price Boosts Magnolia Stakes (Listed Race)",
            "distance_round": "1m2f",
            "region": "GB",
            "pattern": "Listed",
            "race_class": "Class 1",
            "type": "Flat",
            "prize": "£28,355",
            "field_size": 8,
            "going": "Standard To Slow",
            "surface": "AW",
            "runners": [
              {
                "age": 5,
                "horse_id": 5069209,
                "name": "Botanical",
                "sex": "gelding",
                "sex_code": "G",
                "colour": "b",
                "region": "IRE",
                "sire": "Lope De Vega",
                "dam": "Bloomfield",
                "trainer": "George Boughey",
                "trainer_location": "Newmarket, Suffolk",
                "owner": "Sheikh Mohammed Obaid Al Maktoum",
                "comment": "Showed very useful form over 1m2f last term; interesting returned to this trip",
                "spotlight": "Showed very useful form over 1m2f in notable handicaps at York (won/second) and Listed event at Goodwood (went very close) last term; interesting returned to this trip with 1m reappearance under his belt.",
                "medical": [
                  {
                    "date": "2024-10-17",
                    "type": "Wind Surgery"
                  }
                ],
                "number": 1,
                "draw": 5,
                "headgear": "t",
                "headgear_first": "",
                "ofr": 110,
                "rpr": 128,
                "jockey": "Billy Loughnane",
                "last_run": "23",
                "form": "1022-3",
                "silk_url": "https://www.rp-assets.com/svg/6/3/3/55336.svg"
              },
              {
                "age": 6,
                "horse_id": 3791279,
                "name": "Checkandchallenge",
                "sex": "gelding",
                "sex_code": "G",
                "colour": "b/br",
                "region": "GB",
                "sire": "Fast Company",
                "dam": "Likeable",
                "trainer": "William Knight",
                "trainer_location": "Newmarket, Suffolk",
                "owner": "A Hetherton",
                "comment": "Interesting returned to AW, being 2-2 in this sphere (including Listed win)",
                "spotlight": "Ties in with Botanical on reappearance form; has scored only once in last 18 turf runs but looks interesting belatedly returned to AW, being 2-2 in this sphere (first two career starts, including Listed win).",
                "number": 2,
                "draw": 3,
                "headgear": "",
                "headgear_first": "",
                "ofr": 108,
                "rpr": 123,
                "jockey": "Callum Shepherd",
                "last_run": "23",
                "form": "49311-3"
              }
            ]
          }
        }
      },
      "IRE": {
        "Navan": {
          "2:10": {
            "course": "Navan",
            "course_id": 193,
            "race_id": 892324,
            "date": "2025-04-08",
            "off_time": "2:10",
            "race_name": "Return Of The Flat At Navan Handicap",
            "distance_round": "6f",
            "region": "IRE",
            "pattern": "",
            "race_class": "",
            "type": "Flat",
            "prize": "€5,900",
            "field_size": 9,
            "going": "Good",
            "surface": "Turf",
            "runners": [
              {
                "age": 7,
                "horse_id": 3423095,
                "name": "Gegenpressing",
                "sex": "gelding",
                "sex_code": "G",
                "colour": "b",
                "region": "GB",
                "sire": "The Gurkha",
                "dam": "Bouyrin",
                "trainer": "Eddie & Patrick Harty",
                "trainer_location": "Curragh, Co Kildare",
                "owner": "Mrs E P Harty",
                "comment": "4-46 on turf, handles most ground, well-treated on old form but might need this run",
                "spotlight": "4-46 on turf; last three wins on soft but handles good ground; well-handicapped on best form but poor record first time out in previous seasons; others preferred.",
                "number": 1,
                "draw": 5,
                "headgear": "t",
                "headgear_first": "",
                "ofr": 53,
                "rpr": 59,
                "jockey": "Alexandra Egan(10)",
                "last_run": "173",
                "form": "40630-"
              }
            ]
          }
        }
      }
    };
    
    // DOM elements
    const raceDropdown = document.getElementById('race-dropdown');
    const raceInfo = document.getElementById('race-info');
    const cardContainer = document.getElementById('card-container');
    const remainingCount = document.getElementById('remaining-count');
    const loader = document.getElementById('loader');
    
    // Variables to track state
    let currentRace = null;
    let currentRunners = [];
    let currentRunnerIndex = 0;
    
    // Function to initialize the app
    function initApp() {
      populateRaceDropdown();
      raceDropdown.addEventListener('change', handleRaceSelection);
    }
    
    // Function to populate race dropdown
    function populateRaceDropdown() {
      let optionIndex = 1;
      
      // Loop through the data structure
      for (const region in racingData) {
        for (const course in racingData[region]) {
          for (const time in racingData[region][course]) {
            const race = racingData[region][course][time];
            const option = document.createElement('option');
            option.value = `${region}|${course}|${time}`;
            option.textContent = `${race.race_name} - ${course} (${time})`;
            raceDropdown.appendChild(option);
            
            if (optionIndex === 1) {
              option.selected = true;
              handleRaceSelection({ target: { value: option.value } });
            }
            
            optionIndex++;
          }
        }
      }
    }
    
    // Function to handle race selection
    function handleRaceSelection(event) {
      showLoader();
      
      const value = event.target.value;
      if (!value) {
        hideLoader();
        return;
      }
      
      const [region, course, time] = value.split('|');
      currentRace = racingData[region][course][time];
      currentRunners = currentRace.runners;
      currentRunnerIndex = 0;
      
      updateRaceInfo();
      clearCards();
      createCards();
      updateRemainingCount();
      
      hideLoader();
    }
    
    // Function to update race info display
    function updateRaceInfo() {
      let classInfo = '';
      if (currentRace.pattern) {
        classInfo = `${currentRace.pattern}${currentRace.race_class ? ' • ' + currentRace.race_class : ''}`;
      } else if (currentRace.race_class) {
        classInfo = currentRace.race_class;
      }
      
      const raceTitle = currentRace.race_name.replace(/\([^)]*\)/g, '').trim();
      
      raceInfo.innerHTML = `
        <div class="race-title">${raceTitle}</div>
        <div class="race-details">
          <div>${currentRace.course}, ${currentRace.region} • ${currentRace.off_time}</div>
          <div>${currentRace.distance_round} • ${currentRace.going} • ${currentRace.prize}</div>
        </div>
        ${classInfo ? `<div class="race-class">${classInfo}</div>` : ''}
      `;
    }
    
    // Function to clear existing cards
    function clearCards() {
      cardContainer.innerHTML = '';
    }
    
    // Function to create cards for all runners
    function createCards() {
      currentRunners.forEach((runner, index) => {
        const card = createRunnerCard(runner, index);
        card.style.zIndex = 1000 - index;
        if (index > 0) {
          card.style.opacity = '0';
          card.style.pointerEvents = 'none';
        }
        cardContainer.appendChild(card);
      });
    }
    
    // Function to create a single runner card
    function createRunnerCard(runner, index) {
      const card = document.createElement('div');
      card.className = 'card';
      card.id = `card-${index}`;
      
      // Generate horse image based on color
      const colorMap = {
        'b': '#2e2e2e', // black
        'br': '#654321', // brown
        'ch': '#d2691e', // chestnut
        'gr': '#808080', // grey
        'b/br': '#3e2723', // black/brown
        'ch': '#b35c1e' // chestnut
      };
      
      const horseColor = colorMap[runner.colour] || '#654321';
      
      card.innerHTML = `
        <div class="card-header" style="background-color: ${horseColor};">
          <div class="horse-number">${runner.number}</div>
          ${runner.silk_url ? `<div class="horse-silks"><img src="${runner.silk_url}" width="50" height="50" alt="Silks"></div>` : ''}
        </div>
        
        <div class="card-body">
          <h2 class="horse-name">
            ${runner.name}
            ${runner.headgear ? `<span class="headgear">${runner.headgear.toUpperCase()}</span>` : ''}
          </h2>
          
          <div class="trainer-jockey">
            ${runner.jockey} • ${runner.trainer}
          </div>
          
          <div class="stats-grid">
            <div class="stat-box">
              <div class="stat-label">AGE/SEX</div>
              <div class="stat-value">${runner.age}yo ${getSexFull(runner.sex_code)}</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">RATING</div>
              <div class="stat-value">${runner.ofr || 'n/a'}</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">FORM</div>
              <div class="stat-value">${runner.form || 'n/a'}</div>
            </div>
            <div class="stat-box">
              <div class="stat-label">DAYS SINCE RUN</div>
              <div class="stat-value">${runner.last_run || 'n/a'}</div>
            </div>
          </div>
          
          ${runner.spotlight ? `<div class="analysis">${runner.spotlight}</div>` : 
            runner.comment ? `<div class="analysis">${runner.comment}</div>` : ''}
          
          ${runner.medical && runner.medical.length > 0 ? 
            `<div class="medical-info">
              <strong>Medical:</strong> ${runner.medical.map(m => `${m.type} (${m.date})`).join(', ')}
            </div>` : ''}
          
          <div class="tags-container">
            <div class="form-tag">Draw: ${runner.draw || 'n/a'}</div>
            <div class="form-tag">Sire: ${runner.sire || 'n/a'}</div>
            <div class="form-tag">Dam: ${runner.dam || 'n/a'}</div>
            <div class="form-tag">Origin: ${runner.region || 'n/a'}</div>
          </div>
        </div>
      `;
      
      return card;
    }
    
    // Function to update remaining count display
    function updateRemainingCount() {
      remainingCount.textContent = `${currentRunnerIndex + 1} of ${currentRunners.length} horses in this race`;
    }
    
    // Function to handle swipe actions
    function handleSwipe(direction) {
      if (currentRunners.length === 0) return;
      
      const currentCard = document.getElementById(`card-${currentRunnerIndex}`);
      
      if (direction === 'left') {
        currentCard.classList.add('swiped-left');
      } else if (direction === 'right') {
        currentCard.classList.add('swiped-right');
      }
      
      // Move to next card
      setTimeout(() => {
        currentCard.style.display = 'none';
        currentRunnerIndex++;
        
        if (currentRunnerIndex < currentRunners.length) {
          const nextCard = document.getElementById(`card-${currentRunnerIndex}`);
          nextCard.style.opacity = '1';
          nextCard.style.pointerEvents = 'auto';
          updateRemainingCount();
        } else {
          // No more cards
          remainingCount.textContent = 'No more horses in this race';
          // Could show a "reload" button or automatically reset here
        }
      }, 300);
    }
    
    // Helper function to get full sex name
    function getSexFull(sexCode) {
      const sexMap = {
        'C': 'Colt',
        'F': 'Filly',
        'G': 'Gelding',
        'H': 'Horse',
        'M': 'Mare'
      };
      return sexMap[sexCode] || sexCode;
    }
    
    // Loading functions
    function showLoader() {
      loader.style.display = 'flex';
    }
    
    function hideLoader() {
      loader.style.display = 'none';
    }
    
    // Add event listeners for the buttons
    document.addEventListener('DOMContentLoaded', function() {
      document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-dislike')) {
          handleSwipe('left');
        } else if (event.target.classList.contains('btn-like')) {
          handleSwipe('right');
        } else if (event.target.classList.contains('btn-superlike')) {
          // Handle superlike - could be a special betting option
          handleSwipe('up');
        }
      });
      
      // Add keyboard controls
      document.addEventListener('keydown', function(event) {
        if (event.key === 'ArrowLeft') {
          handleSwipe('left');
        } else if (event.key === 'ArrowRight') {
          handleSwipe('right');
        } else if (event.key === 'ArrowUp') {
          handleSwipe('up');
        }
      });
      
      // Initialize the app
      initApp();
      
      // Dynamically add the action buttons after the cards are created
      const actions = document.createElement('div');
      actions.className = 'actions';
      actions.innerHTML = `
        <button class="btn btn-dislike">✕</button>
        <button class="btn btn-superlike">★</button>
        <button class="btn btn-like">♥</button>
      `;
      cardContainer.appendChild(actions);
    });
    
    // Function that would be used to load data from file
    function loadDataFromFile(fileInput) {
      showLoader();
      
      const file = fileInput.files[0];
      if (!file) {
        hideLoader();
        return;
      }
      
      const reader = new FileReader();
      
      reader.onload = function(e) {
        try {
          const data = JSON.parse(e.target.result);
          racingData = data;
          
          // Reset UI
          raceDropdown.innerHTML = '<option value="">Select a race</option>';
          clearCards();
          
          // Populate races
          populateRaceDropdown();
          
          hideLoader();
        } catch (error) {
          console.error('Error parsing JSON file:', error);
          alert('Error parsing the racing data file. Please ensure it\'s valid JSON.');
          hideLoader();
        }
      };
      
      reader.onerror = function() {
        console.error('Error reading file');
        alert('Error reading the file');
        hideLoader();
      };
      
      reader.readAsText(file);
    }
  </script>
</body>
</html>