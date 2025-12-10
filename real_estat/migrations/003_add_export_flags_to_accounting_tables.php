<?php

defined('BASEPATH') or exit('No direct script access allowed');

function real_estate_add_export_flags()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    $tables = [
        db_prefix() . 'real_booking_payments',
        db_prefix() . 'real_agent_commission_payments',
        db_prefix() . 'real_owner_payouts',
        db_prefix() . 'real_travel_claims',
        db_prefix() . 'real_sales_incentives',
    ];

    foreach ($tables as $table) {
        if ($CI->db->table_exists($table) && !$CI->db->field_exists('exported_to_accounts', $table)) {
            $CI->dbforge->add_column($table, [
                'exported_to_accounts' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'null' => false,
                    'default' => 0,
                ],
            ]);
        }
    }
}

function real_estate_drop_export_flags()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    $tables = [
        db_prefix() . 'real_booking_payments',
        db_prefix() . 'real_agent_commission_payments',
        db_prefix() . 'real_owner_payouts',
        db_prefix() . 'real_travel_claims',
        db_prefix() . 'real_sales_incentives',
    ];

    foreach ($tables as $table) {
        if ($CI->db->table_exists($table) && $CI->db->field_exists('exported_to_accounts', $table)) {
            $CI->dbforge->drop_column($table, 'exported_to_accounts');
        }
    }
}
