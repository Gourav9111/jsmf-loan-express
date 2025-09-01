// Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function() {
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking on a link
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', () => {
                navMenu.classList.remove('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
});

// EMI Calculator Function
function calculateEMI() {
    const loanAmount = parseFloat(document.getElementById('loanAmount').value);
    const annualRate = parseFloat(document.getElementById('interestRate').value);
    const loanTenureYears = parseFloat(document.getElementById('loanTenure').value);

    // Validation
    if (!loanAmount || !annualRate || !loanTenureYears) {
        alert('Please fill all fields');
        return;
    }

    if (loanAmount < 100000 || loanAmount > 50000000) {
        alert('Loan amount should be between ₹1 Lakh and ₹5 Crore');
        return;
    }

    if (annualRate < 7 || annualRate > 24) {
        alert('Interest rate should be between 7% and 24%');
        return;
    }

    if (loanTenureYears < 1 || loanTenureYears > 30) {
        alert('Loan tenure should be between 1 and 30 years');
        return;
    }

    // Calculate EMI
    const monthlyRate = annualRate / 12 / 100;
    const numberOfPayments = loanTenureYears * 12;

    const emi = (loanAmount * monthlyRate * Math.pow(1 + monthlyRate, numberOfPayments)) / 
                (Math.pow(1 + monthlyRate, numberOfPayments) - 1);

    const totalAmount = emi * numberOfPayments;
    const totalInterest = totalAmount - loanAmount;

    // Display results
    document.getElementById('monthlyEMI').textContent = '₹' + formatNumber(Math.round(emi));
    document.getElementById('totalInterest').textContent = '₹' + formatNumber(Math.round(totalInterest));
    document.getElementById('totalAmount').textContent = '₹' + formatNumber(Math.round(totalAmount));
    document.getElementById('emiResult').style.display = 'block';

    // Smooth scroll to result
    document.getElementById('emiResult').scrollIntoView({ behavior: 'smooth' });
}

// Format number with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Form Validation and Submission
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            showFieldError(input, 'This field is required');
            isValid = false;
        } else {
            clearFieldError(input);
        }

        // Email validation
        if (input.type === 'email' && input.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(input.value)) {
                showFieldError(input, 'Please enter a valid email address');
                isValid = false;
            }
        }

        // Phone validation
        if (input.type === 'tel' && input.value) {
            const phoneRegex = /^[6-9]\d{9}$/;
            if (!phoneRegex.test(input.value.replace(/\D/g, ''))) {
                showFieldError(input, 'Please enter a valid 10-digit mobile number');
                isValid = false;
            }
        }
    });

    return isValid;
}

function showFieldError(input, message) {
    clearFieldError(input);
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.color = '#ef4444';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    errorDiv.textContent = message;
    input.parentNode.appendChild(errorDiv);
    input.style.borderColor = '#ef4444';
}

function clearFieldError(input) {
    const existingError = input.parentNode.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    input.style.borderColor = '#e5e7eb';
}

