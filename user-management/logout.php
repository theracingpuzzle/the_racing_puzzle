<?php
// logout.php - Log the user out
require_once 'auth.php';

// Log the user out
$result = logoutUser();

// Redirect to the login page
header('Location: login.php?logout=true');
exit;
?>