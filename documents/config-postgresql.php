<?php
// Database configuration for PostgreSQL
define('DB_HOST', 'localhost');
define('DB_NAME', 'jsmf_postgresql');
define('DB_USER', 'postgres');
define('DB_PASS', 'your_postgresql_password');
define('DB_PORT', '5432');

// Website configuration
define('SITE_URL', 'https://jsmf.in');
define('SITE_NAME', 'Jay Shree Mahakal Finance Services');
define('CONTACT_EMAIL', 'costumercare@jsmf.in');
define('CONTACT_PHONE', '+91 62620 79180');
define('COMPANY_ADDRESS', 'HARSH SAHU, Shop No 2, Near Mittal College, Karond, Bhopal, Madhya Pradesh');

// PDO PostgreSQL Database connection
try {
    $pdo = new PDO("pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // Set PostgreSQL specific settings
    $pdo->exec("SET client_encoding = 'UTF8'");
    $pdo->exec("SET timezone = 'UTC'");
} catch(PDOException $e) {
    die("PostgreSQL Connection failed: " . $e->getMessage());
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

// PostgreSQL specific helper functions
function getLastInsertId($pdo, $sequence_name = null) {
    if ($sequence_name) {
        return $pdo->lastInsertId($sequence_name);
    }
    return $pdo->lastInsertId();
}

function formatPostgreSQLDate($date) {
    return date('Y-m-d H:i:s', strtotime($date));
}
?>