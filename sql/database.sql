CREATE DATABASE IF NOT EXISTS internal_asset_exchange_db;
USE internal_asset_exchange_db;

CREATE TABLE companies (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  company_code VARCHAR(50) UNIQUE NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('leader','member') DEFAULT 'member',
  department VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
  UNIQUE KEY unique_email_per_company (company_id, email)
);

CREATE TABLE assets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  user_assigned_id INT NULL,
  added_by INT NULL,
  name VARCHAR(200) NOT NULL,
  category VARCHAR(100),
  description TEXT,
  value DECIMAL(10,2) NOT NULL,
  `condition` VARCHAR(50),
  image_path VARCHAR(255),
  status ENUM('available','assigned','pending_transfer') DEFAULT 'available',
  department VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
  FOREIGN KEY (user_assigned_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (added_by) REFERENCES users(id),
  INDEX idx_status (status),
  INDEX idx_company (company_id)
);

CREATE TABLE transfer_log (
  id INT AUTO_INCREMENT PRIMARY KEY,
  company_id INT NOT NULL,
  asset_id INT NOT NULL,
  requester_id INT NOT NULL,
  approver_id INT NULL,
  request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  approval_date TIMESTAMP NULL,
  status ENUM('pending','approved','rejected') DEFAULT 'pending',
  from_department VARCHAR(100),
  to_department VARCHAR(100),
  asset_value DECIMAL(10,2),
  FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
  FOREIGN KEY (asset_id) REFERENCES assets(id),
  FOREIGN KEY (requester_id) REFERENCES users(id),
  FOREIGN KEY (approver_id) REFERENCES users(id),
  INDEX idx_status (status)
);