# Digital NOC & QR Certificate Verification Portal

[![Live Demo](https://img.shields.io/badge/Live%20Demo-GitHub%20Pages-emerald?style=for-the-badge&logo=github)](https://mhmithun.github.io/Digital-NOC-QR-Verification-Portal/)
[![Database](https://img.shields.io/badge/Database-MongoDB%20Atlas-forestgreen?style=for-the-badge&logo=mongodb)](https://www.mongodb.com/cloud/atlas)
[![Serverless](https://img.shields.io/badge/Serverless-Vercel%20%2F%20Netlify-black?style=for-the-badge&logo=vercel)](https://vercel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue?style=for-the-badge)](LICENSE)

An enterprise digital platform developed for the **Bangladesh Sustainable and Renewable Energy Association (BSREA)** to automate the issuance, registration, and cryptographic verification of **No Objection Certificates (NOC)**, equipment compliance approvals, and institutional credentials using tamper-proof cryptographic QR codes.

Engineered with a **Cybersecurity Dark Vault UI** (Midnight Obsidian & Electric Cyan), real-time optical camera scanner simulation, and SHA-256 digest breakdown.

Developed by **MD. Mahamudul Hasan**.

---

## 🌐 Live Access & Deployment

- **Public Live Application**: [https://mhmithun.github.io/Digital-NOC-QR-Verification-Portal/](https://mhmithun.github.io/Digital-NOC-QR-Verification-Portal/)
- **GitHub Repository**: [https://github.com/MHMITHUN/Digital-NOC-QR-Verification-Portal](https://github.com/MHMITHUN/Digital-NOC-QR-Verification-Portal)
- **Production Database**: MongoDB Atlas Dedicated Cluster (`bsrea_portal` database with IP whitelist `0.0.0.0/0`)
- **Serverless API Ready**: Supports Vercel & Netlify Serverless Node.js runtime (`api/verify.js`, `api/generate_noc.js`)

---

## 🌟 Key Features

1. **Dual Verification Experience**:
   - **Mode 1 (Manual Lookup)**: Instant credential search by Document ID (e.g. `NOC-2026-SOLAR-084`) or SHA-256 hash.
   - **Mode 2 (Optical QR Scanner)**: Simulated real-time camera viewfinder reticle with sweeping cyan laser detection beam.
2. **Cryptographic QR Code Generation**: Automatically creates high-density SVG/Canvas QR codes containing tamper-resistant verification endpoints and SHA-256 document digests.
3. **Hardware-Accelerated Web Crypto API**: Generates real-time 256-bit hash digests on document creation and verifies tamper integrity.
4. **Live Security Gateway Audit Log**: Streaming verification events with origin IP, latency metrics, and cryptographic audit badges.
5. **Dual Database Architecture**: 
   - **MongoDB Atlas Cloud**: Connected to active Atlas Cluster via MongoDB Node.js Driver (`noc_certificates`, `noc_records`, `verification_logs`).
   - **MySQL / MariaDB**: Enterprise relational schema with foreign key constraints, issue/expiry tracking, and audit trails.
6. **Print & PDF Export Ready**: Official holographic seal badge and printable certificate format.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5 Semantic markup, High-Security Cyber Vault CSS3 (Midnight Obsidian `#070b14` & Electric Cyan `#06b6d4`), JavaScript (ES6+), Web Crypto API.
- **Cloud Database**: MongoDB Atlas (Cloud Cluster, Global IP `0.0.0.0/0`, authenticated connection).
- **Relational Backend**: PHP 8.2 (RESTful JSON APIs, PDO secure parameterized statements, MySQL/MariaDB).
- **Serverless Runtime**: Node.js 20+ on Vercel / Netlify Serverless Functions (`api/*.js`).
- **Security**: SHA-256 cryptographic hash validation, input sanitization, and verification audit logging.

---

## 🚀 How to Run Locally

### Option 1: Live Web / Browser (Zero Setup)
Visit the live portal at [https://mhmithun.github.io/Digital-NOC-QR-Verification-Portal/](https://mhmithun.github.io/Digital-NOC-QR-Verification-Portal/) or open `index.html` locally in any browser.

### Option 2: Node.js & MongoDB Atlas Serverless API
1. Install dependencies:
   ```bash
   npm install
   ```
2. Configure `.env` (copy from `.env.example`):
   ```env
   MONGODB_URI=your_mongodb_atlas_connection_string
   PORT=3000
   ```
3. Run the development server:
   ```bash
   npm start
   ```

### Option 3: PHP & MySQL Local Setup
1. Import `backend/schema.sql` into MySQL:
   ```sql
   mysql -u root -p < backend/schema.sql
   ```
2. Configure credentials in `backend/db_connect.php`:
   ```php
   $host     = 'localhost';
   $db_name  = 'bsrea_digital_portal';
   $username = 'your_username';
   $password = 'your_password';
   ```
3. Start PHP server:
   ```bash
   php -S localhost:8000
   ```

---

## 📂 Project Structure

```
Digital_NOC_QR_Verification_Portal/
├── index.html               # Main verification & NOC issuance portal UI
├── css/
│   └── styles.css           # Modern BSREA emerald design system
├── js/
│   └── app.js              # Frontend logic, QR code generator, and verification engine
├── api/                     # Cloud Serverless API (Vercel / Netlify)
│   ├── db.js                # MongoDB Atlas connection handler
│   ├── verify.js            # /api/verify endpoint with MongoDB query & logging
│   └── generate_noc.js      # /api/generate_noc endpoint with SHA-256
├── backend/                 # Relational PHP Backend
│   ├── schema.sql           # MySQL database schema & seed data
│   ├── db_connect.php       # Secure PDO database connector
│   └── api/
│       ├── verify.php       # PHP verification endpoint
│       └── generate_noc.php # PHP NOC generation endpoint
├── vercel.json              # Vercel deployment configuration
├── netlify.toml             # Netlify deployment configuration
├── package.json             # Node.js dependencies (mongodb driver)
└── README.md                # Project documentation
```

---

## 👨‍💻 Author & Maintainer

- **Developer**: MD. Mahamudul Hasan (Mithun)
- **GitHub**: [github.com/MHMITHUN](https://github.com/MHMITHUN)
- **LinkedIn**: [linkedin.com/in/mahamudul0](https://linkedin.com/in/mahamudul0)
- **Email**: mhmmithun1@gmail.com
