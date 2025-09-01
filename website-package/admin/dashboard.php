<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection (simple file-based storage for portability)
$data_file = 'data/applications.json';
$contact_file = 'data/contacts.json';

// Ensure data directory exists
if (!file_exists('data')) {
    mkdir('data', 0755, true);
}

// Initialize data files if they don't exist
if (!file_exists($data_file)) {
    file_put_contents($data_file, '[]');
}
if (!file_exists($contact_file)) {
    file_put_contents($contact_file, '[]');
}

// Load data
$applications = json_decode(file_get_contents($data_file), true) ?: [];
$contacts = json_decode(file_get_contents($contact_file), true) ?: [];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - JSMF Loan Services</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .admin-header {
            background: #1f2937;
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        .admin-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-nav h1 {
            margin: 0;
            font-size: 1.5rem;
        }
        .admin-nav .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .logout-btn {
            background: #dc2626;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
        }
        .logout-btn:hover {
            background: #b91c1c;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }
        .stat-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
            text-align: center;
        }
        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 0.5rem;
        }
        .stat-label {
            color: #6b7280;
            font-weight: 500;
        }
        .tab-container {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        }
        .tab-nav {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
        }
        .tab-btn {
            padding: 1rem 2rem;
            background: none;
            border: none;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            transition: all 0.3s;
        }
        .tab-btn.active {
            background: white;
            color: #2563eb;
            border-bottom: 2px solid #2563eb;
        }
        .tab-content {
            padding: 2rem;
        }
        .tab-pane {
            display: none;
        }
        .tab-pane.active {
            display: block;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        .data-table th,
        .data-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .data-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
        }
        .data-table tr:hover {
            background: #f8fafc;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-new {
            background: #dbeafe;
            color: #1d4ed8;
        }
        .status-processing {
            background: #fef3c7;
            color: #d97706;
        }
        .status-approved {
            background: #d1fae5;
            color: #059669;
        }
        .status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }
        .action-btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.875rem;
            margin: 0 0.25rem;
            text-decoration: none;
            display: inline-block;
        }
        .btn-view {
            background: #3b82f6;
            color: white;
        }
        .btn-edit {
            background: #10b981;
            color: white;
        }
        .btn-delete {
            background: #ef4444;
            color: white;
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="container">
            <div class="admin-nav">
                <h1>JSMF Admin Dashboard</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?></span>
                    <a href="?logout=1" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Statistics -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo count($applications); ?></div>
                <div class="stat-label">Total Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($applications, function($app) { return ($app['status'] ?? 'new') === 'new'; })); ?></div>
                <div class="stat-label">New Applications</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count($contacts); ?></div>
                <div class="stat-label">Contact Inquiries</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo count(array_filter($applications, function($app) { return ($app['status'] ?? 'new') === 'approved'; })); ?></div>
                <div class="stat-label">Approved Loans</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tab-container">
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('applications')">
                    <i class="fas fa-file-alt"></i> Loan Applications
                </button>
                <button class="tab-btn" onclick="switchTab('contacts')">
                    <i class="fas fa-envelope"></i> Contact Inquiries
                </button>
                <button class="tab-btn" onclick="switchTab('stats')">
                    <i class="fas fa-chart-bar"></i> Statistics
                </button>
            </div>

            <div class="tab-content">
                <!-- Applications Tab -->
                <div id="applications" class="tab-pane active">
                    <h3>Loan Applications</h3>
                    <?php if (empty($applications)): ?>
                        <div class="empty-state">
                            <i class="fas fa-file-alt"></i>
                            <h4>No Applications Yet</h4>
                            <p>When customers submit loan applications, they will appear here.</p>
                        </div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Name</th>
                                    <th>Loan Type</th>
                                    <th>Amount</th>
                                    <th>Phone</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($applications) as $app): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($app['reference_id'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($app['name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($app['loan_type'] ?? 'N/A'); ?></td>
                                    <td>₹<?php echo number_format($app['loan_amount'] ?? 0); ?></td>
                                    <td><?php echo htmlspecialchars($app['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($app['created_at'] ?? 'now')); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo htmlspecialchars($app['status'] ?? 'new'); ?>">
                                            <?php echo ucfirst($app['status'] ?? 'New'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="view-application.php?id=<?php echo htmlspecialchars($app['reference_id']); ?>" class="action-btn btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Contacts Tab -->
                <div id="contacts" class="tab-pane">
                    <h3>Contact Inquiries</h3>
                    <?php if (empty($contacts)): ?>
                        <div class="empty-state">
                            <i class="fas fa-envelope"></i>
                            <h4>No Contact Messages Yet</h4>
                            <p>When customers send messages through the contact form, they will appear here.</p>
                        </div>
                    <?php else: ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Subject</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_reverse($contacts) as $contact): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($contact['name'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($contact['email'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars($contact['phone'] ?? 'N/A'); ?></td>
                                    <td><?php echo htmlspecialchars(substr($contact['subject'] ?? 'N/A', 0, 50)); ?>...</td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($contact['created_at'] ?? 'now')); ?></td>
                                    <td>
                                        <a href="view-contact.php?id=<?php echo htmlspecialchars($contact['id'] ?? ''); ?>" class="action-btn btn-view">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

                <!-- Statistics Tab -->
                <div id="stats" class="tab-pane">
                    <h3>Statistics Overview</h3>
                    <div class="stats-grid">
                        <?php
                        $loan_types = [];
                        foreach ($applications as $app) {
                            $type = $app['loan_type'] ?? 'Other';
                            $loan_types[$type] = ($loan_types[$type] ?? 0) + 1;
                        }
                        ?>
                        <div class="stat-card">
                            <h4>Applications by Loan Type</h4>
                            <?php if (empty($loan_types)): ?>
                                <p>No data available</p>
                            <?php else: ?>
                                <?php foreach ($loan_types as $type => $count): ?>
                                    <div style="display: flex; justify-content: space-between; margin: 0.5rem 0;">
                                        <span><?php echo htmlspecialchars($type); ?>:</span>
                                        <strong><?php echo $count; ?></strong>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <div class="stat-card">
                            <h4>Quick Actions</h4>
                            <div style="text-align: left;">
                                <a href="../index.html" class="action-btn btn-view" style="display: block; margin: 0.5rem 0;">
                                    <i class="fas fa-globe"></i> View Website
                                </a>
                                <a href="export.php" class="action-btn btn-edit" style="display: block; margin: 0.5rem 0;">
                                    <i class="fas fa-download"></i> Export Data
                                </a>
                                <a href="settings.php" class="action-btn btn-view" style="display: block; margin: 0.5rem 0;">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab panes
            const panes = document.querySelectorAll('.tab-pane');
            panes.forEach(pane => pane.classList.remove('active'));
            
            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-btn');
            buttons.forEach(btn => btn.classList.remove('active'));
            
            // Show selected tab pane
            document.getElementById(tabName).classList.add('active');
            
            // Add active class to clicked button
            event.target.classList.add('active');
        }
    </script>
</body>
</html>