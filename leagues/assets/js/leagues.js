
document.addEventListener('DOMContentLoaded', function() {
    // Create League Modal
    const createLeagueBtn = document.getElementById('createLeagueBtn');
    const createLeagueModal = document.getElementById('createLeagueModal');
    if (createLeagueBtn && createLeagueModal) {
        const createModal = new bootstrap.Modal(createLeagueModal);
        createLeagueBtn.addEventListener('click', function() {
            createModal.show();
        });
    }
    
    // Join League Modal
    const joinLeagueBtn = document.getElementById('joinLeagueBtn');
    const joinLeagueModal = document.getElementById('joinLeagueModal');
    if (joinLeagueBtn && joinLeagueModal) {
        const joinModal = new bootstrap.Modal(joinLeagueModal);
        joinLeagueBtn.addEventListener('click', function() {
            joinModal.show();
        });
    }
    
    // Edit League Modal
    const editLeagueBtn = document.getElementById('editLeagueBtn');
    const editLeagueModal = document.getElementById('editLeagueModal');
    if (editLeagueBtn && editLeagueModal) {
        const editModal = new bootstrap.Modal(editLeagueModal);
        editLeagueBtn.addEventListener('click', function() {
            // Get league data from button attributes
            const leagueId = this.getAttribute('data-league-id');
            const leagueName = this.getAttribute('data-league-name');
            const leagueDescription = this.getAttribute('data-league-description');
            const rankingType = this.getAttribute('data-ranking-type');
            
            // Populate form fields
            document.getElementById('edit_league_id').value = leagueId;
            document.getElementById('edit_league_name').value = leagueName;
            document.getElementById('edit_league_description').value = leagueDescription;
            
            // Set ranking type radio button
            if (rankingType === 'winners') {
                document.getElementById('edit_ranking_winners').checked = true;
            } else {
                document.getElementById('edit_ranking_returns').checked = true;
            }
            
            // Show modal
            editModal.show();
        });
    }
    
    // Copy PIN button functionality
    const copyPinBtn = document.getElementById('copyPinBtn');
    if (copyPinBtn) {
        copyPinBtn.addEventListener('click', function() {
            const pin = this.getAttribute('data-pin');
            
            // Create a temporary input element
            const tempInput = document.createElement('input');
            tempInput.value = pin;
            document.body.appendChild(tempInput);
            
            // Select and copy the text
            tempInput.select();
            document.execCommand('copy');
            
            // Remove the temporary element
            document.body.removeChild(tempInput);
            
            // Update button text temporarily
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-check"></i> Copied!';
            
            // Reset button text after a delay
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });
    }
});
