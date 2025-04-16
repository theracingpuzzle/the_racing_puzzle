<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Tracker</title>
    <meta name="description" content="Track your favorite race horses and receive notifications for upcoming races">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --primary-dark: #2980b9;
            --secondary-color: #27ae60;
            --secondary-dark: #219653;
            --danger-color: #e74c3c;
            --warning-color: #f1c40f;
            --text-color: #333;
            --light-text: #7f8c8d;
            --light-bg: #f5f5f5;
            --white: #ffffff;
            --card-shadow: 0 2px 15px rgba(0,0,0,0.1);
            --border-radius: 8px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: var(--text-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        header {
            text-align: center;
            padding: 30px 0;
            background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
            color: var(--white);
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--card-shadow);
        }

        header h1 {
            margin-bottom: 10px;
            font-size: 2.5rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
        }

        .btn {
            background-color: var(--secondary-color);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: var(--transition);
        }

        .btn:hover {
            background-color: var(--secondary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .secondary-btn {
            background-color: var(--primary-color);
        }

        .secondary-btn:hover {
            background-color: var(--primary-dark);
        }

        .horse-list-section {
            background-color: var(--white);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }

        .list-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filters {
            display: flex;
            gap: 15px;
            flex-grow: 1;
            flex-wrap: wrap;
        }

        #search-horses {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            min-width: 200px;
        }

        #sort-by {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
        }

        .data-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .horse-item {
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            margin-bottom: 15px;
            overflow: hidden;
            background-color: #f9f9f9;
            transition: var(--transition);
        }

        .horse-item:hover {
            box-shadow: var(--card-shadow);
        }

        .horse-header {
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            background-color: #f9f9f9;
            border-bottom: 1px solid #eee;
            transition: var(--transition);
        }

        .horse-header:hover {
            background-color: #f0f0f0;
        }

        .horse-header h3 {
            margin: 0;
            font-size: 1.2rem;
            color: var(--primary-dark);
        }

        .horse-header .race-date {
            color: var(--danger-color);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .horse-details {
            padding: 0;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.5s ease, padding 0.3s ease;
        }

        .horse-details.active {
            padding: 20px;
            max-height: 1000px;
        }

        .notes-section {
            margin-top: 15px;
            padding: 15px;
            background-color: #fff9e6;
            border-left: 4px solid var(--warning-color);
            border-radius: 4px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            flex-wrap: wrap;
        }

        .edit-btn {
            background-color: var(--primary-color);
            color: var(--white);
        }

        .delete-btn {
            background-color: var(--danger-color);
            color: var(--white);
        }

        .results-btn {
            background-color: #9b59b6;
            color: var(--white);
        }

        .notification-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--secondary-color);
        }

        input:focus + .slider {
            box-shadow: 0 0 1px var(--secondary-color);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: var(--white);
            margin: 5% auto;
            padding: 30px;
            border-radius: var(--border-radius);
            width: 90%;
            max-width: 600px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            position: relative;
            animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
            from {opacity: 0; transform: translateY(-50px);}
            to {opacity: 1; transform: translateY(0);}
        }

        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 28px;
            cursor: pointer;
            color: var(--light-text);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }

        @media (max-width: 768px) {
            .list-controls {
                flex-direction: column;
                align-items: stretch;
            }
            
            .filters {
                flex-direction: column;
            }
            
            .data-actions {
                justify-content: space-between;
            }
            
            .action-buttons button {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<?php include_once "../includes/menu_bar.php"; ?>

<body>
    <div class="container">
        <header>
            <h1>Horse Tracker</h1>
            <p>Track your favorite race horses and receive notifications for upcoming races</p>
        </header>

        <main>
            <div class="horse-list-section">
                <div class="list-controls">
                    <div class="filters">
                        <input type="text" id="search-horses" placeholder="Search horses...">
                        <select id="sort-by">
                            <option value="name">Sort by Name</option>
                            <option value="date">Sort by Next Race Date</option>
                            <option value="added">Sort by Date Added</option>
                        </select>
                    </div>
                    <div class="data-actions">
                        <button id="add-horse-btn" class="btn"><i class="fas fa-plus"></i> Add Horse</button>
                        <button id="view-calendar" class="btn secondary-btn"><i class="fas fa-calendar"></i> Calendar</button>
                    </div>
                </div>
                
                <div id="horses-list">
                    <!-- Sample horse item (will be generated by JS) -->
                    <div class="horse-item">
                        <div class="horse-header">
                            <h3>Midnight Thunder</h3>
                            <div class="race-date">Next race: Apr 20, 2025</div>
                        </div>
                        <div class="horse-details">
                            <div>
                                <p><strong>Race Location:</strong> Churchill Downs</p>
                                <div class="notes-section">
                                    <h4>Last Run Notes:</h4>
                                    <p>Strong finish in the final stretch. Needs to improve starting position.</p>
                                </div>
                                <div class="notification-toggle">
                                    <span>Notify for next race:</span>
                                    <label class="switch">
                                        <input type="checkbox" checked>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <div class="action-buttons">
                                    <button class="btn edit-btn"><i class="fas fa-edit"></i> Edit</button>
                                    <button class="btn results-btn"><i class="fas fa-trophy"></i> Results</button>
                                    <button class="btn delete-btn"><i class="fas fa-trash"></i> Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Additional horse items will be added by JavaScript -->
                </div>
            </div>
            
            <!-- Calendar Section (Hidden by default) -->
            <section class="calendar-section" style="display: none;">
                <h2>Race Calendar</h2>
                <div id="calendar-container">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; Racing Puzzle | <a href="#" id="privacy-policy">Privacy Policy</a></p>
        </footer>
    </div>

    <!-- Add Horse Modal -->
    <div id="add-horse-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Add New Horse</h2>
            <form id="add-horse-form">
                <div class="form-group">
                    <label for="horse-name">Horse Name:</label>
                    <input type="text" id="horse-name" required>
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
                    <label for="notification">
                        <input type="checkbox" id="notification" checked>
                        Notify for next race
                    </label>
                </div>
                <button type="submit" class="btn">Save Horse</button>
            </form>
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
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle horse details when clicking on header
            const horseHeaders = document.querySelectorAll('.horse-header');
            horseHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    const details = this.nextElementSibling;
                    details.classList.toggle('active');
                });
            });

            // Modal functionality
            const addHorseBtn = document.getElementById('add-horse-btn');
            const addHorseModal = document.getElementById('add-horse-modal');
            const closeButtons = document.querySelectorAll('.close-modal');

            addHorseBtn.addEventListener('click', function() {
                addHorseModal.style.display = 'block';
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.modal').style.display = 'none';
                });
            });

            // Close modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) {
                    event.target.style.display = 'none';
                }
            });

            // Calendar toggle
            const calendarBtn = document.getElementById('view-calendar');
            const horseListSection = document.querySelector('.horse-list-section');
            const calendarSection = document.querySelector('.calendar-section');

            calendarBtn.addEventListener('click', function() {
                if (calendarSection.style.display === 'none') {
                    horseListSection.style.display = 'none';
                    calendarSection.style.display = 'block';
                    calendarBtn.innerHTML = '<i class="fas fa-list"></i> List View';
                } else {
                    calendarSection.style.display = 'none';
                    horseListSection.style.display = 'block';
                    calendarBtn.innerHTML = '<i class="fas fa-calendar"></i> Calendar';
                }
            });

            // Here you would add code to handle form submissions, filtering, sorting, etc.
        });
    </script>
</body>
</html>

