<?php
// PostgreSQL Database setup for local development
$host = 'localhost';
$username = 'postgres';
$password = 'your_postgresql_password';
$port = '5432';
$database = 'jsmf_postgresql';

try {
    // Create database if not exists
    $pdo = new PDO("pgsql:host=$host;port=$port", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists and create if not
    $stmt = $pdo->prepare("SELECT 1 FROM pg_database WHERE datname = ?");
    $stmt->execute([$database]);
    
    if (!$stmt->fetch()) {
        echo "Creating database '$database'...\n";
        $pdo->exec("CREATE DATABASE $database");
    } else {
        echo "Database '$database' already exists.\n";
    }
    
    // Connect to the specific database
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to PostgreSQL database '$database'.\n";
    
    // Read and execute SQL file
    $sql = file_get_contents('database-postgresql.sql');
    
    if ($sql === false) {
        throw new Exception("Could not read database-postgresql.sql file");
    }
    
    // Split SQL statements (PostgreSQL compatible)
    $statements = explode(';', $sql);
    
    echo "Executing database schema...\n";
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (!empty($statement) && !preg_match('/^--/', $statement)) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Continue on minor errors but report them
                echo "Warning: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "Database schema executed successfully.\n";
    
    // Create admin user
    $adminPassword = password_hash('Harsh@9131', PASSWORD_DEFAULT);
    
    // Check if admin user already exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE username = ?");
    $stmt->execute(['admin']);
    
    if ($stmt->fetchColumn() == 0) {
        echo "Creating admin user...\n";
        $stmt = $pdo->prepare("INSERT INTO admin_users (username, password, email, role, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', $adminPassword, 'admin@jsmf.in', 'admin', 'Active']);
        echo "Admin user created successfully.\n";
    } else {
        echo "Admin user already exists, updating password...\n";
        $stmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE username = ?");
        $stmt->execute([$adminPassword, 'admin']);
        echo "Admin user password updated.\n";
    }
    
    // Insert default loan categories if they don't exist
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM loan_categories");
    $stmt->execute();
    
    if ($stmt->fetchColumn() == 0) {
        echo "Inserting default loan categories...\n";
        
        $categories = [
            ['Personal Loan', 'fas fa-user', 'Quick personal loans for immediate needs', 'Instant approval in 30 minutes', 'No collateral required', 'Flexible repayment up to 5 years', 'https://images.unsplash.com/photo-1633158829585-23ba8f7c8caf?w=400&h=250&fit=crop', 10000, 1000000, '10.5% onwards', true, 1],
            ['Home Loan', 'fas fa-home', 'Affordable home loans with competitive rates', 'Up to 90% property value funding', 'Tax benefits available', 'Tenure up to 30 years', 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=400&h=250&fit=crop', 500000, 50000000, '7% onwards', true, 2],
            ['Education Loan', 'fas fa-graduation-cap', 'Fund your education dreams', 'Cover full course fees', 'Moratorium period available', 'No processing fees', 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=400&h=250&fit=crop', 50000, 2000000, '8.5% onwards', true, 3],
            ['Business Loan', 'fas fa-briefcase', 'Expand your business with our loans', 'Quick approval process', 'Minimal documentation', 'Competitive interest rates', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=400&h=250&fit=crop', 100000, 5000000, '9% onwards', true, 4],
            ['Car Loan', 'fas fa-car', 'Drive your dream car today', 'Up to 90% vehicle value funding', 'New and used car financing', 'Flexible tenure options', 'https://images.unsplash.com/photo-1493238792000-8113da705763?w=400&h=250&fit=crop', 100000, 2000000, '8% onwards', false, 5]
        ];
        
        $stmt = $pdo->prepare("
            INSERT INTO loan_categories 
            (name, icon, description, key_point_1, key_point_2, key_point_3, image_url, min_amount, max_amount, interest_rate, is_featured, sort_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
        
        echo "Default loan categories inserted successfully.\n";
    } else {
        echo "Loan categories already exist.\n";
    }
    
    echo "\n=== PostgreSQL Database Setup Completed Successfully! ===\n";
    echo "Database: $database\n";
    echo "Admin Login:\n";
    echo "Username: admin\n";
    echo "Password: Harsh@9131\n";
    echo "Email: admin@jsmf.in\n\n";
    echo "Configuration file: config-postgresql.php\n";
    echo "Schema file: database-postgresql.sql\n";
    
} catch (PDOException $e) {
    echo "PostgreSQL Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>