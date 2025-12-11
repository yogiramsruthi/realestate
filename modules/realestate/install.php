<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'realestate_projects')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_projects` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `project_code` varchar(50) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `status` varchar(50) DEFAULT 'draft',
        `project_manager` int(11) DEFAULT NULL,
        `total_plots` int(11) DEFAULT 0,
        `available_plots` int(11) DEFAULT 0,
        `project_type` varchar(100) DEFAULT NULL,
        `start_date` date DEFAULT NULL,
        `end_date` date DEFAULT NULL,
        `location` varchar(255) DEFAULT NULL,
        `district` varchar(100) DEFAULT NULL,
        `area_taluk` varchar(100) DEFAULT NULL,
        `village` varchar(100) DEFAULT NULL,
        `location_map_url` text DEFAULT NULL,
        `nearby_landmarks` text DEFAULT NULL,
        `total_owners` int(11) DEFAULT 0,
        `power_of_attorney` varchar(255) DEFAULT NULL,
        `total_acres` decimal(10,2) DEFAULT 0.00,
        `total_sqft` decimal(15,2) DEFAULT 0.00,
        `total_approved_sqft` decimal(15,2) DEFAULT 0.00,
        `owner_price_per_sqft` decimal(15,2) DEFAULT 0.00,
        `min_selling_price_per_sqft` decimal(15,2) DEFAULT 0.00,
        `max_selling_price_per_sqft` decimal(15,2) DEFAULT 0.00,
        `commission_type` varchar(50) DEFAULT NULL,
        `commission_percentage` decimal(5,2) DEFAULT 0.00,
        `dtcp_approval` tinyint(1) DEFAULT 0,
        `rera_approval` tinyint(1) DEFAULT 0,
        `bdo_approval` tinyint(1) DEFAULT 0,
        `panchayath_78_go` tinyint(1) DEFAULT 0,
        `farm_land` tinyint(1) DEFAULT 0,
        `cmda_approval` tinyint(1) DEFAULT 0,
        `other_approvals` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `project_code` (`project_code`),
        KEY `project_manager` (`project_manager`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_plots')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_plots` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `project_id` int(11) NOT NULL,
        `plot_number` varchar(100) NOT NULL,
        `plot_size` varchar(100) DEFAULT NULL,
        `plot_type` varchar(100) DEFAULT NULL,
        `price` decimal(15,2) DEFAULT 0.00,
        `price_per_sqft` decimal(15,2) DEFAULT 0.00,
        `status` varchar(50) DEFAULT 'available',
        `dimension` varchar(100) DEFAULT NULL,
        `facing` varchar(50) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `corner_plot` tinyint(1) DEFAULT 0,
        `main_road_facing` tinyint(1) DEFAULT 0,
        `road_width` varchar(50) DEFAULT NULL,
        `soil_type` varchar(100) DEFAULT NULL,
        `elevation` varchar(100) DEFAULT NULL,
        `drainage` varchar(100) DEFAULT NULL,
        `water_connection` tinyint(1) DEFAULT 0,
        `electricity_connection` tinyint(1) DEFAULT 0,
        `sewage_connection` tinyint(1) DEFAULT 0,
        `road_access` varchar(50) DEFAULT NULL,
        `nearby_amenities` text DEFAULT NULL,
        `plot_category` varchar(50) DEFAULT 'standard',
        `latitude` varchar(50) DEFAULT NULL,
        `longitude` varchar(50) DEFAULT NULL,
        `corner_coordinates` text DEFAULT NULL,
        `plot_map_image` varchar(255) DEFAULT NULL,
        `reservation_expiry` datetime DEFAULT NULL,
        `token_amount` decimal(15,2) DEFAULT 0.00,
        `discount_percentage` decimal(5,2) DEFAULT 0.00,
        `discount_amount` decimal(15,2) DEFAULT 0.00,
        `final_price` decimal(15,2) DEFAULT 0.00,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`),
        KEY `status` (`status`),
        KEY `plot_category` (`plot_category`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_plot_price_history')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_plot_price_history` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `plot_id` int(11) NOT NULL,
        `old_price` decimal(15,2) NOT NULL,
        `new_price` decimal(15,2) NOT NULL,
        `changed_by` int(11) NOT NULL,
        `change_date` datetime NOT NULL,
        `notes` text DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `plot_id` (`plot_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_plot_waiting_list')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_plot_waiting_list` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `plot_id` int(11) NOT NULL,
        `customer_id` int(11) NOT NULL,
        `priority` int(11) DEFAULT 1,
        `added_date` datetime NOT NULL,
        `status` varchar(50) DEFAULT 'active',
        `notes` text DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `plot_id` (`plot_id`),
        KEY `customer_id` (`customer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_bookings')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_bookings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `plot_id` int(11) NOT NULL,
        `customer_id` int(11) NOT NULL,
        `booking_date` date NOT NULL,
        `booking_amount` decimal(15,2) DEFAULT 0.00,
        `total_amount` decimal(15,2) DEFAULT 0.00,
        `paid_amount` decimal(15,2) DEFAULT 0.00,
        `balance_amount` decimal(15,2) DEFAULT 0.00,
        `payment_plan` varchar(100) DEFAULT NULL,
        `status` varchar(50) DEFAULT 'pending',
        `notes` text DEFAULT NULL,
        `assigned_to` int(11) DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `plot_id` (`plot_id`),
        KEY `customer_id` (`customer_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_transactions')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_transactions` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `booking_id` int(11) NOT NULL,
        `transaction_date` date NOT NULL,
        `amount` decimal(15,2) NOT NULL,
        `payment_mode` varchar(100) DEFAULT NULL,
        `transaction_type` varchar(50) DEFAULT 'payment',
        `reference_number` varchar(255) DEFAULT NULL,
        `notes` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `booking_id` (`booking_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_team_assignments')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_team_assignments` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `staff_id` int(11) NOT NULL,
        `project_id` int(11) DEFAULT NULL,
        `role` varchar(100) DEFAULT NULL,
        `assigned_date` date NOT NULL,
        `status` varchar(50) DEFAULT 'active',
        `notes` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        PRIMARY KEY (`id`),
        KEY `staff_id` (`staff_id`),
        KEY `project_id` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_owners')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_owners` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `project_id` int(11) NOT NULL,
        `owner_name` varchar(255) NOT NULL,
        `owner_type` varchar(50) DEFAULT NULL,
        `contact_number` varchar(20) DEFAULT NULL,
        `email` varchar(255) DEFAULT NULL,
        `address` text DEFAULT NULL,
        `aadhar_number` varchar(20) DEFAULT NULL,
        `pan_number` varchar(20) DEFAULT NULL,
        `ownership_percentage` decimal(5,2) DEFAULT 0.00,
        `notes` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_patta_details')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_patta_details` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `project_id` int(11) NOT NULL,
        `patta_number` varchar(100) DEFAULT NULL,
        `survey_number` varchar(100) DEFAULT NULL,
        `subdivision_number` varchar(100) DEFAULT NULL,
        `patta_holder_name` varchar(255) DEFAULT NULL,
        `extent` varchar(100) DEFAULT NULL,
        `classification` varchar(100) DEFAULT NULL,
        `remarks` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_documents')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_documents` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `project_id` int(11) NOT NULL,
        `document_type` varchar(100) NOT NULL,
        `document_name` varchar(255) NOT NULL,
        `file_name` varchar(255) NOT NULL,
        `file_path` text NOT NULL,
        `file_size` int(11) DEFAULT NULL,
        `uploaded_by` int(11) NOT NULL,
        `date_uploaded` datetime NOT NULL,
        `description` text DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}

if (!$CI->db->table_exists(db_prefix() . 'realestate_settings')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_settings` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `setting_key` varchar(255) NOT NULL,
        `setting_value` text DEFAULT NULL,
        `created_at` datetime NOT NULL,
        `updated_at` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `setting_key` (`setting_key`)
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
}
