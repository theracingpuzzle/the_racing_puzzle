<?php
$currentDirectory = basename(dirname($_SERVER['PHP_SELF']));
?>

<link rel="stylesheet" href="bottom-nav.css" />
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@700&family=Roboto+Mono&family=Source+Sans+Pro:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="nav-container">
  <nav class="bottom-nav">
    <a href="../dashboard" class="nav-item <?= ($currentDirectory == 'dashboard') ? 'active' : '' ?>">
      <i class="fas fa-calendar-day nav-icon"></i>
      <span>Dashboard</span>
    </a>
    <a href="../horse-tracker" class="nav-item <?= ($currentDirectory == 'horse-tracker') ? 'active' : '' ?>">
      <i class="fas fa-star nav-icon"></i>
      <span>My Tracker</span>
    </a>
    <a href="../bet-record" class="nav-item <?= ($currentDirectory == 'bet-record') ? 'active' : '' ?>">
      <i class="fas fa-chart-line nav-icon"></i>
      <span>My Results</span>
    </a>
    <a href="../race-cards" class="nav-item <?= ($currentDirectory == 'race-cards') ? 'active' : '' ?>">
      <i class="fas fa-list-ul nav-icon"></i>
      <span>Racecards</span>
    </a>
    <a href="../leagues" class="nav-item <?= ($currentDirectory == 'leagues') ? 'active' : '' ?>">
      <i class="fas fa-trophy nav-icon"></i>
      <span>My Leagues</span>
    </a>
  </nav>
</div>
