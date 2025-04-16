<?php
/**
 * Improved Sidebar component for The Racing Puzzle website
 * Works with the updated sidebar.js and sidebar.css
 */

// Get current page for highlighting active menu item
$currentDirectory = basename(dirname($_SERVER['PHP_SELF']));
?>
<button id="mobileToggle" class="mobile-toggle" aria-label="Toggle menu">
    <i class="fas fa-bars"></i>
</button>

<div id="mobileOverlay" class="mobile-overlay"></div>

<div class="wrapper">
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar" role="navigation" aria-label="Main Navigation">
        <div class="header-container">
            <button id="toggleSidebar" aria-label="Toggle sidebar width">
                <i id="toggleIcon" class="fas fa-chevron-left"></i>
            </button>
            <img src="../assets/images/logo.png" alt="The Racing Puzzle Logo" class="logo">
            <span class="title">The Racing Puzzle</span>
        </div>
        
        <nav>
            <div class="nav-item">
                <a href="../dashboard" class="<?php echo ($currentDirectory == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-puzzle-piece"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../horse-tracker" class="<?php echo ($currentDirectory == 'horse-tracker') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-binoculars"></i>
                    <span class="nav-text">Tracker</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../bet-record" class="<?php echo ($currentDirectory == 'bet-record') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-database"></i>
                    <span class="nav-text">Record</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../racecards" class="<?php echo ($currentDirectory == 'racecards') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-newspaper"></i>
                    <span class="nav-text">Racecards</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../results" class="<?php echo ($currentDirectory == 'results') ? 'active' : ''; ?>">
                    <i class="fa-solid fa-flag-checkered"></i>
                    <span class="nav-text">Results</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../leagues" class="<?php echo ($currentDirectory == 'leagues') ? 'active' : ''; ?>">
                    <i class="fas fa-trophy"></i>
                    <span class="nav-text">Leagues</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../donate" class="<?php echo ($currentDirectory == 'donate') ? 'active' : ''; ?>">
                    <i class="fa-brands fa-paypal"></i>
                    <span class="nav-text">Donate</span>
                </a>
            </div>
            
            <div class="nav-item">
                <a href="../index.html">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="nav-text">Logout</span>
                </a>
            </div>
        </nav>
        
        <!-- You could add a footer here if needed -->
    </aside>

    <!-- Main content container -->
    <main id="mainContent" class="content">
        <!-- Page content will be placed here -->