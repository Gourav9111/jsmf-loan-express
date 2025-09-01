<?php
require_once 'config.php';

$page_title = "Apply for Loan";
$page_description = "Apply for Personal Loan, Home Loan, Education Loan, Car Loan and more with Jay Shree Mahakal Finance Services";

// Get loan type from URL parameter
$selected_loan_type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';

// Fetch loan types from database
try {
    $stmt = $pdo->query("SELECT * FROM loan_types WHERE is_active = 1 ORDER BY name");
    $loan_types = $stmt->fetchAll();
} catch(PDOException $e) {
    $loan_types = [];
}

// If no loan types in database, use default array
if (empty($loan_types)) {
    $loan_types = [
        ['name' => 'Personal Loan'],
        ['name' => 'Home Loan'],
        ['name' => 'Education Loan'],
        ['name' => 'Car Loan'],
        ['name' => 'Business Loan'],
        ['name' => 'Plot Purchase'],
        ['name' => 'Construction Loan'],
        ['name' => 'Renovation Loan'],
        ['name' => 'Balance Transfer'],
        ['name' => 'LAP (Loan Against Property)']
    ];
}

include 'includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-container">
                <div class="text-center mb-4">
                    <h2 class="text-danger fw-bold">Apply for Loan</h2>
                    <p class="text-muted">Fill out the form below and get instant approval</p>
                </div>

                <form id="loanApplicationForm" action="process-loan.php" method="POST" class="needs-validation" novalidate>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" required>
                                <div class="invalid-feedback">Please enter your full name.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="mobile" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10}" required>
                                <div class="invalid-feedback">Please enter a valid 10-digit mobile number.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="city" name="city" required>
                                <div class="invalid-feedback">Please enter your city.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loan_type" class="form-label">Loan Type <span class="text-danger">*</span></label>
                                <select class="form-control" id="loan_type" name="loan_type" required>
                                    <option value="">Select Loan Type</option>
                                    <?php foreach($loan_types as $loan): ?>
                                    <option value="<?php echo htmlspecialchars($loan['name']); ?>" 
                                            <?php echo ($selected_loan_type == $loan['name']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($loan['name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">Please select a loan type.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="loan_amount" class="form-label">Loan Amount (₹) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="loan_amount" name="loan_amount" 
                                       placeholder="e.g., 5,00,000" required>
                                <div class="invalid-feedback">Please enter the loan amount.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monthly_income" class="form-label">Monthly Income (₹) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="monthly_income" name="monthly_income" 
                                       placeholder="e.g., 50,000" required>
                                <div class="invalid-feedback">Please enter your monthly income.</div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="pan_aadhar" class="form-label">PAN / Aadhaar Number</label>
                        <input type="text" class="form-control" id="pan_aadhar" name="pan_aadhar" 
                               placeholder="Enter PAN (ABCDE1234F) or Aadhaar">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="terms-and-conditions.php" target="_blank" class="text-danger">Terms & Conditions</a> 
                                and <a href="privacy-policy.php" target="_blank" class="text-danger">Privacy Policy</a>
                            </label>
                            <div class="invalid-feedback">Please accept the terms and conditions.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="whatsapp_updates" name="whatsapp_updates">
                            <label class="form-check-label" for="whatsapp_updates">
                                I consent to receive updates on WhatsApp
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger btn-lg px-5">
                            <i class="fas fa-paper-plane me-2"></i>Submit Application
                        </button>
                    </div>
                </form>

                <!-- Loan Information -->
                <div class="mt-5 p-4 bg-light rounded">
                    <h5 class="text-danger mb-3">Why Choose Our Loans?</h5>
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <i class="fas fa-clock text-danger fa-2x mb-2"></i>
                            <h6>Quick Approval</h6>
                            <p class="small text-muted">Get approval in 24 hours</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="fas fa-percentage text-danger fa-2x mb-2"></i>
                            <h6>Low Interest Rates</h6>
                            <p class="small text-muted">Starting from 7% per annum</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <i class="fas fa-shield-alt text-danger fa-2x mb-2"></i>
                            <h6>Secure Process</h6>
                            <p class="small text-muted">100% safe and secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="container mb-5">
    <div class="row">
        <div class="col-md-4 text-center mb-3">
            <a href="emi/calculator.php" class="btn btn-outline-danger btn-lg w-100">
                <i class="fas fa-calculator me-2"></i>Calculate EMI
            </a>
        </div>
        <div class="col-md-4 text-center mb-3">
            <a href="check-status.php" class="btn btn-outline-danger btn-lg w-100">
                <i class="fas fa-search me-2"></i>Check Status
            </a>
        </div>
        <div class="col-md-4 text-center mb-3">
            <a href="contact-us.php" class="btn btn-outline-danger btn-lg w-100">
                <i class="fas fa-phone me-2"></i>Contact Us
            </a>
        </div>
    </div>
</div>

<script>
// Format amount inputs
document.addEventListener('DOMContentLoaded', function() {
    const amountInputs = document.querySelectorAll('#loan_amount, #monthly_income');
    amountInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-digits
            let value = this.value.replace(/\D/g, '');
            
            // Format with commas
            if (value) {
                this.value = new Intl.NumberFormat('en-IN').format(value);
            }
        });
        
        // Remove formatting for form submission
        input.addEventListener('blur', function() {
            const rawValue = this.value.replace(/,/g, '');
            this.setAttribute('data-raw-value', rawValue);
        });
    });
    
    // Update form values before submission
    document.getElementById('loanApplicationForm').addEventListener('submit', function() {
        amountInputs.forEach(input => {
            const rawValue = input.value.replace(/,/g, '');
            input.value = rawValue;
        });
    });
});
</script>

<?php include 'includes/footer.php'; ?>
