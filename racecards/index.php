<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Cards</title>


    <!-- Link to Sidebar CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">

    <!-- Link to Racecard CSS -->
    <link rel="stylesheet" href="assets/css/racecards.css">

     <!-- Link to Tracker Icon CSS -->
     <link rel="stylesheet" href="assets/css/tracker_icon.css">

     <!-- Add Bootstrap for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
</head>
<body>
    <header>
        <div class="container">
            <h1> Race Cards</h1>
            <p>Race cards for today's racing - <span id="today-date"></span></p>
        </div>
    </header>
    
    <div class="container">
        <div id="race-container">
            <div class="loading">Loading race data...</div>
        </div>
    </div>

    <!-- Add this HTML to your main page where the race cards are displayed -->
<div id="quick-tracker-modal" class="modal">
    <div class="modal-content">
        <span class="close-quick-tracker">&times;</span>
        <h2>Add Horse to Tracker</h2>
        <form id="quick-tracker-form">
            <div class="form-group">
                <label for="quick-horse-name">Horse Name:</label>
                <input type="text" id="quick-horse-name" name="horseName" required>
            </div>
            <div class="form-group">
                <label for="quick-jockey">Jockey:</label>
                <input type="text" id="quick-jockey" name="jockey">
            </div>
            <div class="form-group">
                <label for="quick-trainer">Trainer:</label>
                <input type="text" id="quick-trainer" name="trainer">
            </div>
            <div class="form-group">
                <label for="quick-notes">Notes:</label>
                <textarea id="quick-notes" name="notes" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="quick-notify" name="notify" checked>
                    Notify me about this horse's races
                </label>
            </div>
            <button type="submit" class="btn">Add to Tracker</button>
        </form>
    </div>
</div>

    <?php include_once "../includes/sidebar.php"; ?>


    <script>
        // Display today's date
        const today = new Date();
        document.getElementById('today-date').textContent = today.toLocaleDateString('en-GB', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    </script>
    
    <!-- Include the racecards.js file -->
    <script src="assets/js/racecards.js"></script>

    <!-- Link to sidebar JavaScript -->
    <script src="../assets/js/sidebar.js"></script>

     <!-- Link to Tracker JavaScript -->
     <script src="assets/js/quick_tracker.js"></script>


</body>
</html>