// Contact Form Submission
function submitContactForm(event) {
    event.preventDefault();
    const form = event.target;

    if (!validateForm(form)) {
        return;
    }

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Show loading state
    submitBtn.textContent = 'Sending...';
    submitBtn.disabled = true;

    fetch('contact-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', 'Thank you! Your message has been sent successfully. We will contact you soon.');
            form.reset();
        } else {
            showAlert('error', data.message || 'Something went wrong. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Network error. Please check your connection and try again.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Loan Application Form Submission
function submitLoanApplication(event) {
    event.preventDefault();
    const form = event.target;

    if (!validateForm(form)) {
        return;
    }

    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    // Show loading state
    submitBtn.textContent = 'Submitting Application...';
    submitBtn.disabled = true;

    fetch('loan-application-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('success', `Application submitted successfully! Your reference ID is: ${data.referenceId}`);
            form.reset();
            // Redirect to thank you page after 3 seconds
            setTimeout(() => {
                window.location.href = 'application-success.html?ref=' + data.referenceId;
            }, 3000);
        } else {
            showAlert('error', data.message || 'Something went wrong. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('error', 'Network error. Please check your connection and try again.');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Show Alert Messages
function showAlert(type, message) {
    // Remove existing alerts
    const existingAlerts = document.querySelectorAll('.alert');
    existingAlerts.forEach(alert => alert.remove());

    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        <div style="display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        </div>
    `;

    // Insert at the top of the page
    document.body.insertBefore(alertDiv, document.body.firstChild);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);

    // Scroll to top to show alert
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
});

// Loan amount slider functionality
function updateLoanAmount(value) {
    document.getElementById('loanAmountDisplay').textContent = '₹' + formatNumber(value);
}

function updateInterestRate(value) {
    document.getElementById('interestRateDisplay').textContent = value + '%';
}

function updateLoanTenure(value) {
    document.getElementById('loanTenureDisplay').textContent = value + ' years';
}

// Auto-calculate EMI when slider values change
function autoCalculateEMI() {
    const loanAmount = document.getElementById('loanAmountSlider');
    const interestRate = document.getElementById('interestRateSlider');
    const loanTenure = document.getElementById('loanTenureSlider');

    if (loanAmount && interestRate && loanTenure) {
        // Set input values
        document.getElementById('loanAmount').value = loanAmount.value;
        document.getElementById('interestRate').value = interestRate.value;
        document.getElementById('loanTenure').value = loanTenure.value;

        // Calculate EMI
        calculateEMI();
    }
}

// Document upload preview
function previewFile(input) {
    const file = input.files[0];
    const previewDiv = input.parentNode.querySelector('.file-preview');
    
    if (!previewDiv) {
        const preview = document.createElement('div');
        preview.className = 'file-preview';
        preview.style.marginTop = '0.5rem';
        preview.style.fontSize = '0.875rem';
        preview.style.color = '#6b7280';
        input.parentNode.appendChild(preview);
    }

    if (file) {
        const preview = input.parentNode.querySelector('.file-preview');
        preview.innerHTML = `
            <i class="fas fa-file"></i>
            <span>${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
        `;
    }
}

// Initialize tooltips and other interactive elements
document.addEventListener('DOMContentLoaded', function() {
    // Add loading animation to forms
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            }
        });
    });

    // Add click tracking for analytics (if needed)
    const trackingElements = document.querySelectorAll('[data-track]');
    trackingElements.forEach(element => {
        element.addEventListener('click', function() {
            const action = this.getAttribute('data-track');
            console.log('Tracking:', action);
            // Add your analytics code here
        });
    });
});

// Loan eligibility quick check
function quickEligibilityCheck() {
    const income = document.getElementById('monthlyIncome')?.value;
    const amount = document.getElementById('requestedAmount')?.value;
    
    if (income && amount) {
        const eligibleAmount = income * 60; // 60x monthly income rule
        const resultDiv = document.getElementById('eligibilityResult');
        
        if (resultDiv) {
            if (parseFloat(amount) <= eligibleAmount) {
                resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        Good news! You may be eligible for this loan amount.
                    </div>
                `;
            } else {
                resultDiv.innerHTML = `
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        Based on your income, you may be eligible for up to ₹${formatNumber(eligibleAmount)}.
                    </div>
                `;
            }
        }
    }
}

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const incomeInput = document.getElementById('monthlyIncome');
    const amountInput = document.getElementById('requestedAmount');
    
    if (incomeInput && amountInput) {
        [incomeInput, amountInput].forEach(input => {
            input.addEventListener('blur', quickEligibilityCheck);
        });
    }
});

// WhatsApp integration for quick contact
function openWhatsApp(message = '') {
    const phoneNumber = '916262079180';
    const defaultMessage = message || 'Hi JSMF Loan Services, I am interested in your loan products. Please provide more information.';
    const whatsappURL = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(defaultMessage)}`;
    window.open(whatsappURL, '_blank');
}

// Call tracking
function trackCall(source = '') {
    console.log('Call initiated from:', source);
    // Add call tracking analytics here
}

// Email tracking
function trackEmail(source = '') {
    console.log('Email initiated from:', source);
    // Add email tracking analytics here
}