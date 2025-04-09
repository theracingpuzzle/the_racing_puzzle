<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Cards</title>


    <!-- Link to Sidebar CSS -->
    <link rel="stylesheet" href="../assets/css/sidebar.css">

     <!-- Add Bootstrap for layout and styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
   
    <style>
        :root {
            --primary-color: #1a5fb4;
            --secondary-color: #26a269;
            --accent-color: #ff7800;
            --dark-bg: #1c1c1c;
            --light-bg: #f9f9f9;
            --medium-bg: #f0f0f0;
            --border-color: #e0e0e0;
            --text-dark: #333333;
            --text-light: #ffffff;
            --text-medium: #666666;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 8px rgba(0, 0, 0, 0.12);
            --radius-sm: 4px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --transition-speed: 0.3s;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
            background-color: var(--light-bg);
            padding: 0;
            margin: 0;
        }
        
        .container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        header {
            background-color: var(--primary-color);
            color: var(--text-light);
            padding: 30px 0;
            text-align: center;
            box-shadow: var(--shadow-md);
            margin-bottom: 30px;
        }
        
        header h1 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            font-size: 2.6rem;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        
        header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        #race-container {
            padding: 0 10px 40px;
        }
        
        /* Course Container Styles */
        .course-container {
            margin-bottom: 25px;
            box-shadow: var(--shadow-sm);
            border-radius: var(--radius-md);
            overflow: hidden;
        }
        
        .course-header {
            background: linear-gradient(135deg, var(--dark-bg), #2c2c2c);
            color: var(--text-light);
            padding: 15px 20px;
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            user-select: none;
            transition: all var(--transition-speed) ease;
            border-left: 5px solid var(--accent-color);
        }
        
        .course-header:hover {
            background: linear-gradient(135deg, #2c2c2c, #3c3c3c);
        }
        
        .course-header h2 {
            margin: 0;
            font-family: 'Montserrat', sans-serif;
            font-size: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .toggle-icon {
            font-size: 24px;
            font-weight: bold;
            transition: transform var(--transition-speed) ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .course-header.expanded .toggle-icon {
            transform: rotate(180deg);
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .races-container {
            background-color: white;
            transition: all var(--transition-speed) ease;
            overflow: hidden;
            border: 1px solid var(--border-color);
            border-top: none;
            border-bottom-left-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }
        
        /* Race Card Styles */
        .race-card {
            margin: 15px;
            border-radius: var(--radius-md);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            background-color: white;
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .race-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .race-header {
            background: linear-gradient(to right, var(--primary-color), #2a6fc9);
            color: var(--text-light);
            padding: 15px 20px;
            position: relative;
        }
        
        .race-header h3 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.4rem;
            margin: 0;
            font-weight: 600;
        }
        
        .race-header h4 {
            font-size: 1.1rem;
            font-weight: 400;
            margin: 5px 0 0;
            opacity: 0.9;
        }
        
        .race-info {
            display: flex;
            flex-wrap: wrap;
            gap: 25px;
            padding: 20px;
            background-color: var(--medium-bg);
            border-bottom: 1px solid var(--border-color);
        }
        
        .race-info div {
            flex: 1;
            min-width: 220px;
        }
        
        .race-info p {
            margin: 8px 0;
            line-height: 1.5;
        }
        
        .race-info strong {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        /* Runners Table Styles */
        .race-runners-container {
            padding: 15px;
            overflow-x: auto;
        }
        
        .race-runners {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.95rem;
        }
        
        .race-runners thead {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        
        .race-runners th {
            background-color: var(--primary-color);
            color: var(--text-light);
            text-align: left;
            padding: 12px 15px;
            font-weight: 500;
            position: relative;
        }
        
        .race-runners th:first-child {
            border-top-left-radius: var(--radius-sm);
        }
        
        .race-runners th:last-child {
            border-top-right-radius: var(--radius-sm);
        }
        
        .race-runners td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .race-runners tr:last-child td {
            border-bottom: none;
        }
        
        .race-runners tr:nth-child(even) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .race-runners tr:hover {
            background-color: rgba(26, 95, 180, 0.05);
        }
        
        .horse-name {
            font-weight: 500;
            color: var(--primary-color);
        }
        
        .headgear {
            font-style: italic;
            color: var(--text-medium);
            font-size: 0.85em;
            margin-left: 5px;
        }
        
        .jockey {
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .comment {
            font-size: 0.9em;
            max-width: 400px;
            color: var(--text-medium);
            line-height: 1.5;
        }
        
        .headgear-legend {
            padding: 12px 20px;
            font-size: 0.85em;
            background-color: var(--light-bg);
            border-top: 1px solid var(--border-color);
            color: var(--text-medium);
            border-bottom-left-radius: var(--radius-md);
            border-bottom-right-radius: var(--radius-md);
        }
        
        /* Loading State */
        .loading {
            text-align: center;
            padding: 40px 0;
            color: var(--text-medium);
            font-size: 1.1rem;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            header {
                padding: 20px 0;
            }
            
            header h1 {
                font-size: 2rem;
            }
            
            .course-header h2 {
                font-size: 1.3rem;
            }
            
            .race-header h3 {
                font-size: 1.2rem;
            }
            
            .race-header h4 {
                font-size: 1rem;
            }
            
            .race-info {
                padding: 15px;
                gap: 15px;
            }
            
            .race-info div {
                min-width: 100%;
            }
            
            .race-runners td, 
            .race-runners th {
                padding: 10px;
            }
            
            .comment {
                max-width: 250px;
            }
        }
        
        /* Mobile Optimizations */
        @media (max-width: 480px) {
            .container {
                padding: 0 10px;
            }
            
            header h1 {
                font-size: 1.8rem;
            }
            
            .course-header {
                padding: 12px 15px;
            }
            
            .toggle-icon {
                width: 24px;
                height: 24px;
                font-size: 20px;
            }
        }

        .silks-cell {
    width: 40px;
    text-align: center;
}

.silks-image {
    width: 52px;
    height: 52px;
    object-fit: contain;
    display: block;
    margin: 0 auto;
}

.no-silks {
    font-size: 12px;
    color: #999;
    text-align: center;
}

/* If you want a hover effect to show larger silks */
.silks-cell:hover .silks-image {
    position: absolute;
    width: 64px;
    height: 64px;
    z-index: 100;
    transform: translateX(-16px) translateY(-16px);
    border: 1px solid #ccc;
    background-color: white;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}
    </style>
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


</body>
</html>