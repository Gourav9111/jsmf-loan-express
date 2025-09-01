<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get form data
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

// Validation
$errors = [];

if (empty($name)) {
    $errors[] = 'Name is required';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Valid email is required';
}

if (empty($phone) || !preg_match('/^[6-9]\d{9}$/', preg_replace('/\D/', '', $phone))) {
    $errors[] = 'Valid 10-digit mobile number is required';
}

if (empty($message)) {
    $errors[] = 'Message is required';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Prepare data
$contact_data = [
    'id' => 'CNT' . date('YmdHis') . rand(100, 999),
    'name' => $name,
    'email' => $email,
    'phone' => $phone,
    'subject' => $subject,
    'message' => $message,
    'created_at' => date('Y-m-d H:i:s'),
    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
];

// Store data
$data_file = 'admin/data/contacts.json';

// Ensure directory exists
if (!file_exists('admin/data')) {
    mkdir('admin/data', 0755, true);
}

// Load existing data
$contacts = [];
if (file_exists($data_file)) {
    $existing_data = file_get_contents($data_file);
    $contacts = json_decode($existing_data, true) ?: [];
}

// Add new contact
$contacts[] = $contact_data;

// Save data
if (file_put_contents($data_file, json_encode($contacts, JSON_PRETTY_PRINT))) {
    // Send email notification (if configured)
    $to = 'costumercare@jsmf.in';
    $email_subject = 'New Contact Form Submission - JSMF Loan Services';
    $email_message = "
New contact form submission received:

Name: $name
Email: $email
Phone: $phone
Subject: $subject

Message:
$message

Submitted at: " . date('Y-m-d H:i:s') . "
IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "
";

    $headers = "From: noreply@jsmf.in\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Attempt to send email (will work if mail server is configured)
    @mail($to, $email_subject, $email_message, $headers);

    echo json_encode(['success' => true, 'message' => 'Thank you for your message. We will contact you soon!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save your message. Please try again.']);
}
?>