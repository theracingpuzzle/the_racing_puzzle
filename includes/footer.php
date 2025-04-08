<?php
// Detect the current page
$page = basename($_SERVER['SCRIPT_NAME'], ".php");

// Define footer CSS based on the page
$footerCSS = "../assets/css/footer.css"; // Default footer style
if ($page === "dashboard") {
    $footerCSS = "../assets/css/footer.css";
} elseif ($page === "horse-tracker") {
    $footerCSS = "/assets/css/horse-footer.css";
}
?>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<footer class="site-footer">
    <div class="footer-background">
        <!-- Overlay to make text readable -->
        <div class="footer-overlay"></div>

        <div class="footer-container">
            <!-- Left Section -->
            <div class="footer-left">
                <p>&copy; <?php echo date("Y"); ?> The Racing Puzzle. All rights reserved.</p>
            </div>
            
            <!-- Center Section -->
            <div class="footer-center">
                <nav class="footer-nav">
                    <ul>
                        <li><a href="/about-us.php">About Us</a></li>
                        <li><a href="/privacy-policy.php">Privacy Policy</a></li>
                        <li><a href="/terms-of-service.php">Terms of Service</a></li>
                        <li><a href="/contact.php">Contact</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Right Section - Social Media -->
            <div class="footer-right">
                <p class="follow-us-text">Follow us on social media:</p>
                <div class="social-icons">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Load footer-specific CSS dynamically -->
<link rel="stylesheet" href="<?php echo $footerCSS; ?>">
