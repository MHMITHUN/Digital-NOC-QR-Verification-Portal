-- BSREA Digital NOC & Certificate Verification Database Schema
-- Database: bsrea_digital_portal
-- Author: MD. Mahamudul Hasan (IT Officer Candidate)

CREATE DATABASE IF NOT EXISTS `bsrea_digital_portal` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bsrea_digital_portal`;

-- 1. Organizations / Members Table
CREATE TABLE IF NOT EXISTS `tbl_members` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `member_code` VARCHAR(50) NOT NULL UNIQUE,
  `organization_name` VARCHAR(255) NOT NULL,
  `sector` ENUM('Solar Energy', 'Wind Energy', 'Biomass & Biofuel', 'Hydro & Microgrid', 'Energy Efficiency', 'Electric Mobility') NOT NULL,
  `contact_person` VARCHAR(150) NOT NULL,
  `contact_email` VARCHAR(150) NOT NULL,
  `contact_phone` VARCHAR(50) NOT NULL,
  `office_address` TEXT NOT NULL,
  `membership_type` ENUM('Corporate Member', 'Associate Member', 'Institutional Member') DEFAULT 'Corporate Member',
  `status` ENUM('Active', 'Pending', 'Suspended', 'Expired') DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Digital NOC & Certificates Table
CREATE TABLE IF NOT EXISTS `tbl_noc_certificates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `document_number` VARCHAR(60) NOT NULL UNIQUE,
  `member_id` INT NOT NULL,
  `document_type` ENUM('NOC - Grid Interconnection', 'NOC - Equipment Import', 'Membership Certificate', 'Renewable Energy Compliance') NOT NULL,
  `purpose_title` VARCHAR(255) NOT NULL,
  `authorized_representative` VARCHAR(150) NOT NULL,
  `issue_date` DATE NOT NULL,
  `valid_until` DATE NOT NULL,
  `issuing_authority` VARCHAR(255) DEFAULT 'BSREA Secretariat, Gulshan-2, Dhaka',
  `qr_payload` TEXT NOT NULL,
  `cryptographic_hash` VARCHAR(128) NOT NULL UNIQUE,
  `verification_url` VARCHAR(255) NOT NULL,
  `status` ENUM('Valid', 'Revoked', 'Expired') DEFAULT 'Valid',
  `created_by` VARCHAR(100) DEFAULT 'IT Officer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_noc_member` FOREIGN KEY (`member_id`) REFERENCES `tbl_members` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Verification Audit Logs Table
CREATE TABLE IF NOT EXISTS `tbl_verification_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `document_number` VARCHAR(60) NOT NULL,
  `verified_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` TEXT DEFAULT NULL,
  `verification_result` ENUM('Verified Authentic', 'Revoked', 'Not Found', 'Expired') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample Seed Data for Bangladesh Renewable Energy Association
INSERT INTO `tbl_members` (`member_code`, `organization_name`, `sector`, `contact_person`, `contact_email`, `contact_phone`, `office_address`, `membership_type`, `status`) VALUES
('BSREA-CORP-011', 'Rahimafrooz Renewable Energy Ltd.', 'Solar Energy', 'Engr. M. Rahman', 'info@rahimafrooz-renewable.com', '+880 1711 000111', 'Arzed Chamber, Mohakhali / Gulshan, Dhaka', 'Corporate Member', 'Active'),
('BSREA-CORP-024', 'Solaric Bangladesh Limited', 'Solar Energy', 'Tanvir Hossain', 'support@solaricbd.com', '+880 1712 222333', 'Baridhara DOHS, Dhaka', 'Corporate Member', 'Active'),
('BSREA-ASSOC-038', 'Green Energy Engineering BD', 'Wind Energy', 'Engr. Kamal Uddin', 'contact@greenenergybd.org', '+880 1819 444555', 'Sector-4, Uttara, Dhaka', 'Associate Member', 'Active'),
('BSREA-CORP-052', 'Desh BioPower Systems PLC', 'Biomass & Biofuel', 'Dr. M. A. Karim', 'biopower@deshenergy.com', '+880 1713 555666', 'Tejgaon I/A, Dhaka', 'Corporate Member', 'Active');

INSERT INTO `tbl_noc_certificates` (`document_number`, `member_id`, `document_type`, `purpose_title`, `authorized_representative`, `issue_date`, `valid_until`, `issuing_authority`, `qr_payload`, `cryptographic_hash`, `verification_url`, `status`) VALUES
('NOC-2026-SOLAR-084', 1, 'NOC - Grid Interconnection', 'Commercial 5MW Solar PV Grid Interconnection Authorization', 'Engr. M. Rahman', '2026-10-12', '2027-10-11', 'BSREA Secretariat, Gulshan-2, Dhaka', 'https://verify.bsreabd.org/verify?doc=NOC-2026-SOLAR-084&hash=e89c31fa780d6b2c45e12aa4f8b910e5', 'e89c31fa780d6b2c45e12aa4f8b910e5893a201bfd90a1bc34e56f7890123abc', 'https://verify.bsreabd.org/verify?doc=NOC-2026-SOLAR-084', 'Valid'),
('CERT-BSREA-MEM-129', 2, 'Membership Certificate', 'Certified Renewable Equipment Manufacturer & Solar EPC Provider', 'Tanvir Hossain', '2026-08-05', '2027-12-31', 'BSREA Secretariat, Gulshan-2, Dhaka', 'https://verify.bsreabd.org/verify?doc=CERT-BSREA-MEM-129&hash=4a1b9c77e201f654aa39c09d84fbb128', '4a1b9c77e201f654aa39c09d84fbb128562cd99a01234ef987654321fedcba01', 'https://verify.bsreabd.org/verify?doc=CERT-BSREA-MEM-129', 'Valid');
