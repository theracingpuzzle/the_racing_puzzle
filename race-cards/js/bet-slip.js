// Define the bet slip functionality
document.addEventListener('DOMContentLoaded', function() {
    // Get the modal element
    const betSlipModal = document.getElementById('bet-slip-modal');
    const closeBetSlip = document.querySelector('.close-bet-slip');
    const betSlipForm = document.getElementById('bet-slip-form');
    const betSlipItems = document.getElementById('bet-slip-items');
    
    // Close modal when clicking the X
    if (closeBetSlip) {
        closeBetSlip.addEventListener('click', function() {
            betSlipModal.style.display = 'none';
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === betSlipModal) {
            betSlipModal.style.display = 'none';
        }
    });
    
    // Add "Cancel" button functionality
    const cancelButtons = document.querySelectorAll('.close-modal');
    cancelButtons.forEach(button => {
        button.addEventListener('click', function() {
            betSlipModal.style.display = 'none';
        });
    });
    
    // Add functionality to all circle-plus buttons - both in card view and table view
    setupBetSlipButtons();
    
    // Setup place bet button functionality
    setupPlaceBetButton();
});

function setupPlaceBetButton() {
    // Get the place bet button
    const placeBetButton = document.querySelector('.bet-slip-actions .btn-primary');
    
    if (placeBetButton) {
        placeBetButton.addEventListener('click', function() {
            submitBet();
        });
    }
}

function submitBet() {
    // Get all the necessary data from the bet slip
    const raceCourseTimeEl = document.getElementById('bet-slip-course-time');
    const horseNameEl = document.getElementById('bet-slip-horse-name');
    const jockeyEl = document.getElementById('bet-slip-jockey');
    const trainerEl = document.getElementById('bet-slip-trainer');
    const stakeInput = document.querySelector('.bet-slip-stake-input');
    const oddsInput = document.querySelector('.bet-slip-odds-input');
    const returnsEl = document.querySelector('.bet-slip-returns .bet-slip-value');
    
    // Validate that odds have been entered
    if (!oddsInput.value.trim()) {
        alert('Please enter the odds before placing your bet');
        oddsInput.focus();
        return;
    }
    
    // Extract the racecourse from the course-time text
    const raceCourseTime = raceCourseTimeEl.textContent;
    const raceCourse = raceCourseTime.split(' - ')[0];
    
    // Get the stake value
    const stake = parseFloat(stakeInput.value) || 0;
    
    // Create a form to submit the data
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '../bet-record/includes/process_bet.php';
    form.style.display = 'none';
    
    // Add all required fields to the form
    const formData = {
        'bet_type': 'Win',
        'stake': stake,
        'selection': horseNameEl.textContent,
        'odds': oddsInput.value, // Use the actual input value
        'jockey': jockeyEl.textContent,
        'trainer': trainerEl.textContent,
        'outcome': 'Pending',
        'racecourse': raceCourse
    };
    
    // Create form inputs for each data field
    for (const key in formData) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = formData[key];
        form.appendChild(input);
    }
    
    // Append the form to the body
    document.body.appendChild(form);
    
    // Log the form data for debugging
    console.log('Submitting bet with data:', formData);
    console.log('Form action:', form.action);
    
    // Submit the form
    form.submit();
    
    // Close the modal
    const betSlipModal = document.getElementById('bet-slip-modal');
    betSlipModal.style.display = 'none';
}


function setupBetSlipButtons() {
    // Find all circle-plus buttons in table view
    const tableAddButtons = document.querySelectorAll('.table-action-btn .fa-circle-plus');
    
    tableAddButtons.forEach(button => {
        const buttonParent = button.closest('.table-action-btn');
        
        if (buttonParent) {
            buttonParent.addEventListener('click', function(e) {
                e.preventDefault();
                handleBetSlipButtonClick(this);
            });
        }
    });
    
    // Find all circle-plus buttons in card view
    const cardAddButtons = document.querySelectorAll('.runner-action .fa-circle-plus');
    
    cardAddButtons.forEach(button => {
        const buttonParent = button.closest('.runner-action');
        
        if (buttonParent) {
            buttonParent.addEventListener('click', function(e) {
                e.preventDefault();
                handleBetSlipButtonClick(this);
            });
        }
    });
}

