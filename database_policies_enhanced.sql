-- Enhanced Policies Table Structure for Bootstrap 5 Application
-- This file creates/updates the policies table with all required fields

-- Create or update policies table
CREATE TABLE IF NOT EXISTS `policies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vehicle_number` varchar(20) NOT NULL,
  `vehicle_type` varchar(50) NOT NULL,
  `engine_number` varchar(50) DEFAULT NULL,
  `chassis_number` varchar(50) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `policy_type` varchar(50) NOT NULL,
  `insurance_company` varchar(100) NOT NULL,
  `premium` decimal(10,2) NOT NULL,
  `policy_start_date` date NOT NULL,
  `policy_end_date` date NOT NULL,
  `policy_number` varchar(100) DEFAULT NULL,
  `aadhar_card` varchar(255) DEFAULT NULL,
  `pan_card` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `created_by` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  INDEX `idx_vehicle_number` (`vehicle_number`),
  INDEX `idx_client_phone` (`phone`),
  INDEX `idx_policy_dates` (`policy_start_date`, `policy_end_date`),
  INDEX `idx_insurance_company` (`insurance_company`),
  INDEX `idx_policy_type` (`policy_type`),
  INDEX `idx_vehicle_type` (`vehicle_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data if table is empty
INSERT IGNORE INTO `policies` (
  `id`, `vehicle_number`, `vehicle_type`, `engine_number`, `chassis_number`, 
  `name`, `phone`, `email`, `address`, `policy_type`, `insurance_company`, 
  `premium`, `policy_start_date`, `policy_end_date`, `policy_number`, 
  `remarks`, `created_at`
) VALUES
(1, 'KA05HB1234', 'Four Wheeler', 'ENG123456', 'CH789012', 'Rajesh Kumar', '9876543210', 'rajesh@email.com', '123 Brigade Road, Bangalore', 'Comprehensive', 'Bajaj Allianz', 15000.00, '2024-01-15', '2025-01-14', 'POL001234', 'First policy entry', '2024-01-15 10:30:00'),
(2, 'KA01AA9999', 'Two Wheeler', 'ENG654321', 'CH345678', 'Priya Sharma', '9123456780', 'priya@email.com', '45 MG Road, Bangalore', 'Third Party', 'HDFC ERGO', 2500.00, '2024-02-20', '2025-02-19', 'POL002456', 'Two wheeler policy', '2024-02-20 14:15:00'),
(3, 'KA02BC5678', 'Four Wheeler', 'ENG789123', 'CH901234', 'Amit Patel', '9988776655', 'amit@email.com', '78 Whitefield, Bangalore', 'Comprehensive', 'ICICI Lombard', 18500.00, '2024-03-10', '2025-03-09', 'POL003789', 'Premium policy with full coverage', '2024-03-10 09:45:00'),
(4, 'KA03DD1111', 'Commercial Vehicle', 'ENG456789', 'CH567890', 'Sunita Reddy', '9876012345', 'sunita@email.com', '90 Electronic City, Bangalore', 'Third Party', 'Oriental Insurance', 12000.00, '2024-04-05', '2025-04-04', 'POL004012', 'Commercial vehicle insurance', '2024-04-05 16:20:00'),
(5, 'KA04EE7777', 'Two Wheeler', 'ENG321654', 'CH654321', 'Vikram Singh', '9123098765', 'vikram@email.com', '25 Koramangala, Bangalore', 'Comprehensive', 'Bharti AXA', 3500.00, '2024-01-01', '2024-12-31', 'POL005345', 'Expiring soon policy', '2024-01-01 11:00:00'),
(6, 'KA06FF2222', 'Four Wheeler', 'ENG987654', 'CH987654', 'Deepika Nair', '9876501234', 'deepika@email.com', '67 Indiranagar, Bangalore', 'Stand Alone OD', 'SBI General', 8500.00, '2025-06-15', '2026-06-14', 'POL006678', 'Future policy', '2024-05-15 13:30:00');

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS `idx_policies_search` ON `policies` (`vehicle_number`, `name`, `phone`);
CREATE INDEX IF NOT EXISTS `idx_policies_dates_status` ON `policies` (`policy_end_date`, `status`);
CREATE INDEX IF NOT EXISTS `idx_policies_company_type` ON `policies` (`insurance_company`, `policy_type`);

-- Add any missing columns to existing table (for upgrades)
ALTER TABLE `policies` 
ADD COLUMN IF NOT EXISTS `engine_number` varchar(50) DEFAULT NULL AFTER `vehicle_type`,
ADD COLUMN IF NOT EXISTS `chassis_number` varchar(50) DEFAULT NULL AFTER `engine_number`,
ADD COLUMN IF NOT EXISTS `email` varchar(100) DEFAULT NULL AFTER `phone`,
ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL AFTER `email`,
ADD COLUMN IF NOT EXISTS `policy_number` varchar(100) DEFAULT NULL AFTER `policy_end_date`,
ADD COLUMN IF NOT EXISTS `aadhar_card` varchar(255) DEFAULT NULL AFTER `policy_number`,
ADD COLUMN IF NOT EXISTS `pan_card` varchar(255) DEFAULT NULL AFTER `aadhar_card`,
ADD COLUMN IF NOT EXISTS `remarks` text DEFAULT NULL AFTER `pan_card`,
ADD COLUMN IF NOT EXISTS `status` enum('active','expired','cancelled') DEFAULT 'active' AFTER `remarks`,
ADD COLUMN IF NOT EXISTS `created_by` int(11) DEFAULT 1 AFTER `status`,
ADD COLUMN IF NOT EXISTS `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `created_by`,
ADD COLUMN IF NOT EXISTS `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Create or update the connection variable for the new system
SET @sql = 'CREATE TABLE IF NOT EXISTS `config_variables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `variable_name` varchar(100) NOT NULL,
  `variable_value` text,
  `description` text,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `variable_name` (`variable_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4';

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Insert configuration for the connection object name
INSERT IGNORE INTO `config_variables` (`variable_name`, `variable_value`, `description`) 
VALUES ('db_connection_var', 'conn', 'Database connection variable name used in the application');

-- Ensure we have the correct connection variable
SET @connection_var = (SELECT variable_value FROM config_variables WHERE variable_name = 'db_connection_var');
