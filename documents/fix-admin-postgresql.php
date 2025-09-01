<?php
require_once 'config-postgresql.php';

echo "Fixing PostgreSQL database structure and admin login...\n";

try {
    // First, check if email column exists in admin_users table
    $stmt = $pdo->query("SELECT column_name FROM information_schema.columns WHERE table_name = 'admin_users' AND column_name = 'email'");
    if ($stmt->rowCount() == 0) {
        echo "Adding email column to admin_users table...\n";
        $pdo->exec("ALTER TABLE admin_users ADD COLUMN email VARCHAR(100)");
    } else {
        echo "Email column already exists in admin_users table.\n";
    }

    // Check if loan_categories table exists, create if not
    $stmt = $pdo->query("SELECT to_regclass('public.loan_categories')");
    $result = $stmt->fetch();
    
    if ($result['to_regclass'] === null) {
        echo "Creating loan_categories table...\n";
        $pdo->exec("CREATE TABLE loan_categories (
            id SERIAL PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            icon VARCHAR(100) NOT NULL,
            description TEXT,
            key_point_1 VARCHAR(200),
            key_point_2 VARCHAR(200),
            key_point_3 VARCHAR(200),
            image_url VARCHAR(500),
            min_amount DECIMAL(12,2) DEFAULT 10000,
            max_amount DECIMAL(12,2) DEFAULT 5000000,
            interest_rate VARCHAR(50) DEFAULT '7% onwards',
            is_featured BOOLEAN DEFAULT FALSE,
            sort_order INTEGER DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Create trigger for updated_at
        $pdo->exec("CREATE OR REPLACE FUNCTION update_loan_categories_updated_at()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            $$ language 'plpgsql'");
            
        $pdo->exec("CREATE TRIGGER update_loan_categories_updated_at 
            BEFORE UPDATE ON loan_categories 
            FOR EACH ROW EXECUTE FUNCTION update_loan_categories_updated_at()");
    } else {
        echo "loan_categories table already exists.\n";
    }

    // Clear existing categories and insert default ones
    echo "Updating default loan categories...\n";
    $pdo->exec("DELETE FROM loan_categories");
    
    $categories = [
        ['Personal Loan', 'fas fa-user', 'Quick personal loans for immediate needs', 'Instant approval in 30 minutes', 'No collateral required', 'Flexible repayment up to 5 years', 'https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=400&h=250&fit=crop', 10000, 1000000, '10.5% onwards', true, 1],
        ['Home Loan', 'fas fa-home', 'Affordable home loans with competitive rates', 'Up to 90% property value funding', 'Tax benefits available', 'Tenure up to 30 years', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=250&fit=crop', 500000, 50000000, '7% onwards', true, 2],
        ['Education Loan', 'fas fa-graduation-cap', 'Fund your education dreams', 'Cover full course fees', 'Moratorium period available', 'No processing fees', 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=250&fit=crop', 50000, 2000000, '8.5% onwards', true, 3],
        ['Car Loan', 'fas fa-car', 'Drive your dream car today', 'Up to 90% on-road price', 'Quick approval in 2 hours', 'Zero down payment options', 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400&h=250&fit=crop', 100000, 2000000, '8% onwards', true, 4],
        ['Business Loan', 'fas fa-briefcase', 'Grow your business with our support', 'Minimal documentation', 'Quick disbursal process', 'Flexible repayment terms', 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=200&fit=crop', 100000, 5000000, '11% onwards', false, 5],
        ['Plot Purchase', 'fas fa-map-marked-alt', 'Buy your dream plot', 'Up to 80% plot value', 'Attractive interest rates', 'Easy documentation', 'https://images.unsplash.com/photo-1513475382585-d06e58bcb0e0?w=300&h=200&fit=crop', 200000, 10000000, '9% onwards', false, 6]
    ];
    
    $stmt = $pdo->prepare("INSERT INTO loan_categories (name, icon, description, key_point_1, key_point_2, key_point_3, image_url, min_amount, max_amount, interest_rate, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($categories as $category) {
        $stmt->execute($category);
    }
    
    echo "Default loan categories inserted successfully.\n";

    // Hash the password
    $password = 'Harsh@9131';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if admin user exists and update/create
    $stmt = $pdo->prepare("SELECT id FROM admin_users WHERE username = 'admin'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ?, email = ?, status = 'Active' WHERE username = 'admin'");
        $stmt->execute([$hashedPassword, 'admin@jsmf.in']);
        echo "Admin user updated successfully!\n";
    } else {
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $hashedPassword, 'admin@jsmf.in', 'super', 'Active']);
        echo "Admin user created successfully!\n";
    }

    // Create indexes if they don't exist
    echo "Creating/verifying database indexes...\n";
    try {
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_loan_applications_status ON loan_applications(status)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_loan_applications_mobile ON loan_applications(mobile)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_loan_applications_application_id ON loan_applications(application_id)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_dsa_users_username ON dsa_users(username)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_admin_users_username ON admin_users(username)");
        $pdo->exec("CREATE INDEX IF NOT EXISTS idx_loan_categories_active ON loan_categories(is_active, sort_order)");
        echo "Database indexes created/verified successfully.\n";
    } catch (PDOException $e) {
        echo "Note: Some indexes might already exist: " . $e->getMessage() . "\n";
    }

    echo "\n=== PostgreSQL Database Structure Fixed Successfully! ===\n";
    echo "Admin Login Credentials:\n";
    echo "Username: admin\n";
    echo "Password: Harsh@9131\n";
    echo "Email: admin@jsmf.in\n";
    echo "Role: super\n\n";
    echo "Configuration: config-postgresql.php\n";
    echo "Schema: database-postgresql.sql\n";

} catch (PDOException $e) {
    echo "PostgreSQL Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>