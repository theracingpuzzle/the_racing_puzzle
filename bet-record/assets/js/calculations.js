/**
 * calculations.js - Handles betting calculations
 * Responsible for calculating potential returns and profits for different bet types
 */
document.addEventListener('DOMContentLoaded', function() {
    const betTypeSelect = document.getElementById('bet_type');
    const stakeInput = document.getElementById('stake');
    const outcomeSelect = document.getElementById('outcome');
    const potentialReturnsEl = document.getElementById('potentialReturns');
    const potentialProfitEl = document.getElementById('potentialProfit');
    
    // Add event listeners for return calculation
    if (stakeInput) {
        stakeInput.addEventListener('input', calculateReturns);
    }
    
    const oddsInputs = document.querySelectorAll('[id^="odds_"]');
    oddsInputs.forEach(input => {
        input.addEventListener('input', calculateReturns);
    });
    
    // Calculate potential returns
    function calculateReturns() {
        if (!betTypeSelect || !stakeInput || !outcomeSelect || 
            !potentialReturnsEl || !potentialProfitEl) return;
        
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
            
            const numberOfSelectionsSelect = document.getElementById('numberOfSelections');
            const minRequiredSelections = (betType === 'Accumulator' && numberOfSelectionsSelect) ? 
                parseInt(numberOfSelectionsSelect.value) : 1;
            
            const isBetValid = (betType !== 'Accumulator' && validOddsCount >= 1) || 
                            (betType === 'Accumulator' && validOddsCount >= minRequiredSelections);
            
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
    
    // Expose function to global scope
    window.calculateReturns = calculateReturns;
});