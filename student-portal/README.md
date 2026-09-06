# Student Portal

A beginner-friendly PHP + MySQL student academic portal.

## Features

- Email or phone login
- Password hashing
- OTP verification flow
- Student dashboard
- Profile
- Attendance
- CGPA
- Fee dues
- Notifications
- Certificate PDF download
- Admin dashboard
- Admin attendance and fee management
- Admin notifications
- CSRF tokens and prepared SQL statements

## Requirements

- PHP 8.1+
- MySQL 8+ or MariaDB
- Apache/XAMPP
- Git

## Local setup

1. Copy this project into XAMPP `htdocs/student-portal`.
2. Start Apache and MySQL.
3. Open phpMyAdmin.
4. Import `database/schema.sql`.
5. Open `http://localhost/student-portal/`.
6. Demo student:
   - Email: `student@example.com`
   - Phone: `9999999999`
   - Password: `password`
   - The OTP is shown on-screen in development mode.
7. Demo admin:
   - Email: `admin@example.com`
   - Password: `password`

## Dataset import

Export the Apple Numbers file to CSV and map it to:

- student_id
- full_name
- department
- email
- phone
- cgpa

Do not commit real student data or plain-text passwords to GitHub.

## Production

- Set DB_HOST, DB_NAME, DB_USER and DB_PASS as environment variables.
- Set `DEV_SHOW_OTP` to false.
- Integrate a real email/SMS OTP provider.
- Use HTTPS.
- Change/remove demo credentials.
- Use a proper PDF library such as Dompdf for production certificates.
- Back up the production database.

## Important

This starter project is suitable for learning and prototyping. Before real college deployment, perform a security review and add rate limiting, audit logs, stronger authorization controls, production OTP delivery, secure cookie settings, backups and proper certificate storage.
