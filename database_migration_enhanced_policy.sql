-- Database Migration Script for Enhanced Policy Financial Fields
-- This script adds new columns to the policy table without affecting existing data
-- Date: July 25, 2025

-- Add new financial columns to policy table
ALTER TABLE `policy` 
ADD COLUMN `payout` DECIMAL(15,2) DEFAULT NULL COMMENT 'Amount paid out to customer',
ADD COLUMN `customer_paid` DECIMAL(15,2) DEFAULT NULL COMMENT 'Amount actually paid by customer',
ADD COLUMN `discount` DECIMAL(15,2) DEFAULT NULL COMMENT 'Discount given (Premium - Customer Paid)',
ADD COLUMN `calculated_revenue` DECIMAL(15,2) DEFAULT NULL COMMENT 'New calculated revenue (Payout - Discount)',
ADD COLUMN `comments` TEXT DEFAULT NULL COMMENT 'Additional comments for policy',
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update timestamp';

-- Note: 
-- 1. Existing 'premium' and 'revenue' columns remain unchanged
-- 2. New logic will use 'calculated_revenue' for new policies
-- 3. Existing data will continue to use 'revenue' column
-- 4. All new columns are nullable to preserve existing data integrity
