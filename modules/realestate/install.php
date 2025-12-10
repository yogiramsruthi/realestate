<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'realestate_projects')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "realestate_projects` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `description` text DEFAULT NULL,
        `location` varchar(255) DEFAULT NULL,
        `total_plots` int(11) DEFAULT 0,
        `available_plots` int(11) DEFAULT 0,
        `project_type` varchar(100) DEFAULT NULL,
        `start_date` date DEFAULT NULL,
        `end_date` date DEFAULT NULL,
        `status` varchar(50) DEFAULT 'active',
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`)
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
        `status` varchar(50) DEFAULT 'available',
        `dimension` varchar(100) DEFAULT NULL,
        `facing` varchar(50) DEFAULT NULL,
        `description` text DEFAULT NULL,
        `created_by` int(11) NOT NULL,
        `date_created` datetime NOT NULL,
        `last_updated` datetime DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `project_id` (`project_id`)
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
