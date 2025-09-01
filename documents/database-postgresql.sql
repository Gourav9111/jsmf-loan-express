-- PostgreSQL Database schema for Jay Shree Mahakal Finance Services

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Loan applications table
CREATE TABLE IF NOT EXISTS loan_applications (
    id SERIAL PRIMARY KEY,
    application_id VARCHAR(20) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    city VARCHAR(50) NOT NULL,
    loan_type VARCHAR(50) NOT NULL,
    loan_amount DECIMAL(12,2) NOT NULL,
    monthly_income DECIMAL(10,2) NOT NULL,
    pan_aadhar VARCHAR(50),
    status VARCHAR(20) CHECK (status IN ('Pending', 'Approved', 'Rejected', 'Processing')) DEFAULT 'Pending',
    assigned_dsa_id INTEGER NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create trigger for updated_at
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ language 'plpgsql';

CREATE TRIGGER update_loan_applications_updated_at 
    BEFORE UPDATE ON loan_applications 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- DSA users table
CREATE TABLE IF NOT EXISTS dsa_users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    experience VARCHAR(50) NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_pic VARCHAR(255),
    kyc_status VARCHAR(20) CHECK (kyc_status IN ('Pending', 'Approved', 'Rejected')) DEFAULT 'Pending',
    status VARCHAR(20) CHECK (status IN ('Active', 'Inactive')) DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    role VARCHAR(20) CHECK (role IN ('super', 'admin')) DEFAULT 'admin',
    status VARCHAR(20) CHECK (status IN ('Active', 'Inactive')) DEFAULT 'Active',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER update_admin_users_updated_at 
    BEFORE UPDATE ON admin_users 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Notifications table
CREATE TABLE IF NOT EXISTS notifications (
    id SERIAL PRIMARY KEY,
    message TEXT NOT NULL,
    target VARCHAR(20) CHECK (target IN ('dsa', 'all')) DEFAULT 'all',
    target_user_id INTEGER NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    message TEXT NOT NULL,
    status VARCHAR(20) CHECK (status IN ('New', 'Read', 'Replied')) DEFAULT 'New',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Lead assignments table
CREATE TABLE IF NOT EXISTS lead_assignments (
    id SERIAL PRIMARY KEY,
    application_id INTEGER NOT NULL,
    dsa_id INTEGER NOT NULL,
    assigned_by INTEGER NOT NULL,
    status VARCHAR(20) CHECK (status IN ('Assigned', 'In Progress', 'Completed', 'Follow-Up')) DEFAULT 'Assigned',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TRIGGER update_lead_assignments_updated_at 
    BEFORE UPDATE ON lead_assignments 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Loan categories table
CREATE TABLE IF NOT EXISTS loan_categories (
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
);

CREATE TRIGGER update_loan_categories_updated_at 
    BEFORE UPDATE ON loan_categories 
    FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();

-- Create foreign key constraints
ALTER TABLE loan_applications 
ADD CONSTRAINT fk_loan_app_dsa 
FOREIGN KEY (assigned_dsa_id) REFERENCES dsa_users(id) ON DELETE SET NULL;

ALTER TABLE lead_assignments 
ADD CONSTRAINT fk_lead_application 
FOREIGN KEY (application_id) REFERENCES loan_applications(id) ON DELETE CASCADE;

ALTER TABLE lead_assignments 
ADD CONSTRAINT fk_lead_dsa 
FOREIGN KEY (dsa_id) REFERENCES dsa_users(id) ON DELETE CASCADE;

ALTER TABLE lead_assignments 
ADD CONSTRAINT fk_lead_assigned_by 
FOREIGN KEY (assigned_by) REFERENCES admin_users(id) ON DELETE CASCADE;

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_loan_applications_status ON loan_applications(status);
CREATE INDEX IF NOT EXISTS idx_loan_applications_mobile ON loan_applications(mobile);
CREATE INDEX IF NOT EXISTS idx_loan_applications_application_id ON loan_applications(application_id);
CREATE INDEX IF NOT EXISTS idx_dsa_users_username ON dsa_users(username);
CREATE INDEX IF NOT EXISTS idx_admin_users_username ON admin_users(username);
CREATE INDEX IF NOT EXISTS idx_lead_assignments_dsa_id ON lead_assignments(dsa_id);
CREATE INDEX IF NOT EXISTS idx_lead_assignments_application_id ON lead_assignments(application_id);
CREATE INDEX IF NOT EXISTS idx_notifications_target ON notifications(target, target_user_id);
CREATE INDEX IF NOT EXISTS idx_loan_categories_active ON loan_categories(is_active, sort_order);

-- Create sequences explicitly if needed (PostgreSQL usually handles this automatically with SERIAL)
CREATE SEQUENCE IF NOT EXISTS loan_applications_id_seq;
CREATE SEQUENCE IF NOT EXISTS dsa_users_id_seq;
CREATE SEQUENCE IF NOT EXISTS admin_users_id_seq;
CREATE SEQUENCE IF NOT EXISTS notifications_id_seq;
CREATE SEQUENCE IF NOT EXISTS contact_messages_id_seq;
CREATE SEQUENCE IF NOT EXISTS lead_assignments_id_seq;
CREATE SEQUENCE IF NOT EXISTS loan_categories_id_seq;