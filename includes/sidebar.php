<?php
/**
 * Sidebar component for the website
 * Works with sidebar.js and sidebar.css
 */
?>
<div class="wrapper">
    <!-- Sidebar -->
    <div id="sidebar" class="sidebar">
        <div style="display: flex; align-items: center; margin-bottom: 30px;">
            <img src="../assets/images/logo.png" alt="Logo" class="logo">
            <span id="sidebarTitle">The Racing Puzzle</span>
        </div>
        
        <button id="toggleSidebar">
            <i class="fas fa-bars"></i>
        </button>
        
        <nav>
            <a href="../dashboard" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'dashboard') ? 'active' : ''; ?>">
                <i class="fa solid fa-puzzle-piece"></i>
                <span class="title">Dashboard</span>
            </a>
            <a href="../horse-tracker" class="<?php echo (basename(dirname($_SERVER['PHP_SELF']))== 'horse-tracker') ? 'active' : ''; ?>">
                <i class="fa solid fa-binoculars"></i>
                <span class="title">Tracker</span>
            </a>
            <a href="../bet-record" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'bet-record') ? 'active' : ''; ?>">
                <i class="fa solid fa-database"></i>
                <span class="title">Record</span>
            </a>
            <a href="../racecards" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'racecards') ? 'active' : ''; ?>">
            <i class="fa-solid fa-newspaper"></i>
                <span class="title">Racecards</span>
            </a>
            <a href="../results" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'results') ? 'active' : ''; ?>">
            <i class="fa-solid fa-flag-checkered"></i>
                <span class="title">Results</span>
            </a>
            <a href="../leagues" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'leagues') ? 'active' : ''; ?>">
                <i class="fas fa-trophy"></i>
                <span class="title">Leagues</span>
            </a>
            <a href="../donate" class="<?php echo (basename(dirname($_SERVER['PHP_SELF'])) == 'donate') ? 'active' : ''; ?>">
                <i class="fa-brands fa-paypal"></i>
                <span class="title">Donate</span>
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span class="title">Logout</span>
            </a>
        </nav>
    </div>

    <!-- Main content container -->
    <div id="mainContent" class="content">
        <!-- Your page content goes here -->

        