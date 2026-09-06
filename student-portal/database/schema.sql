CREATE DATABASE IF NOT EXISTS student_portal
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE student_portal;

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) NOT NULL UNIQUE,
    full_name VARCHAR(150) NOT NULL,
    department VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    phone VARCHAR(30) UNIQUE,
    password VARCHAR(255) NOT NULL,
    cgpa DECIMAL(4,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    subject VARCHAR(150) NOT NULL,
    attended_classes INT NOT NULL DEFAULT 0,
    total_classes INT NOT NULL DEFAULT 0,
    percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    total_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    paid_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    due_fee DECIMAL(12,2) NOT NULL DEFAULT 0,
    status VARCHAR(30) NOT NULL DEFAULT 'Pending',
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    certificate_type VARCHAR(150) NOT NULL,
    certificate_number VARCHAR(100) NOT NULL UNIQUE,
    status VARCHAR(30) NOT NULL DEFAULT 'Available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS otp_verifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    otp_hash VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    attempts INT NOT NULL DEFAULT 0,
    verified TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Demo accounts.
-- Password for both demo student and demo admin: password
INSERT INTO students (student_id, full_name, department, email, phone, password, cgpa)
VALUES ('DEMO001', 'Demo Student', 'CSE (AI & ML)', 'student@example.com', '9999999999',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC4LJgkYpYQj8pR4m2u', 8.52)
ON DUPLICATE KEY UPDATE student_id=student_id;

INSERT INTO fees (student_id, total_fee, paid_fee, due_fee, status)
SELECT id, 120000, 100000, 20000, 'Pending'
FROM students WHERE email='student@example.com'
AND NOT EXISTS (SELECT 1 FROM fees f JOIN students s ON s.id=f.student_id WHERE s.email='student@example.com');

INSERT INTO attendance (student_id, subject, attended_classes, total_classes, percentage)
SELECT id, 'Machine Learning', 45, 50, 90.00 FROM students WHERE email='student@example.com'
AND NOT EXISTS (SELECT 1 FROM attendance a JOIN students s ON s.id=a.student_id WHERE s.email='student@example.com' AND a.subject='Machine Learning');

INSERT INTO attendance (student_id, subject, attended_classes, total_classes, percentage)
SELECT id, 'Database Management Systems', 40, 48, 83.33 FROM students WHERE email='student@example.com'
AND NOT EXISTS (SELECT 1 FROM attendance a JOIN students s ON s.id=a.student_id WHERE s.email='student@example.com' AND a.subject='Database Management Systems');

INSERT INTO notifications (student_id, title, message)
SELECT id, 'Welcome', 'Welcome to the Student Portal.'
FROM students WHERE email='student@example.com'
AND NOT EXISTS (SELECT 1 FROM notifications n JOIN students s ON s.id=n.student_id WHERE s.email='student@example.com');

INSERT INTO certificates (student_id, certificate_type, certificate_number)
SELECT id, 'Bonafide Certificate', 'CERT-DEMO-001'
FROM students WHERE email='student@example.com'
AND NOT EXISTS (SELECT 1 FROM certificates c JOIN students s ON s.id=c.student_id WHERE s.email='student@example.com');

INSERT INTO admin_users (name, email, password)
VALUES ('Administrator', 'admin@example.com',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC4LJgkYpYQj8pR4m2u')
ON DUPLICATE KEY UPDATE email=email;
