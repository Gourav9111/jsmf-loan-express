<?php
require_once '../config.php';

$page_title = "Admin Login";
$page_description = "Admin Portal Login - Access your admin dashboard";

// Check if user is already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = isset($_POST['username']) ? sanitizeInput($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        
        if (empty($username) || empty($password)) {
            throw new Exception('Please enter both username and password.');
        }
        
        // Check admin credentials
        $stmt = $pdo->prepare("SELECT id, username, password, role FROM admin_users WHERE username = ? AND status = 'Active'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();
        
        if ($admin && password_verify($password, $admin['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Set session variables
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            $_SESSION['admin_role'] = $admin['role'];
            $_SESSION['admin_login_time'] = time();
            
            // Redirect to dashboard
            header("Location: dashboard.php");
            exit;
        } else {
            $error_message = 'Invalid username or password.';
        }
        
    } catch (Exception $e) {
        $error_message = $e->getMessage();
    }
}

include '../includes/header.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="form-container">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="text-danger fw-bold">Admin Login</h2>
                    <p class="text-muted">Secure access to admin dashboard</p>
                </div>

                <?php if ($error_message): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <form method="POST" class="needs-validation" novalidate>
                    <div class="form-group">
                        <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control" id="username" name="username" 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" required>
                        </div>
                        <div class="invalid-feedback">Please enter your username.</div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="invalid-feedback">Please enter your password.</div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-danger btn-lg w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Secure Login
                        </button>
                    </div>
                </form>

                <!-- Security Information -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="text-danger mb-2"><i class="fas fa-info-circle me-2"></i>Security Notice</h6>
                    <ul class="list-unstyled small text-muted mb-0">
                        <li><i class="fas fa-check text-success me-2"></i>All admin activities are logged</li>
                        <li><i class="fas fa-check text-success me-2"></i>Sessions timeout after inactivity</li>
                        <li><i class="fas fa-check text-success me-2"></i>Secure SSL encryption enabled</li>
                    </ul>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-lock me-1"></i>
                        This is a secure area. Unauthorized access is prohibited.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle password visibility
document.getElementById('togglePassword').addEventListener('click', function() {
    const password = document.getElementById('password');
    const icon = this.querySelector('i');
    
    if (password.type === 'password') {
        password.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        password.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
});

// Form validation
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

// Auto-focus on username field
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('username').focus();
});
</script>

<?php include '../includes/footer.php'; ?>
