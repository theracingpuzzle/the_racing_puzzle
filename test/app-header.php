<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? null;
?>

<!-- app-header.php -->

<header class="app-header">
  <div class="container">
    <div class="header-content d-flex justify-content-between align-items-center">
      
      <!-- Logo -->
      <div class="logo-container d-flex align-items-center">
        <img src="../assets/images/logo.png" alt="Race Cards Logo" class="me-2" style="height: 40px;">
        <h1 class="mb-0 h4">The Racing Puzzle</h1>
      </div>

      <!-- Actions -->
      <div class="header-actions d-flex align-items-center gap-3">
        <button class="action-button" title="Notifications" aria-label="Notifications">
          <i class="fas fa-bell"></i>
        </button>
        <button class="action-button" title="My Tracker" aria-label="My Tracker">
          <i class="fas fa-star"></i>
        </button>

        <!-- Profile Button -->
<a href="../user-management/profile.php" class="action-button user-action" aria-label="Profile">
  <i class="fas fa-user me-1"></i>
  <?php if ($username): ?>
    <span class="username-display"><?php echo htmlspecialchars($username); ?></span>
  <?php endif; ?>
</a>

        <!-- Logout Button -->
        <a href="../user-management/logout.php" class="action-button user-action" aria-label="Logout">
          <i class="fas fa-sign-out-alt me-1"></i>
        </a>
      </div>
    </div>
  </div>
</header>


       