function handleBetSlipButtonClick(buttonElement) {
    // Get runner data
    let horseName, jockey, trainer, raceCourse, raceTime;
    
    // Different DOM traversal based on where the button is located
    if (buttonElement.closest('.runner-card')) {
        // Card view
        const runnerCard = buttonElement.closest('.runner-card');
        horseName = runnerCard.querySelector('.runner-name').textContent;
        jockey = runnerCard.querySelector('.jockey').textContent;
        trainer = runnerCard.querySelectorAll('.data-value')[1].textContent;
        
        // Get race info from parent elements
        const raceCard = runnerCard.closest('.race-card');
        raceCourse = raceCard.closest('.course-container').querySelector('.course-header h2').textContent.trim();
        raceTime = raceCard.querySelector('.race-time').textContent;
    } 
    else if (buttonElement.closest('.runner-row')) {
        // Table view
        const runnerRow = buttonElement.closest('.runner-row');
        horseName = runnerRow.querySelector('.horse-name').textContent;
        jockey = runnerRow.querySelector('.jockey-col').textContent;
        trainer = runnerRow.querySelector('.trainer-col').textContent;
        
        // Get race info from parent elements
        const raceCard = runnerRow.closest('.race-card');
        raceCourse = raceCard.closest('.course-container').querySelector('.course-header h2').textContent.trim();
        raceTime = raceCard.querySelector('.race-time').textContent;
    }
    
    // Open the bet slip modal with the gathered information
    openBetSlip(horseName, jockey, trainer, raceCourse, raceTime);
}

function openBetSlip(horseName, jockey, trainer, raceCourse, raceTime) {
    const modal = document.getElementById('bet-slip-modal');
    const courseTimeEl = document.getElementById('bet-slip-course-time');
    const horseNameEl = document.getElementById('bet-slip-horse-name');
    const jockeyEl = document.getElementById('bet-slip-jockey');
    const trainerEl = document.getElementById('bet-slip-trainer');
    
    // Set the values in the modal
    courseTimeEl.textContent = `${raceCourse} - ${raceTime}`;
    horseNameEl.textContent = horseName;
    jockeyEl.textContent = jockey;
    trainerEl.textContent = trainer;
    
    // Display the modal
    modal.style.display = 'block';
    
    // Position the modal in the top right
    modal.classList.add('top-right');
    
    // Get the input elements
    const stakeInput = document.querySelector('.bet-slip-stake-input');
    const oddsInput = document.querySelector('.bet-slip-odds-input'); 
    const returnsEl = document.querySelector('.bet-slip-returns .bet-slip-value');
    
    // Clear the odds input
    oddsInput.value = '';
    
    // Reset returns to 0
    returnsEl.textContent = '£0.00';
    
    // Remove existing event listeners to prevent duplicates
    const newStakeInput = stakeInput.cloneNode(true);
    stakeInput.parentNode.replaceChild(newStakeInput, stakeInput);
    
    const newOddsInput = oddsInput.cloneNode(true);
    oddsInput.parentNode.replaceChild(newOddsInput, oddsInput);
    
    // Initial calculation
    calculateReturns(newStakeInput, newOddsInput, returnsEl); 
    
    // Setup new event listeners
    newStakeInput.addEventListener('input', function() {
        calculateReturns(newStakeInput, newOddsInput, returnsEl); 
    });
    
    newOddsInput.addEventListener('input', function() {
        calculateReturns(newStakeInput, newOddsInput, returnsEl);
    });
}

// Update the calculateReturns function to handle empty odds
function calculateReturns(stakeInput, oddsInput, returnsEl) {
    const stake = parseFloat(stakeInput.value) || 0;
    const oddsValue = oddsInput.value.trim();
    
    let returns = 0;
    
    // Only calculate if odds value is not empty
    if (oddsValue) {
        // Parse odds depending on format (fractional or decimal)
        if (oddsValue.includes('/')) {
            // Fractional odds (e.g., 5/1)
            const [numerator, denominator] = oddsValue.split('/').map(Number);
            if (!isNaN(numerator) && !isNaN(denominator) && denominator !== 0) {
                returns = stake + (stake * (numerator / denominator));
            }
        } else {
            // Decimal odds (e.g., 6.0)
            const decimalOdds = parseFloat(oddsValue);
            if (!isNaN(decimalOdds)) {
                returns = stake * decimalOdds;
            }
        }
    }
    
    returnsEl.textContent = `£${returns.toFixed(2)}`;
}