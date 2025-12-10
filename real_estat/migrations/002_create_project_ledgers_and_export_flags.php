<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * add ledger mapping table and export flag column for accounting exports.
 */
function real_estate_module_update_accounting_tables()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    // Project ledger mappings
    if (!$CI->db->table_exists(db_prefix() . 'real_project_ledgers')) {
        $fields = [
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'project_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'ledger_key' => [
                'type' => 'VARCHAR',
                'constraint' => 64,
                'null' => false,
            ],
            'ledger_name' => [
                'type' => 'VARCHAR',
                'constraint' => 191,
                'null' => false,
            ],
        ];

        $CI->dbforge->add_field($fields);
        $CI->dbforge->add_key('id', true);
        $CI->dbforge->add_key('project_id');
        $CI->dbforge->add_field('UNIQUE KEY `project_ledger_key` (`project_id`,`ledger_key`)');
        $CI->dbforge->create_table(db_prefix() . 'real_project_ledgers', true);
    }

    // Add exported flag on installments for receipt exports
    if (!$CI->db->field_exists('exported_to_accounts', db_prefix() . 're_booking_installments')) {
        $CI->dbforge->add_column(db_prefix() . 're_booking_installments', [
            'exported_to_accounts' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'null' => false,
                'default' => 0,
            ],
        ]);
    }
}

function real_estate_module_drop_accounting_tables()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    if ($CI->db->table_exists(db_prefix() . 'real_project_ledgers')) {
        $CI->dbforge->drop_table(db_prefix() . 'real_project_ledgers', true);
    }

    if ($CI->db->field_exists('exported_to_accounts', db_prefix() . 're_booking_installments')) {
        $CI->dbforge->drop_column(db_prefix() . 're_booking_installments', 'exported_to_accounts');
    }
}
