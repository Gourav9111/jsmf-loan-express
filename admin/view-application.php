<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$reference_id = $_GET['id'] ?? '';
if (empty($reference_id)) {
    header('Location: dashboard.php');
    exit;
}

// Load application data
$data_file = 'data/applications.json';
$applications = [];
if (file_exists($data_file)) {
    $applications = json_decode(file_get_contents($data_file), true) ?: [];
}

// Find the application
$application = null;
foreach ($applications as $app) {
    if ($app['reference_id'] === $reference_id) {
        $application = $app;
        break;
    }
}

if (!$application) {
    header('Location: dashboard.php');
    exit;
}

// Handle status update
if ($_POST && isset($_POST['update_status'])) {
    $new_status = $_POST['status'] ?? 'new';
    $notes = $_POST['notes'] ?? '';
    
    // Update application status
    for ($i = 0; $i < count($applications); $i++) {
        if ($applications[$i]['reference_id'] === $reference_id) {
            $applications[$i]['status'] = $new_status;
            $applications[$i]['admin_notes'] = $notes;
            $applications[$i]['updated_at'] = date('Y-m-d H:i:s');
            $applications[$i]['updated_by'] = $_SESSION['admin_username'];
            break;
        }
    }
    
    // Save updated data
    file_put_contents($data_file, json_encode($applications, JSON_PRETTY_PRINT));
    
    // Refresh application data
    $application['status'] = $new_status;
    $application['admin_notes'] = $notes;
    $application['updated_at'] = date('Y-m-d H:i:s');
    
    $success_message = 'Application status updated successfully!';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Application - <?php echo htmlspecialchars($reference_id); ?></title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-header { background: #1f2937; color: white; padding: 1rem 0; margin-bottom: 2rem; }
        .admin-nav { display: flex; justify-content: space-between; align-items: center; }
        .back-btn { background: #374151; color: white; padding: 0.5rem 1rem; border-radius: 5px; text-decoration: none; }
        .back-btn:hover { background: #4b5563; }
        .application-header { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .application-details { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }
        .detail-section { background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); margin-bottom: 2rem; }
        .detail-row { display: grid; grid-template-columns: 1fr 2fr; gap: 1rem; padding: 1rem 0; border-bottom: 1px solid #e5e7eb; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #374151; }
        .detail-value { color: #1f2937; }
        .status-form { background: #f8fafc; padding: 2rem; border-radius: 15px; }
        .status-badge { padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; text-transform: uppercase; font-size: 0.875rem; }
        .status-new { background: #dbeafe; color: #1d4ed8; }
        .status-processing { background: #fef3c7; color: #d97706; }
        .status-approved { background: #d1fae5; color: #059669; }
        .status-rejected { background: #fee2e2; color: #dc2626; }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <h1>Application Details</h1>
                <a href="dashboard.php" class="back-btn">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <div class="container">
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <!-- Application Header -->
        <div class="application-header">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2>Application #<?php echo htmlspecialchars($application['reference_id']); ?></h2>
                    <p style="color: #6b7280; margin: 0.5rem 0;">
                        Submitted on <?php echo date('d/m/Y H:i A', strtotime($application['created_at'] ?? 'now')); ?>
                    </p>
                </div>
                <div>
                    <span class="status-badge status-<?php echo htmlspecialchars($application['status'] ?? 'new'); ?>">
                        <?php echo ucfirst($application['status'] ?? 'New'); ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="application-details">
            <div>
                <!-- Loan Details -->
                <div class="detail-section">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem;">
                        <i class="fas fa-money-check-alt"></i> Loan Information
                    </h3>
                    <div class="detail-row">
                        <div class="detail-label">Loan Type:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['loan_type'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Loan Amount:</div>
                        <div class="detail-value">₹<?php echo number_format($application['loan_amount'] ?? 0); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Purpose:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['purpose'] ?? 'N/A'); ?></div>
                    </div>
                </div>

                <!-- Personal Details -->
                <div class="detail-section">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem;">
                        <i class="fas fa-user"></i> Personal Information
                    </h3>
                    <div class="detail-row">
                        <div class="detail-label">Full Name:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['name'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Date of Birth:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['dob'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Email:</div>
                        <div class="detail-value">
                            <a href="mailto:<?php echo htmlspecialchars($application['email'] ?? ''); ?>">
                                <?php echo htmlspecialchars($application['email'] ?? 'N/A'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Phone:</div>
                        <div class="detail-value">
                            <a href="tel:<?php echo htmlspecialchars($application['phone'] ?? ''); ?>">
                                <?php echo htmlspecialchars($application['phone'] ?? 'N/A'); ?>
                            </a>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">PAN Number:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['pan'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Aadhaar Number:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['aadhar'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Address:</div>
                        <div class="detail-value">
                            <?php echo htmlspecialchars($application['address'] ?? 'N/A'); ?><br>
                            <?php echo htmlspecialchars($application['city'] ?? ''); ?>, 
                            <?php echo htmlspecialchars($application['state'] ?? ''); ?> - 
                            <?php echo htmlspecialchars($application['pincode'] ?? ''); ?>
                        </div>
                    </div>
                </div>

                <!-- Employment Details -->
                <div class="detail-section">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem;">
                        <i class="fas fa-briefcase"></i> Employment Information
                    </h3>
                    <div class="detail-row">
                        <div class="detail-label">Employment Type:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['employment_type'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Company/Business:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['company_name'] ?? 'N/A'); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Monthly Income:</div>
                        <div class="detail-value">₹<?php echo number_format($application['monthly_income'] ?? 0); ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Work Experience:</div>
                        <div class="detail-value"><?php echo htmlspecialchars($application['work_experience'] ?? 'N/A'); ?></div>
                    </div>
                </div>
            </div>

            <!-- Status Update Panel -->
            <div>
                <div class="status-form">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Update Application Status</h3>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select id="status" name="status" required>
                                <option value="new" <?php echo ($application['status'] ?? 'new') === 'new' ? 'selected' : ''; ?>>New</option>
                                <option value="processing" <?php echo ($application['status'] ?? '') === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                <option value="approved" <?php echo ($application['status'] ?? '') === 'approved' ? 'selected' : ''; ?>>Approved</option>
                                <option value="rejected" <?php echo ($application['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Admin Notes</label>
                            <textarea id="notes" name="notes" placeholder="Add notes about this application..."><?php echo htmlspecialchars($application['admin_notes'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_status" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-save"></i> Update Status
                        </button>
                    </form>
                </div>

                <!-- Application Timeline -->
                <div class="detail-section">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Application Timeline</h3>
                    <div style="padding: 1rem 0;">
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="width: 12px; height: 12px; background: #2563eb; border-radius: 50%; margin-right: 1rem;"></div>
                            <div>
                                <strong>Application Submitted</strong><br>
                                <small style="color: #6b7280;"><?php echo date('d/m/Y H:i A', strtotime($application['created_at'] ?? 'now')); ?></small>
                            </div>
                        </div>
                        
                        <?php if (isset($application['updated_at'])): ?>
                        <div style="display: flex; align-items: center; margin-bottom: 1rem;">
                            <div style="width: 12px; height: 12px; background: #10b981; border-radius: 50%; margin-right: 1rem;"></div>
                            <div>
                                <strong>Status Updated</strong><br>
                                <small style="color: #6b7280;">
                                    <?php echo date('d/m/Y H:i A', strtotime($application['updated_at'])); ?>
                                    by <?php echo htmlspecialchars($application['updated_by'] ?? 'Admin'); ?>
                                </small>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="detail-section">
                    <h3 style="margin-bottom: 1.5rem; color: #1f2937;">Quick Actions</h3>
                    <div style="display: grid; gap: 1rem;">
                        <a href="mailto:<?php echo htmlspecialchars($application['email'] ?? ''); ?>" class="btn btn-outline" style="text-align: center;">
                            <i class="fas fa-envelope"></i> Send Email
                        </a>
                        <a href="tel:<?php echo htmlspecialchars($application['phone'] ?? ''); ?>" class="btn btn-outline" style="text-align: center;">
                            <i class="fas fa-phone"></i> Call Applicant
                        </a>
                        <button onclick="window.print()" class="btn btn-outline" style="width: 100%;">
                            <i class="fas fa-print"></i> Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>