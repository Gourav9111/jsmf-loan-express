<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Sanitize and validate input
        $name = sanitizeInput($_POST['name']);
        $mobile = sanitizeInput($_POST['mobile']);
        $city = sanitizeInput($_POST['city']);
        $loan_type = sanitizeInput($_POST['loan_type']);
        $loan_amount = str_replace(',', '', sanitizeInput($_POST['loan_amount']));
        $monthly_income = str_replace(',', '', sanitizeInput($_POST['monthly_income']));
        $pan_aadhar = sanitizeInput($_POST['pan_aadhar'] ?? '');
        
        // Validate required fields
        if (empty($name) || empty($mobile) || empty($city) || empty($loan_type) || empty($loan_amount) || empty($monthly_income)) {
            throw new Exception('All required fields must be filled.');
        }
        
        // Validate mobile number
        if (!preg_match('/^[0-9]{10}$/', $mobile)) {
            throw new Exception('Please enter a valid 10-digit mobile number.');
        }
        
        // Validate loan amount and income
        if (!is_numeric($loan_amount) || $loan_amount < 10000) {
            throw new Exception('Loan amount must be at least ₹10,000.');
        }
        
        if (!is_numeric($monthly_income) || $monthly_income < 10000) {
            throw new Exception('Monthly income must be at least ₹10,000.');
        }
        
        // Generate unique application ID
        $application_id = generateApplicationId();
        
        // Check if application ID already exists (unlikely but safe check)
        $check_stmt = $pdo->prepare("SELECT id FROM loan_applications WHERE application_id = ?");
        $check_stmt->execute([$application_id]);
        
        // If exists, generate a new one
        while ($check_stmt->fetch()) {
            $application_id = generateApplicationId();
            $check_stmt->execute([$application_id]);
        }
        
        // Insert loan application
        $stmt = $pdo->prepare("
            INSERT INTO loan_applications 
            (application_id, name, mobile, city, loan_type, loan_amount, monthly_income, pan_aadhar, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Pending')
        ");
        
        $stmt->execute([
            $application_id,
            $name,
            $mobile,
            $city,
            $loan_type,
            $loan_amount,
            $monthly_income,
            $pan_aadhar
        ]);
        
        // Send confirmation email (if email function is working)
        $email_subject = "Loan Application Received - " . $application_id;
        $email_message = "
            <h3>Dear {$name},</h3>
            <p>Thank you for applying for a loan with Jay Shree Mahakal Finance Services.</p>
            <p><strong>Application Details:</strong></p>
            <ul>
                <li>Application ID: <strong>{$application_id}</strong></li>
                <li>Loan Type: {$loan_type}</li>
                <li>Loan Amount: ₹" . number_format($loan_amount) . "</li>
                <li>Status: Pending</li>
            </ul>
            <p>Our team will review your application and contact you within 24 hours.</p>
            <p>You can check your application status anytime using your Application ID or mobile number on our website.</p>
            <p>Best regards,<br>Jay Shree Mahakal Finance Services</p>
        ";
        
        // Redirect to success page with application ID
        header("Location: apply-loan.php?success=1&app_id=" . urlencode($application_id));
        exit;
        
    } catch (Exception $e) {
        // Redirect back with error
        header("Location: apply-loan.php?error=" . urlencode($e->getMessage()));
        exit;
    }
} else {
    header("Location: apply-loan.php");
    exit;
}
?>

<?php if (isset($_GET['success']) && isset($_GET['app_id'])): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted - Jay Shree Mahakal Finance Services</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="text-success mb-3">Application Submitted Successfully!</h2>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title text-danger">Your Application ID</h5>
                            <h3 class="text-danger fw-bold"><?php echo htmlspecialchars($_GET['app_id']); ?></h3>
                            <p class="card-text">
                                Please save this Application ID for future reference. 
                                Our team will review your application and contact you within 24 hours.
                            </p>
                            <div class="mt-4">
                                <a href="check-status.php" class="btn btn-danger me-2">
                                    <i class="fas fa-search me-2"></i>Check Status
                                </a>
                                <a href="index.php" class="btn btn-outline-danger">
                                    <i class="fas fa-home me-2"></i>Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="text-danger">What's Next?</h6>
                        <ul class="list-unstyled text-start">
                            <li><i class="fas fa-check text-success me-2"></i>Application received and under review</li>
                            <li><i class="fas fa-phone text-warning me-2"></i>Our team will contact you for document verification</li>
                            <li><i class="fas fa-file-check text-info me-2"></i>Final approval and disbursement</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php endif; ?>
