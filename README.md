# Digital NOC & QR Certificate Verification Portal

An enterprise digital platform developed for the **Bangladesh Sustainable and Renewable Energy Association (BSREA)** to automate the issuance, registration, and instant online verification of **No Objection Certificates (NOC)**, equipment compliance approvals, and membership certificates using tamper-proof cryptographic QR codes.

Developed by **MD. Mahamudul Hasan** (IT Officer Candidate).

---

## 🌟 Key Features

1. **Cryptographic QR Code Generation**: Automatically creates high-density SVG/Canvas QR codes containing tamper-resistant verification endpoints and SHA-256 document digests.
2. **Instant Mobile & Web Verification**: Public officials, port authorities, and utility grid operators can scan the QR code with any camera or enter the document number to verify authenticity in real-time.
3. **Database Architecture & Integrity**: Built with MySQL/MariaDB schema enforcing strict foreign key constraints, unique document numbers, issue/expiry tracking, and audit trails.
4. **Administrative NOC Issuance**: Intuitive interface for issuing new certificates, attaching applicant data, renewable energy sectors (Solar, Wind, Biomass, Hydro, Energy Efficiency), and setting validity periods.
5. **Print & PDF Export Ready**: Clean CSS print styling formatted for official association letterheads.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5 Semantic markup, Vanilla CSS3 (Custom Emerald Renewable Design System), JavaScript (ES6+), Web Crypto API.
- **Backend**: PHP 8.2 (RESTful JSON APIs, PDO secure parameterized statements).
- **Database**: MySQL / MariaDB (InnoDB engine, utf8mb4 character set).
- **Security**: SHA-256 cryptographic hash validation, input sanitization, and verification audit logging.

---

## 🚀 How to Run Locally

### Option 1: Standalone Browser Mode (Zero Setup)
Simply open `index.html` in any modern web browser (Google Chrome, Microsoft Edge, Firefox, Safari). The system includes an embedded client-side database and Web Crypto engine for immediate demonstration.

### Option 2: Full PHP & MySQL Setup
1. Import `backend/schema.sql` into your local MySQL database (e.g. via phpMyAdmin or MySQL CLI):
   ```sql
   mysql -u root -p < backend/schema.sql
   ```
2. Configure database credentials in `backend/db_connect.php`:
   ```php
   $host     = 'localhost';
   $db_name  = 'bsrea_digital_portal';
   $username = 'your_username';
   $password = 'your_password';
   ```
3. Start local PHP server:
   ```bash
   php -S localhost:8000
   ```
4. Access the portal at `http://localhost:8000/`.

---

## 📂 Project Structure

```
Digital_NOC_QR_Verification_Portal/
├── index.html               # Main verification & NOC issuance portal
├── css/
│   └── styles.css           # Modern BSREA emerald design system
├── js/
│   └── app.js              # Frontend logic, QR code generator, and verification engine
├── backend/
│   ├── schema.sql           # MySQL database schema & seed data
│   ├── db_connect.php       # Secure PDO database connector
│   └── api/
│       ├── verify.php       # Verification endpoint (by doc number or hash)
│       └── generate_noc.php # NOC generation endpoint with SHA-256
└── README.md                # Project documentation
```

---

## 🔗 How to Push to GitHub

To make this repository live on your GitHub account (`github.com/MHMITHUN`):

```bash
git init
git add .
git commit -m "feat: initial commit of BSREA Digital NOC and QR Verification Portal"
git branch -M main
git remote add origin https://github.com/MHMITHUN/Digital-NOC-QR-Verification-Portal.git
git push -u origin main
```
