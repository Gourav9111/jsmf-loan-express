<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$loan_type = trim($_POST['loan_type'] ?? '');
$loan_amount = trim($_POST['loan_amount'] ?? '');
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$pan = trim($_POST['pan'] ?? '');
$aadhar = trim($_POST['aadhar'] ?? '');
$address = trim($_POST['address'] ?? '');
$city = trim($_POST['city'] ?? '');
$state = trim($_POST['state'] ?? '');
$pincode = trim($_POST['pincode'] ?? '');
$employment_type = trim($_POST['employment_type'] ?? '');
$company_name = trim($_POST['company_name'] ?? '');
$monthly_income = trim($_POST['monthly_income'] ?? '');
$work_experience = trim($_POST['work_experience'] ?? '');
$purpose = trim($_POST['purpose'] ?? '');

// Validation
$errors = [];

if (empty($loan_type)) {
    $errors[] = 'Loan type is required';
}

if (empty($loan_amount) || !is_numeric($loan_amount) || $loan_amount < 100000) {
    $errors[] = 'Valid loan amount (minimum ₹1 Lakh) is required';
}

if (empty($name)) {
    $errors[] = 'Full name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($phone) || !preg_match('/^[6-9]\d{9}$/', preg_replace('/\D/', '', $phone))) {
    $errors[] = 'Valid 10-digit mobile number is required';
}

if (empty($pan) || !preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/', strtoupper($pan))) {
    $errors[] = 'Valid PAN number is required';
}

if (empty($monthly_income) || !is_numeric($monthly_income)) {
    $errors[] = 'Valid monthly income is required';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Generate reference ID
$reference_id = 'JSMF' . date('Ymd') . rand(1000, 9999);

// Prepare data
$application_data = [
    'reference_id' => $reference_id,
    'loan_type' => $loan_type,
    'loan_amount' => floatval($loan_amount),
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'dob' => $dob,
    'pan' => strtoupper($pan),
    'aadhar' => $aadhar,
    'address' => $address,
    'city' => $city,
    'state' => $state,
    'pincode' => $pincode,
    'employment_type' => $employment_type,
    'company_name' => $company_name,
    'monthly_income' => floatval($monthly_income),
    'work_experience' => $work_experience,
    'purpose' => $purpose,
    'status' => 'new',
    'created_at' => date('Y-m-d H:i:s'),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Store data
$data_file = 'admin/data/applications.json';

// Ensure directory exists
if (!file_exists('admin/data')) {
    mkdir('admin/data', 0755, true);
}

// Load existing data
$applications = [];
if (file_exists($data_file)) {
    $existing_data = file_get_contents($data_file);
    $applications = json_decode($existing_data, true) ?: [];
}

// Add new application
$applications[] = $application_data;

// Save data
if (file_put_contents($data_file, json_encode($applications, JSON_PRETTY_PRINT))) {
    // Send email notification
    $to = 'costumercare@jsmf.in';
    $email_subject = 'New Loan Application - ' . $reference_id;
    $email_message = "
New loan application received:

Reference ID: $reference_id
Loan Type: $loan_type
Loan Amount: ₹" . number_format($loan_amount) . "

Applicant Details:
Name: $name
Email: $email
Phone: $phone
PAN: " . strtoupper($pan) . "
Monthly Income: ₹" . number_format($monthly_income) . "
Employment: $employment_type

Submitted at: " . date('Y-m-d H:i:s') . "

Please review and process this application in the admin panel.
";

    $headers = "From: noreply@jsmf.in\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Attempt to send email
    @mail($to, $email_subject, $email_message, $headers);

    // Send confirmation email to applicant
    $applicant_subject = 'Loan Application Received - ' . $reference_id;
    $applicant_message = "
Dear $name,

Thank you for your loan application with JSMF Loan Services.

Your application details:
Reference ID: $reference_id
Loan Type: $loan_type
Loan Amount: ₹" . number_format($loan_amount) . "

We have received your application and our team will review it shortly. You will be contacted within 24-48 hours for further process.

For any queries, please contact us:
Phone: +91 62620 79180
Email: costumercare@jsmf.in

Thank you for choosing JSMF Loan Services.

Best regards,
JSMF Loan Services Team
";

    $applicant_headers = "From: costumercare@jsmf.in\r\n";
    $applicant_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    @mail($email, $applicant_subject, $applicant_message, $applicant_headers);

    echo json_encode([
        'success' => true, 
        'message' => 'Application submitted successfully!',
        'referenceId' => $reference_id
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save your application. Please try again.']);
}
?>