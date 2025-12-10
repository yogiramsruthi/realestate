-- Real Estate Module - Manual Installation Script
-- Run this if automatic activation fails

USE test_real;

-- Check if module is already activated
SELECT * FROM tbloptions WHERE name = 'module_real_estat_activated';

-- If not activated, you can activate it manually by:
-- 1. Going to Setup → Modules in Perfex admin
-- 2. Click "Activate" button next to "Real Estate Management"

-- Verify tables were created
SHOW TABLES LIKE 'tbl_re_%';

-- Check table structure
DESCRIBE tbl_re_projects;
DESCRIBE tbl_re_blocks;
DESCRIBE tbl_re_plots;
DESCRIBE tbl_re_bookings;
DESCRIBE tbl_re_payment_plans;
DESCRIBE tbl_re_booking_installments;
DESCRIBE tbl_re_team_assignments;
DESCRIBE tbl_re_communications;
DESCRIBE tbl_re_custom_fields_values;

-- Verify default payment plans
SELECT * FROM tbl_re_payment_plans;

-- Check if there are any sample projects (should be empty initially)
SELECT COUNT(*) as total_projects FROM tbl_re_projects;
SELECT COUNT(*) as total_plots FROM tbl_re_plots;
SELECT COUNT(*) as total_bookings FROM tbl_re_bookings;

-- Grant permissions to Administrator role (ID usually 1)
-- These will be added automatically when you configure in admin panel

-- Verify Perfex CRM is working
SELECT * FROM tblstaff WHERE admin = 1 LIMIT 1;
SELECT * FROM tblclients LIMIT 5;

-- Check module file exists
-- File should be at: c:/xampp/htdocs/test_real/modules/real_estat/real_estat.php

-- Module activation checklist:
-- ✓ Tables created (9 tables with tbl_re_ prefix)
-- ✓ Payment plans inserted (4 default plans)
-- ✓ Module appears in Setup → Modules
-- ✓ Permissions configured for staff roles
-- ✓ Menu items appear in sidebar after activation

-- If you need to reset/reinstall the module:
/*
DROP TABLE IF EXISTS tbl_re_custom_fields_values;
DROP TABLE IF EXISTS tbl_re_communications;
DROP TABLE IF EXISTS tbl_re_team_assignments;
DROP TABLE IF EXISTS tbl_re_booking_installments;
DROP TABLE IF EXISTS tbl_re_payment_plans;
DROP TABLE IF EXISTS tbl_re_bookings;
DROP TABLE IF EXISTS tbl_re_plots;
DROP TABLE IF EXISTS tbl_re_blocks;
DROP TABLE IF EXISTS tbl_re_projects;

-- Then reactivate the module from Perfex admin panel
*/
