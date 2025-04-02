
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Tracker</title>
    <meta name="description" content="Track your favorite race horses and receive notifications for upcoming races">
    <link rel="stylesheet" href="assets/css/horse-tracker.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="manifest" href="manifest.json">
</head>
<body>
    <div class="container">
        <header>
            <h1>Horse Tracker</h1>
            <p>Track your favorite race horses and receive notifications for their next races</p>
        </header>

        <main>
            <section class="add-horse-section">
                <h2>Add New Horse</h2>
                <form id="add-horse-form">
                    <div class="form-group">
                        <label for="horse-name">Horse Name:</label>
                        <input type="text" id="horse-name" required>
                    </div>
                    <div class="form-group">
                        <label for="trainer">Trainer:</label>
                        <input type="text" id="trainer">
                    </div>
                    <div class="form-group">
                        <label for="jockey">Jockey:</label>
                        <input type="text" id="jockey">
                    </div>
                    <div class="form-group">
                        <label for="next-race-date">Next Race Date:</label>
                        <input type="datetime-local" id="next-race-date">
                    </div>
                    <div class="form-group">
                        <label for="race-location">Race Location:</label>
                        <input type="text" id="race-location">
                    </div>
                    <div class="form-group">
                        <label for="last-run-notes">Last Run Notes:</label>
                        <textarea id="last-run-notes" rows="4"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notification">Notify for next race:</label>
                        <input type="checkbox" id="notification" checked>
                    </div>
                    <button type="submit" class="btn">Add Horse</button>
                </form>
            </section>

            <section class="tracked-horses-section">
                <h2>Tracked Horses</h2>
                <div class="filters">
                    <input type="text" id="search-horses" placeholder="Search horses...">
                    <select id="sort-by">
                        <option value="name">Sort by Name</option>
                        <option value="date">Sort by Next Race Date</option>
                        <option value="trainer">Sort by Trainer</option>
                        <option value="added">Sort by Date Added</option>
                    </select>
                </div>
                <div class="data-management">
                    <button id="export-data" class="btn secondary-btn">Export Data</button>
                    <button id="import-data" class="btn secondary-btn">Import Data</button>
                    <input type="file" id="import-file" accept=".json" style="display: none;">
                    <button id="view-calendar" class="btn secondary-btn">View Calendar</button>
                </div>
                <div id="horses-container">
                    <!-- Horses will be populated here by JavaScript -->
                </div>
            </section>
            
            <!-- New Calendar Section -->
            <section class="calendar-section" style="display: none;">
                <h2>Race Calendar</h2>
                <div id="calendar-container">
                    <!-- Calendar will be populated here by JavaScript -->
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; Racing Puzzle | <a href="#" id="privacy-policy">Privacy Policy</a></p>
        </footer>
    </div>

    <!-- Notification modal -->
    <div id="notification-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Upcoming Races</h2>
            <div id="upcoming-races-list"></div>
        </div>
    </div>
    
    <!-- Race Results Modal -->
    <div id="race-results-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Race Results for <span id="results-horse-name"></span></h2>
            <div id="race-results-list"></div>
            
            <!-- Add New Result Form -->
            <form id="add-result-form">
                <h3>Add Race Result</h3>
                <div class="form-group">
                    <label for="result-date">Race Date:</label>
                    <input type="date" id="result-date" required>
                </div>
                <div class="form-group">
                    <label for="result-position">Position:</label>
                    <input type="number" id="result-position" min="1" required>
                </div>
                <div class="form-group">
                    <label for="result-venue">Venue:</label>
                    <input type="text" id="result-venue">
                </div>
                <div class="form-group">
                    <label for="result-notes">Notes:</label>
                    <textarea id="result-notes" rows="3"></textarea>
                </div>
                <button type="submit" class="btn">Add Result</button>
            </form>
        </div>
    </div>

    <?php include_once "../includes/sidebar.php"; ?>
    <?php include_once "../includes/footer.php"; ?>

    <script src="assets/js/add-horse.js"></script>
  

    <script src="../assets/js/sidebar.js"></script>
</body>
</html>