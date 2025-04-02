<?php
/**
 * PayPal Donation Page
 * Main entry point for the donation form
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Our Cause - Donate Today</title>
    <link rel="stylesheet" href="assets/css/paypal.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="assets/js/paypal.js" defer></script>

    <link rel="stylesheet" href="../assets/css/sidebar.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Support - The Racing Puzzle</h1>
            <p>Your donation helps us make a difference</p>
        </header>

        <div class="donation-container">
            <div class="donation-options">
                <h2>Choose Donation Amount</h2>
                
                <div class="amount-buttons">
                    <button class="amount-btn" data-amount="5">£5</button>
                    <button class="amount-btn" data-amount="10">£10</button>
                    <button class="amount-btn" data-amount="25">£25</button>
                    <button class="amount-btn" data-amount="50">£50</button>
                    <button class="amount-btn" data-amount="100">£100</button>
                    <button class="amount-btn custom" data-amount="custom">Custom</button>
                </div>
                
                <div id="custom-amount-container" style="display: none;">
                    <div class="input-group">
                        <span class="currency-symbol">£</span>
                        <input type="number" id="custom-amount" min="1" step="1" placeholder="Enter amount">
                    </div>
                </div>
                
                <div class="frequency-selection">
                    <h3>Donation Frequency</h3>
                    <div class="toggle-container">
                        <input type="radio" id="one-time" name="frequency" value="one-time" checked>
                        <label for="one-time">One-time</label>
                        
                        <input type="radio" id="monthly" name="frequency" value="monthly">
                        <label for="monthly">Monthly</label>
                    </div>
                </div>
            </div>
            
            <div class="donor-info">
                <h2>Your Information</h2>
                <form id="donor-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="first-name">First Name</label>
                            <input type="text" id="first-name" name="first-name" required>
                        </div>
                        <div class="form-group">
                            <label for="last-name">Last Name</label>
                            <input type="text" id="last-name" name="last-name" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <input type="checkbox" id="anonymous" name="anonymous">
                        <label for="anonymous">Make my donation anonymous</label>
                    </div>
                    
                    <div class="form-group">
                        <input type="checkbox" id="newsletter" name="newsletter" checked>
                        <label for="newsletter">Sign me up for the newsletter</label>
                    </div>
                </form>
            </div>
            
            <div class="payment-container">
                <h2>Payment Method</h2>
                <div class="payment-options">
                    <button id="paypal-btn" class="payment-btn active">
                        <i class="fab fa-paypal"></i> PayPal
                    </button>
                    <button id="card-btn" class="payment-btn">
                        <i class="far fa-credit-card"></i> Credit Card
                    </button>
                </div>
                
                <div id="paypal-container" class="payment-method-container">
                    <p>You'll be redirected to PayPal to complete your donation.</p>
                    <div id="paypal-button-container"></div>
                </div>
                
                <div id="card-container" class="payment-method-container" style="display:none;">
                    <div class="form-group">
                        <label for="card-number">Card Number</label>
                        <input type="text" id="card-number" placeholder="1234 5678 9012 3456">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry">Expiry Date</label>
                            <input type="text" id="expiry" placeholder="MM/YY">
                        </div>
                        <div class="form-group">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" placeholder="123">
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="donation-summary">
                <h2>Donation Summary</h2>
                <div class="summary-item">
                    <span>Donation Amount:</span>
                    <span id="summary-amount">£25.00</span>
                </div>
                <div class="summary-item">
                    <span>Frequency:</span>
                    <span id="summary-frequency">One-time</span>
                </div>
                <div class="summary-item total">
                    <span>Total:</span>
                    <span id="summary-total">£25.00</span>
                </div>
                
                <button id="donate-btn" class="btn-donate">Complete Donation</button>
                
                <div class="secure-notice">
                    <i class="fas fa-lock"></i> Your information is secure and encrypted
                </div>
            </div>
        </div>
        
        <div class="donation-footer">
            <div class="impact-info">
                <h3>Your Impact</h3>
                <div class="impact-items">
                    <div class="impact-item">
                        <i class="fas fa-hand-holding-heart"></i>
                        <p>£25 provides meals for a family in need</p>
                    </div>
                    <div class="impact-item">
                        <i class="fas fa-book-open"></i>
                        <p>£50 supplies educational materials for children</p>
                    </div>
                    <div class="impact-item">
                        <i class="fas fa-home"></i>
                        <p>£100 helps provide shelter for those experiencing homelessness</p>
                    </div>
                </div>
            </div>
            
            <div class="testimonial">
                <blockquote>
                    "Your donations have made a significant difference in our community. Thank you for your continued support."
                </blockquote>
                <cite>— Community Director</cite>
            </div>
        </div>
    </div>
    
    <!-- Success Modal -->
    <div id="success-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                <h2>Thank You for Your Donation!</h2>
                <p>Your generosity makes our work possible.</p>
                <p>A receipt has been sent to your email.</p>
                <button id="close-success" class="btn-close">Close</button>
            </div>
        </div>
    </div>
    
    
    <footer>
        <div class="footer-content">
            <div class="org-info">
                <h3>Organization Name</h3>
                <p>123 Charity Lane, Nonprofit City, NC 12345</p>
                <p>501(c)(3) Tax ID: 12-3456789</p>
            </div>
            <div class="contact-info">
                <p>Questions? Contact us at <a href="mailto:donate@example.org">donate@example.org</a></p>
                <p>Phone: (555) 123-4567</p>
            </div>
            <div class="social-links">
                <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        <div class="copyright">
            <p>&copy; <?php echo date('Y'); ?> Organization Name. All rights reserved.</p>
        </div>
    </footer>

    <?php include_once "../includes/sidebar.php"; ?>
    
    <!-- Add PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=USD"></script>
</body>
</html>