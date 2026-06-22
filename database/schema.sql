CREATE DATABASE IF NOT EXISTS course_enrollment_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE course_enrollment_app;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL, role ENUM('admin', 'staff') DEFAULT 'staff',
  status ENUM('active', 'inactive') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL, email VARCHAR(150) NOT NULL, phone VARCHAR(30),
  status VARCHAR(30) NOT NULL DEFAULT 'new', note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
  UNIQUE KEY unique_student_email (email),
  INDEX idx_students_created_at (created_at), INDEX idx_students_status_created_at (status, created_at)
);

CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  enrollment_code VARCHAR(50) NOT NULL, student_email VARCHAR(150) NOT NULL,
  course_name VARCHAR(200) NOT NULL, total_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
  status VARCHAR(30) NOT NULL DEFAULT 'pending',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, updated_at DATETIME NULL,
  UNIQUE KEY unique_enrollment_code (enrollment_code),
  INDEX idx_enrollments_created_at (created_at)
);

-- Seed data
INSERT INTO students (full_name, email, phone, status) VALUES 
('Anna Nguyen', 'anna@example.com', '0909000001', 'new'), ('Ben Tran', 'ben@example.com', '0909000002', 'contacted'),
('Chris Le', 'chris@example.com', '0909000003', 'new'), ('Duyen Pham', 'duyen@example.com', '0909000004', 'new');

INSERT INTO enrollments (enrollment_code, student_email, course_name, total_fee, status) VALUES 
('ENR-001', 'anna@example.com', 'PHP Master', 2500000, 'pending'), 
('ENR-002', 'ben@example.com', 'Data Science 101', 3000000, 'paid');