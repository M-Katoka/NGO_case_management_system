-- ==========================================================
-- NGO Case Management Information System Database Schema
-- ==========================================================

CREATE DATABASE IF NOT EXISTS ngo_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ngo_system;

-- 1. Users Table (Role-Based Access Control: Admin, Supervisor, Management, CaseWorker)
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Supervisor', 'Management', 'CaseWorker') NOT NULL DEFAULT 'CaseWorker',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Beneficiaries Table
CREATE TABLE IF NOT EXISTS beneficiaries (
    beneficiary_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL,
    sex ENUM('Male', 'Female', 'Other') NOT NULL,
    contact_info VARCHAR(100) DEFAULT NULL,
    location VARCHAR(150) NOT NULL,
    registration_date DATE NOT NULL,
    registered_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (registered_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 3. Cases Table
CREATE TABLE IF NOT EXISTS cases (
    case_id INT AUTO_INCREMENT PRIMARY KEY,
    beneficiary_id INT NOT NULL,
    case_type VARCHAR(100) NOT NULL,
    priority ENUM('Low', 'Medium', 'High', 'Urgent') NOT NULL DEFAULT 'Medium',
    assigned_worker_id INT DEFAULT NULL,
    date_opened DATE NOT NULL,
    case_status ENUM('Open', 'Under Review', 'Escalated', 'Closed') NOT NULL DEFAULT 'Open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (beneficiary_id) REFERENCES beneficiaries(beneficiary_id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_worker_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. Assessments Table
CREATE TABLE IF NOT EXISTS assessments (
    assessment_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    assessed_by INT DEFAULT NULL,
    assessment_date DATE NOT NULL,
    identified_needs TEXT NOT NULL,
    risk_level ENUM('Low', 'Moderate', 'High', 'Severe') NOT NULL,
    observations TEXT DEFAULT NULL,
    recommendations TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(case_id) ON DELETE CASCADE,
    FOREIGN KEY (assessed_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 5. Interventions Table
CREATE TABLE IF NOT EXISTS interventions (
    intervention_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    service_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    service_provider VARCHAR(150) NOT NULL,
    intervention_date DATE NOT NULL,
    outcome TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(case_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Referrals Table
CREATE TABLE IF NOT EXISTS referrals (
    referral_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    referred_to_organization VARCHAR(150) NOT NULL,
    service_required VARCHAR(150) NOT NULL,
    referral_date DATE NOT NULL,
    referral_status ENUM('Pending', 'Completed', 'Cancelled') NOT NULL DEFAULT 'Pending',
    completion_info TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(case_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 7. Follow-Ups Table
CREATE TABLE IF NOT EXISTS follow_ups (
    follow_up_id INT AUTO_INCREMENT PRIMARY KEY,
    case_id INT NOT NULL,
    next_follow_up_date DATE NOT NULL,
    notes TEXT DEFAULT NULL,
    status ENUM('Scheduled', 'Completed', 'Missed') NOT NULL DEFAULT 'Scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (case_id) REFERENCES cases(case_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================================
-- Default Insert: Administrator Account
-- Password is: password123
-- ==========================================================
INSERT INTO users (username, email, password_hash, role) 
VALUES ('admin_mwisa', 'admin@ngo.org', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin');