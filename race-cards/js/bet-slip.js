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
});

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

// Function to open the bet slip modal with horse details
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
    
    // Calculate potential returns when stake changes
    const stakeInput = document.querySelector('.bet-slip-stake-input');
    const returnsEl = document.querySelector('.bet-slip-returns .bet-slip-value');
    
    // Initial calculation
    calculateReturns(stakeInput, returnsEl);
    
    // Setup event listener for stake changes
    stakeInput.addEventListener('input', function() {
        calculateReturns(this, returnsEl);
    });
}

// Calculate potential returns based on stake
function calculateReturns(stakeInput, returnsEl) {
    const stake = parseFloat(stakeInput.value) || 0;
    // Assuming odds of 5/1 for this example
    const odds = 5; 
    const returns = stake + (stake * odds);
    returnsEl.textContent = `£${returns.toFixed(2)}`;
}