<?php
// Database configuration for production MySQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'u900473099_gourav');
define('DB_USER', 'u900473099_gourav');
define('DB_PASS', 'Gourav@9111968788');

// Website configuration
define('SITE_URL', 'https://jsmf.in');
define('SITE_NAME', 'Jay Shree Mahakal Finance Services');
define('CONTACT_EMAIL', 'costumercare@jsmf.in');
define('CONTACT_PHONE', '+91 62620 79180');
define('COMPANY_ADDRESS', 'HARSH SAHU, Shop No 2, Near Mittal College, Karond, Bhopal, Madhya Pradesh');

// PDO Database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Helper functions
function generateApplicationId() {
    return 'JSMF' . date('Y') . str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function sendEmail($to, $subject, $message) {
    // Simple mail function - can be enhanced with PHPMailer
    $headers = "From: " . CONTACT_EMAIL . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers);
}
?>
