/**
 * Horse Tracker - Search & Filtering
 */
const HorseFilters = {
    /**
     * Initialize the filters
     */
    init: function() {
        this.bindEvents();
    },
    
    /**
     * Bind event handlers
     */
    bindEvents: function() {
        // Search input
        $('#searchInput').on('keyup', this.handleSearch);
        
        // Clear search button
        $('#clearSearch').on('click', this.handleClearSearch);
        
        // Trainer filter
        $('#filterTrainer').on('change', this.handleTrainerFilter);
        
        // Sort options
        $('#sortOption').on('change', this.handleSortChange);
    },
    
    /**
     * Handle search input
     */
    handleSearch: function() {
        const searchTerm = $(this).val().toLowerCase();
        
        if (searchTerm.length === 0) {
            HorseFilters.handleClearSearch();
            return;
        }
        
        // Build filter criteria
        const filters = {
            search: searchTerm,
            trainer: $('#filterTrainer').val(),
            sort: $('#sortOption').val()
        };
        
        // Apply filters
        HorseManager.loadHorses(filters);
    },
    
    /**
     * Clear search input
     */
    handleClearSearch: function() {
        $('#searchInput').val('');
        
        // Apply other filters if any
        const filters = {
            trainer: $('#filterTrainer').val(),
            sort: $('#sortOption').val()
        };
        
        HorseManager.loadHorses(filters);
    },
    
    /**
     * Handle trainer filter change
     */
    handleTrainerFilter: function() {
        const trainerFilter = $(this).val();
        const searchTerm = $('#searchInput').val().toLowerCase();
        
        // Build filter criteria
        const filters = {
            search: searchTerm,
            trainer: trainerFilter,
            sort: $('#sortOption').val()
        };
        
        // Apply filters
        HorseManager.loadHorses(filters);
    },
    
    /**
     * Handle sort option change
     */
    handleSortChange: function() {
        const sortOption = $(this).val();
        const searchTerm = $('#searchInput').val().toLowerCase();
        const trainerFilter = $('#filterTrainer').val();
        
        // Build filter criteria
        const filters = {
            search: searchTerm,
            trainer: trainerFilter,
            sort: sortOption
        };
        
        // Apply filters
        HorseManager.loadHorses(filters);
    }
};

// Initialize on document ready
$(document).ready(function() {
    HorseFilters.init();
});