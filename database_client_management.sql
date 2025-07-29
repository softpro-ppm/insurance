-- Client Management Database Table
-- This script creates the clients table with all necessary fields

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL UNIQUE,
  `phone` varchar(20) NOT NULL,
  `address` text,
  `dob` date,
  `policy_number` varchar(100) NOT NULL UNIQUE,
  `policy_type` varchar(100) NOT NULL,
  `premium_amount` decimal(10,2) NOT NULL,
  `status` enum('Active','Pending','Expired','Cancelled') DEFAULT 'Active',
  `policy_start_date` date,
  `policy_end_date` date,
  `aadhar_card` varchar(255),
  `pan_card` varchar(255),
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_email` (`email`),
  KEY `idx_policy_number` (`policy_number`),
  KEY `idx_status` (`status`),
  KEY `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data for testing
INSERT INTO `clients` (
  `client_name`, `email`, `phone`, `address`, `dob`, 
  `policy_number`, `policy_type`, `premium_amount`, `status`, 
  `policy_start_date`, `policy_end_date`
) VALUES 
('John Doe', 'john.doe@email.com', '9876543210', '123 Main Street, Mumbai', '1985-05-15', 
 'POL001', 'Life Insurance', 50000.00, 'Active', '2024-01-01', '2029-01-01'),
 
('Jane Smith', 'jane.smith@email.com', '9876543211', '456 Park Avenue, Delhi', '1990-03-22', 
 'POL002', 'Health Insurance', 25000.00, 'Active', '2024-02-01', '2025-02-01'),
 
('Robert Johnson', 'robert.j@email.com', '9876543212', '789 Garden Street, Bangalore', '1988-07-10', 
 'POL003', 'Motor Insurance', 15000.00, 'Pending', '2024-03-01', '2025-03-01'),
 
('Sarah Wilson', 'sarah.wilson@email.com', '9876543213', '321 Lake View, Chennai', '1992-11-05', 
 'POL004', 'Home Insurance', 30000.00, 'Active', '2024-01-15', '2025-01-15'),
 
('Michael Brown', 'michael.brown@email.com', '9876543214', '654 Hill Station, Pune', '1987-09-18', 
 'POL005', 'Travel Insurance', 8000.00, 'Expired', '2023-06-01', '2024-06-01');

-- Create uploads directory structure (Note: This needs to be done manually or via PHP)
-- uploads/
-- └── documents/
