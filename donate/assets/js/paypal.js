/**
 * PayPal Donation Page JavaScript
 * Handles all interactive elements of the donation form
 */

document.addEventListener('DOMContentLoaded', function() {
    // Default values
    let selectedAmount = 25;
    let donationFrequency = 'one-time';
    let paymentMethod = 'paypal';
    
    // Elements
    const amountButtons = document.querySelectorAll('.amount-btn');
    const customAmountContainer = document.getElementById('custom-amount-container');
    const customAmountInput = document.getElementById('custom-amount');
    const frequencyInputs = document.querySelectorAll('input[name="frequency"]');
    const paypalBtn = document.getElementById('paypal-btn');
    const cardBtn = document.getElementById('card-btn');
    const paypalContainer = document.getElementById('paypal-container');
    const cardContainer = document.getElementById('card-container');
    const donateBtn = document.getElementById('donate-btn');
    const modal = document.getElementById('success-modal');
    const closeModal = document.querySelector('.close-modal');
    const closeSuccessBtn = document.getElementById('close-success');
    
    // Summary elements
    const summaryAmount = document.getElementById('summary-amount');
    const summaryFrequency = document.getElementById('summary-frequency');
    const summaryTotal = document.getElementById('summary-total');
    
    // Initialize PayPal Button
    initPayPalButton();
    
    // Set initial selected amount
    setSelectedAmount(25);
    
    // Amount button click handlers
    amountButtons.forEach(button => {
        button.addEventListener('click', function() {
            const amount = this.getAttribute('data-amount');
            
            // Remove selected class from all buttons
            amountButtons.forEach(btn => btn.classList.remove('selected'));
            
            // Add selected class to clicked button
            this.classList.add('selected');
            
            if (amount === 'custom') {
                customAmountContainer.style.display = 'block';
                customAmountInput.focus();
                if (customAmountInput.value) {
                    setSelectedAmount(parseFloat(customAmountInput.value));
                }
            } else {
                customAmountContainer.style.display = 'none';
                setSelectedAmount(parseFloat(amount));
            }
        });
    });
    
    // Custom amount input handler
    customAmountInput.addEventListener('input', function() {
        if (this.value && !isNaN(this.value)) {
            setSelectedAmount(parseFloat(this.value));
        }
    });
    
    // Frequency selection handler
    frequencyInputs.forEach(input => {
        input.addEventListener('change', function() {
            donationFrequency = this.value;
            updateSummary();
        });
    });
    
    // Payment method toggle
    paypalBtn.addEventListener('click', function() {
        paymentMethod = 'paypal';
        updatePaymentMethod();
    });
    
    cardBtn.addEventListener('click', function() {
        paymentMethod = 'card';
        updatePaymentMethod();
    });
    
    // Donate button
    donateBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // For demonstration, we'll just show the success modal
        // In a real implementation, you would process the payment here
        if (validateForm()) {
            modal.style.display = 'block';
        }
    });
    
    // Close modal buttons
    closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    closeSuccessBtn.addEventListener('click', function() {
        modal.style.display = 'none';
    });
    
    // Close modal when clicking outside of it
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Helper Functions
    function setSelectedAmount(amount) {
        selectedAmount = amount;
        updateSummary();
    }
    
    function updateSummary() {
        summaryAmount.textContent = `£${selectedAmount.toFixed(2)}`;
        summaryFrequency.textContent = donationFrequency === 'one-time' ? 'One-time' : 'Monthly';
        summaryTotal.textContent = `£${selectedAmount.toFixed(2)}`;
    }
    
    function updatePaymentMethod() {
        if (paymentMethod === 'paypal') {
            paypalBtn.classList.add('active');
            cardBtn.classList.remove('active');
            paypalContainer.style.display = 'block';
            cardContainer.style.display = 'none';
            donateBtn.style.display = 'none'; // Hide the donate button when PayPal is selected
        } else {
            paypalBtn.classList.remove('active');
            cardBtn.classList.add('active');
            paypalContainer.style.display = 'none';
            cardContainer.style.display = 'block';
            donateBtn.style.display = 'block'; // Show the donate button when Credit Card is selected
        }
    }
    
    function validateForm() {
        const firstName = document.getElementById('first-name').value;
        const lastName = document.getElementById('last-name').value;
        const email = document.getElementById('email').value;
        
        if (!firstName) {
            alert('Please enter your first name');
            return false;
        }
        
        if (!lastName) {
            alert('Please enter your last name');
            return false;
        }
        
        if (!email) {
            alert('Please enter your email address');
            return false;
        }
        
        if (paymentMethod === 'card') {
            const cardNumber = document.getElementById('card-number').value;
            const expiry = document.getElementById('expiry').value;
            const cvv = document.getElementById('cvv').value;
            
            if (!cardNumber || !expiry || !cvv) {
                alert('Please fill in all card details');
                return false;
            }
        }
        
        return true;
    }
    
    function initPayPalButton() {
        // This function renders the PayPal button
        // Replace YOUR_PAYPAL_CLIENT_ID with your actual client ID in the HTML
        
        if (window.paypal) {
            paypal.Buttons({
                style: {
                    shape: 'rect',
                    color: 'blue',
                    layout: 'vertical',
                    label: 'paypal'
                },
                
                createOrder: function(data, actions) {
                    // Validate form first
                    if (!validateForm()) {
                        return;
                    }
                    
                    return actions.order.create({
                        purchase_units: [{
                            description: donationFrequency === 'one-time' ? 'One-time Donation' : 'Monthly Donation',
                            amount: {
                                value: selectedAmount.toFixed(2)
                            }
                        }],
                        application_context: {
                            shipping_preference: 'NO_SHIPPING'
                        }
                    });
                },
                
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Show success message
                        modal.style.display = 'block';
                        
                        // In a real application, you would send this information to your server
                        console.log('Transaction completed by ' + details.payer.name.given_name);
                    });
                },
                
                onError: function(err) {
                    console.error('PayPal error:', err);
                    alert('There was an error processing your payment. Please try again.');
                }
            }).render('#paypal-button-container');
        }
    }
});

// Format credit card input with spaces
document.getElementById('card-number').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Remove all non-digits
    value = value.replace(/\D/g, '');
    
    // Add space after every 4 digits
    value = value.replace(/(\d{4})(?=\d)/g, '£1 ');
    
    // Limit to 19 characters (16 digits + 3 spaces)
    value = value.substring(0, 19);
    
    e.target.value = value;
});

// Format expiry date with slash
document.getElementById('expiry').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Remove all non-digits
    value = value.replace(/\D/g, '');
    
    // Add slash after first 2 digits
    if (value.length > 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    
    // Limit to 5 characters (MM/YY)
    value = value.substring(0, 5);
    
    e.target.value = value;
});

// Format CVV to 3 or 4 digits
document.getElementById('cvv').addEventListener('input', function(e) {
    let value = e.target.value;
    
    // Remove all non-digits
    value = value.replace(/\D/g, '');
    
    // Limit to 4 characters
    value = value.substring(0, 4);
    
    e.target.value = value;
});