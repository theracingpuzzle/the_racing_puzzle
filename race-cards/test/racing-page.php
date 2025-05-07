<?php
// racing-page.php
// require_once '../user-management/auth.php';
// requireLogin();
// ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horse Racing Cards</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <link rel="stylesheet" href="racing-page.css">
    <link rel="stylesheet" href="date-slider.css">
    <link rel="stylesheet" href="course-list.css">
    <link rel="stylesheet" href="race-slider.css">
    <link rel="stylesheet" href="runners-grid.css">
</head>
<body>
    <?php 
    // Get date from URL parameter or use today's date
    $requestedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
    include 'race-data-loader.php'; 
    ?>

    <?php include '../includes/app-header.php'; ?>

    <!-- Date Navigation -->
    <?php include 'date-slider.php'; ?>

    <!-- Course Filters -->
    <?php include 'course-filters.php'; ?>

    <main class="container">
        <!-- Course List Section -->
        <section id="course-list-section">
            <?php include 'course-list.php'; ?>
        </section>
    </main>

    <!-- Modals -->
    <?php include 'tracker-modal.php'; ?>

    <?php include '../includes/bottom-nav.php'; ?>

    <script src="date-slider.js"></script>
    <script src="course-list.js"></script>
    <script src="race-slider.js"></script>
    <script src="runners-grid.js"></script>
</body>
</html>