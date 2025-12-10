<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 're_projects')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `location` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_area` decimal(15,2) DEFAULT NULL,
  `total_plots` int(11) DEFAULT 0,
  `status` enum("planning","active","completed","on_hold") DEFAULT "planning",
  `start_date` date DEFAULT NULL,
  `completion_date` date DEFAULT NULL,
  `project_manager_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `status` (`status`),
  KEY `project_manager_id` (`project_manager_id`)
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_blocks')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `total_plots` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `code` (`code`),
  CONSTRAINT `fk_blocks_project` FOREIGN KEY (`project_id`) REFERENCES `' . db_prefix() . 're_projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_plots')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_plots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `block_id` int(11) DEFAULT NULL,
  `plot_number` varchar(50) NOT NULL,
  `plot_type` varchar(50) DEFAULT NULL,
  `area` decimal(10,2) NOT NULL,
  `area_unit` varchar(20) DEFAULT "sqft",
  `facing` varchar(50) DEFAULT NULL,
  `rate_per_unit` decimal(15,2) NOT NULL,
  `total_price` decimal(15,2) NOT NULL,
  `status` enum("available","booked","sold","reserved","blocked") DEFAULT "available",
  `dimensions` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_plot` (`project_id`,`plot_number`),
  KEY `block_id` (`block_id`),
  KEY `status` (`status`),
  KEY `plot_type` (`plot_type`),
  CONSTRAINT `fk_plots_project` FOREIGN KEY (`project_id`) REFERENCES `' . db_prefix() . 're_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_plots_block` FOREIGN KEY (`block_id`) REFERENCES `' . db_prefix() . 're_blocks` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_bookings')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_code` varchar(50) NOT NULL,
  `plot_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_amount` decimal(15,2) NOT NULL,
  `total_amount` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) DEFAULT 0.00,
  `final_amount` decimal(15,2) NOT NULL,
  `payment_plan_id` int(11) DEFAULT NULL,
  `status` enum("pending","confirmed","cancelled","converted_to_sale") DEFAULT "pending",
  `cancellation_date` date DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `booking_code` (`booking_code`),
  KEY `plot_id` (`plot_id`),
  KEY `customer_id` (`customer_id`),
  KEY `status` (`status`),
  KEY `payment_plan_id` (`payment_plan_id`),
  CONSTRAINT `fk_bookings_plot` FOREIGN KEY (`plot_id`) REFERENCES `' . db_prefix() . 're_plots` (`id`) ON DELETE RESTRICT,
  CONSTRAINT `fk_bookings_customer` FOREIGN KEY (`customer_id`) REFERENCES `' . db_prefix() . 'clients` (`userid`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_payment_plans')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_payment_plans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `down_payment_percentage` decimal(5,2) DEFAULT NULL,
  `number_of_installments` int(11) DEFAULT NULL,
  `installment_frequency` enum("monthly","quarterly","yearly") DEFAULT "monthly",
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_booking_installments')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_booking_installments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `booking_id` int(11) NOT NULL,
  `installment_number` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `paid_amount` decimal(15,2) DEFAULT 0.00,
  `payment_date` date DEFAULT NULL,
  `invoice_id` int(11) DEFAULT NULL,
  `status` enum("pending","paid","overdue","waived") DEFAULT "pending",
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `status` (`status`),
  KEY `invoice_id` (`invoice_id`),
  KEY `due_date` (`due_date`),
  CONSTRAINT `fk_installments_booking` FOREIGN KEY (`booking_id`) REFERENCES `' . db_prefix() . 're_bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_installments_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `' . db_prefix() . 'invoices` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_team_assignments')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_team_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `role` varchar(100) DEFAULT NULL,
  `assigned_date` date NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  KEY `staff_id` (`staff_id`),
  CONSTRAINT `fk_team_project` FOREIGN KEY (`project_id`) REFERENCES `' . db_prefix() . 're_projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_team_staff` FOREIGN KEY (`staff_id`) REFERENCES `' . db_prefix() . 'staff` (`staffid`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_communications')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_communications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `related_to` enum("project","plot","booking","customer") NOT NULL,
  `related_id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `communication_type` enum("call","email","meeting","site_visit","other") DEFAULT "call",
  `subject` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `communication_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `related_to` (`related_to`,`related_id`),
  KEY `customer_id` (`customer_id`),
  KEY `staff_id` (`staff_id`),
  KEY `communication_date` (`communication_date`)
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 're_custom_fields_values')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . 're_custom_fields_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(100) NOT NULL,
  `field_value` text DEFAULT NULL,
  `related_to` varchar(50) NOT NULL,
  `related_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `related_to` (`related_to`,`related_id`)
) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
}

// Insert default payment plans
if ($CI->db->table_exists(db_prefix() . 're_payment_plans')) {
    $CI->db->query('INSERT INTO `' . db_prefix() . 're_payment_plans` (`name`, `description`, `down_payment_percentage`, `number_of_installments`, `installment_frequency`, `is_active`) VALUES
    ("One Time Payment", "Full payment at once", 100.00, 1, "monthly", 1),
    ("30-70 Plan", "30% down payment, 70% in installments", 30.00, 12, "monthly", 1),
    ("20-80 Plan", "20% down payment, 80% in installments", 20.00, 24, "monthly", 1),
    ("Quarterly Plan", "10% down payment, rest in quarterly installments", 10.00, 12, "quarterly", 1)
ON DUPLICATE KEY UPDATE name=name');
}
