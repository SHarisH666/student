[README.md](https://github.com/user-attachments/files/31883789/README.md)
# Student Portal

Run:
1. `python -m venv venv`
2. Activate the environment.
3. `pip install -r requirements.txt`
4. `python app.py`
5. Open `http://127.0.0.1:5000`

The supplied CSV is used for roll number, password, name, mobile, email, department and CGPA.
Attendance and fee dues are not present in the supplied CSV, so this demo stores them separately in `data/student_extra.json`.
The OTP is printed in the Flask terminal. For production, connect an SMS/email OTP provider.

Security before real deployment:
- hash passwords with Argon2/bcrypt
- move data to PostgreSQL/MySQL
- use HTTPS and a strong SECRET_KEY
- add rate limiting/lockout and audit logging
- never expose the original plaintext-password CSV to the browser
- connect attendance/fees/certificates to the official college ERP
