-- Add policy_documents table for storing Aadhar and PAN card images
-- Run this script to add document management functionality

CREATE TABLE IF NOT EXISTS `policy_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `policy_id` int(11) NOT NULL,
  `document_type` enum('aadhar_card','pan_card') NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_policy_id` (`policy_id`),
  KEY `idx_document_type` (`document_type`),
  FOREIGN KEY (`policy_id`) REFERENCES `policy`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create upload directories (these will be created by PHP if they don't exist)
-- ../assets/uploads/aadhar/
-- ../assets/uploads/pan/
