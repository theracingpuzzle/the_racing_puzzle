<?php
// dashboard/index.php
require_once '../user-management/auth.php'; // Adjust path as needed
requireLogin();

// Continue with the rest of your dashboard code
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support The Racing Puzzle - Donate Today</title>
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- <link rel="stylesheet" href="../assets/css/sidebar.css"> -->
    <script src="assets/js/paypal.js" defer></script>
    <style>
        /* Additional PayPal donation page specific styles */
        .donation-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 30px 0;
        }
        
        .amount-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .amount-btn {
            background-color: var(--medium-bg);
            color: var(--text-dark);
            border: 1px solid var(--border-color);
            padding: 10px 20px;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition-speed);
        }
        
        .amount-btn:hover, .amount-btn.active {
            background-color: var(--primary-color);
            color: var(--text-light);
        }
        
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        
        .currency-symbol {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: bold;
            color: var(--text-medium);
        }
        
        .input-group input {
            padding-left: 30px;
        }
        
        .frequency-selection {
            margin: 20px 0;
        }
        
        .toggle-container {
            display: flex;
            background-color: var(--medium-bg);
            border-radius: var(--radius-md);
            overflow: hidden;
            margin-top: 10px;
        }
        
        .toggle-container input[type="radio"] {
            display: none;
        }
        
        .toggle-container label {
            flex: 1;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: background-color var(--transition-speed);
        }
        
        .toggle-container input[type="radio"]:checked + label {
            background-color: var(--primary-color);
            color: var(--text-light);
        }
        
        .payment-options {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .payment-btn {
            flex: 1;
            padding: 12px;
            border-radius: var(--radius-md);
            background-color: var(--medium-bg);
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all var(--transition-speed);
            font-weight: 600;
        }
        
        .payment-btn.active {
            background-color: var(--primary-color);
            color: var(--text-light);
        }
        
        .btn-donate {
            width: 100%;
            padding: 15px;
            background: linear-gradient(to right, var(--primary-color), #267b42);
            color: white;
            border: none;
            border-radius: var(--radius-md);
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            margin: 20px 0;
            transition: transform var(--transition-speed);
        }
        
        .btn-donate:hover {
            transform: translateY(-2px);
        }
        
        .secure-notice {
            text-align: center;
            color: var(--text-medium);
            margin-top: 10px;
        }
        
        .donation-summary {
            background-color: white;
            padding: 20px;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-md);
        }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .summary-item.total {
            font-weight: bold;
            font-size: 1.2rem;
            border-bottom: none;
            margin-top: 20px;
        }
        
        .impact-items {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }
        
        .impact-item {
            text-align: center;
            padding: 15px;
            background-color: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
        }
        
        .impact-item i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .testimonial {
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            border-radius: var(--radius-md);
            margin-top: 30px;
        }
        
        .testimonial blockquote {
            font-style: italic;
            margin-bottom: 10px;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        
        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 30px;
            border-radius: var(--radius-md);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            width: 500px;
            max-width: 90%;
            position: relative;
            text-align: center;
        }
        
        .success-message i {
            font-size: 4rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .close-modal {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .btn-close {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="app-header">
            <div class="header-content">
                <div class="logo-container">
                    <img src="../assets/images/racing-logo.png" alt="The Racing Puzzle Logo">
                    <h1>The Racing Puzzle</h1>
                </div>
                <div class="header-actions">
                    <button class="action-button">
                        <i class="fas fa-search"></i>
                    </button>
                    <button class="action-button">
                        <i class="fas fa-user"></i>
                    </button>
                    <button class="action-button primary">
                        <i class="fas fa-heart"></i>
                    </button>
                </div>
            </div>
        </header>

        <div class="card mt-20">
            <div class="card-header">
                <h2>Support The Racing Puzzle</h2>
                <span class="badge badge-success">Secure Donation</span>
            </div>
            
            <div class="card-body">
                <p>Your donation helps us continue to provide the best horse racing content and maintain our community platform.</p>
                
                <div class="donation-container">
                    <div class="card">
                        <div class="card-header">
                            <h3>Choose Donation Amount</h3>
                        </div>
                        <div class="card-body">
                            <div class="amount-buttons">
                                <button class="amount-btn" data-amount="5">£5</button>
                                <button class="amount-btn active" data-amount="10">£10</button>
                                <button class="amount-btn" data-amount="25">£25</button>
                                <button class="amount-btn" data-amount="50">£50</button>
                                <button class="amount-btn" data-amount="100">£100</button>
                                <button class="amount-btn" data-amount="custom">Custom</button>
                            </div>
                            
                            <div id="custom-amount-container" style="display: none;">
                                <div class="input-group">
                                    <span class="currency-symbol">£</span>
                                    <input type="number" id="custom-amount" min="1" step="1" placeholder="Enter amount">
                                </div>
                            </div>
                            
                            <div class="frequency-selection">
                                <h4>Donation Frequency</h4>
                                <div class="toggle-container">
                                    <input type="radio" id="one-time" name="frequency" value="one-time" checked>
                                    <label for="one-time">One-time</label>
                                    
                                    <input type="radio" id="monthly" name="frequency" value="monthly">
                                    <label for="monthly">Monthly</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Your Information</h3>
                        </div>
                        <div class="card-body">
                            <form id="donor-form">
                                <div class="form-group">
                                    <label for="first-name">First Name</label>
                                    <input type="text" id="first-name" name="first-name" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="last-name">Last Name</label>
                                    <input type="text" id="last-name" name="last-name" required>
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
                                    <label for="newsletter">Sign me up for The Racing Puzzle newsletter</label>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h3>Payment Method</h3>
                        </div>
                        <div class="card-body">
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
                                
                                <div class="d-flex gap-10">
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
                            
                            <div class="donation-summary">
                                <h4>Donation Summary</h4>
                                <div class="summary-item">
                                    <span>Donation Amount:</span>
                                    <span id="summary-amount">£10.00</span>
                                </div>
                                <div class="summary-item">
                                    <span>Frequency:</span>
                                    <span id="summary-frequency">One-time</span>
                                </div>
                                <div class="summary-item total">
                                    <span>Total:</span>
                                    <span id="summary-total">£10.00</span>
                                </div>
                                
                                <button id="donate-btn" class="btn-donate">Complete Donation</button>
                                
                                <div class="secure-notice">
                                    <i class="fas fa-lock"></i> Your information is secure and encrypted
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-20">
                    <div class="card-header">
                        <h3>Your Impact</h3>
                    </div>
                    <div class="card-body">
                        <div class="impact-items">
                            <div class="impact-item">
                                <i class="fas fa-trophy"></i>
                                <p>£10 helps us cover race analysis and expert insights</p>
                            </div>
                            <div class="impact-item">
                                <i class="fas fa-database"></i>
                                <p>£25 supports our comprehensive racing database maintenance</p>
                            </div>
                            <div class="impact-item">
                                <i class="fas fa-users"></i>
                                <p>£50 enables community features and racing enthusiast events</p>
                            </div>
                        </div>
                        
                        <div class="testimonial mt-20">
                            <blockquote>
                                "The Racing Puzzle has transformed how I follow horse racing. Your donations ensure we can keep enjoying this invaluable resource."
                            </blockquote>
                            <cite>— John Smith, Racing Enthusiast</cite>
                        </div>
                    </div>
                </div>
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
                <p>Your generosity helps support The Racing Puzzle community.</p>
                <p>A receipt has been sent to your email.</p>
                <button id="close-success" class="btn-close">Close</button>
            </div>
        </div>
    </div>
    
    <footer>
        <div class="container">
            <div class="d-flex justify-between flex-wrap gap-20">
                <div class="org-info">
                    <h3>The Racing Puzzle</h3>
                    <p>123 Racing Lane, Newmarket, UK NM1 2RH</p>
                    <p>Registered Charity: 12-3456789</p>
                </div>
                <div class="contact-info">
                    <p>Questions? Contact us at <a href="mailto:support@theracingpuzzle.com">support@theracingpuzzle.com</a></p>
                    <p>Phone: +44 (123) 456-7890</p>
                </div>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
            <div class="copyright text-center mt-20">
                <p>&copy; <?php echo date('Y'); ?> The Racing Puzzle. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <?php include '../test/bottom-nav.php'; ?>

    
    <!-- Add PayPal SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&currency=GBP"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial selected amount
            const defaultAmount = 10;
            let selectedAmount = defaultAmount;
            let donationFrequency = 'one-time';
            
            // Amount buttons
            const amountBtns = document.querySelectorAll('.amount-btn');
            const customAmountContainer = document.getElementById('custom-amount-container');
            const customAmountInput = document.getElementById('custom-amount');
            
            // Payment method buttons
            const paypalBtn = document.getElementById('paypal-btn');
            const cardBtn = document.getElementById('card-btn');
            const paypalContainer = document.getElementById('paypal-container');
            const cardContainer = document.getElementById('card-container');
            
            // Summary elements
            const summaryAmount = document.getElementById('summary-amount');
            const summaryFrequency = document.getElementById('summary-frequency');
            const summaryTotal = document.getElementById('summary-total');
            
            // Modal elements
            const donateBtn = document.getElementById('donate-btn');
            const successModal = document.getElementById('success-modal');
            const closeModal = document.querySelector('.close-modal');
            const closeSuccess = document.getElementById('close-success');
            
            // Frequency radios
            const frequencyRadios = document.querySelectorAll('input[name="frequency"]');
            
            // Update summary with initial values
            updateSummary();
            
            // Amount button click handlers
            amountBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    amountBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const amount = this.getAttribute('data-amount');
                    if (amount === 'custom') {
                        customAmountContainer.style.display = 'block';
                        selectedAmount = customAmountInput.value || defaultAmount;
                    } else {
                        customAmountContainer.style.display = 'none';
                        selectedAmount = parseInt(amount);
                    }
                    
                    updateSummary();
                });
            });
            
            // Custom amount input handler
            customAmountInput.addEventListener('input', function() {
                selectedAmount = this.value || defaultAmount;
                updateSummary();
            });
            
            // Frequency change handler
            frequencyRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    donationFrequency = this.value;
                    updateSummary();
                });
            });
            
            // Payment method handlers
            paypalBtn.addEventListener('click', function() {
                paypalBtn.classList.add('active');
                cardBtn.classList.remove('active');
                paypalContainer.style.display = 'block';
                cardContainer.style.display = 'none';
            });
            
            cardBtn.addEventListener('click', function() {
                cardBtn.classList.add('active');
                paypalBtn.classList.remove('active');
                cardContainer.style.display = 'block';
                paypalContainer.style.display = 'none';
            });
            
            // Donate button handler
            donateBtn.addEventListener('click', function(e) {
                e.preventDefault();
                // In a real implementation, validate form and process payment here
                successModal.style.display = 'block';
            });
            
            // Modal close handlers
            closeModal.addEventListener('click', function() {
                successModal.style.display = 'none';
            });
            
            closeSuccess.addEventListener('click', function() {
                successModal.style.display = 'none';
            });
            
            // Update summary function
            function updateSummary() {
                summaryAmount.textContent = `£${selectedAmount}.00`;
                summaryFrequency.textContent = donationFrequency === 'one-time' ? 'One-time' : 'Monthly';
                summaryTotal.textContent = `£${selectedAmount}.00`;
            }
            
            // Close modal when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === successModal) {
                    successModal.style.display = 'none';
                }
            });
            
            // Initialize PayPal buttons
            // Note: In a real implementation, you would add your PayPal integration code here
        });
    </script>
</body>
</html>