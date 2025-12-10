<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Extend Real Estate Projects table with additional fields
 * This adds all the fields used in the form
 */
function extend_real_estate_projects_table()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    $table = db_prefix() . 're_projects';

    // Add fields if they don't exist
    $fields_to_add = [
        'district' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
            'after'      => 'location'
        ],
        'area' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
            'after'      => 'district'
        ],
        'village' => [
            'type'       => 'VARCHAR',
            'constraint' => 100,
            'null'       => true,
            'after'      => 'area'
        ],
        'location_map' => [
            'type'       => 'VARCHAR',
            'constraint' => 500,
            'null'       => true,
            'after'      => 'village'
        ],
        'nearby' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'location_map'
        ],
        'total_owners' => [
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
            'after'      => 'nearby'
        ],
        'total_acres' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'total_area'
        ],
        'total_sqft' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'total_acres'
        ],
        'approved_sqft' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'total_sqft'
        ],
        'owners_price_per_sqft' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'approved_sqft'
        ],
        'min_selling_price_per_sqft' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'owners_price_per_sqft'
        ],
        'max_selling_price_per_sqft' => [
            'type'       => 'DECIMAL',
            'constraint' => [15, 2],
            'null'       => true,
            'after'      => 'min_selling_price_per_sqft'
        ],
        'team_commission_type' => [
            'type'       => 'ENUM',
            'constraint' => ["'percentage'", "'slab'"],
            'default'    => 'percentage',
            'after'      => 'max_selling_price_per_sqft'
        ],
        'team_commission_value' => [
            'type'       => 'DECIMAL',
            'constraint' => [5, 2],
            'null'       => true,
            'after'      => 'team_commission_type'
        ],
        'team_commission_slab_json' => [
            'type' => 'JSON',
            'null' => true,
            'after' => 'team_commission_value'
        ],
        'approval_types' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'team_commission_slab_json'
        ],
        'approval_details' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'approval_types'
        ],
        'has_power_of_attorney' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'approval_details'
        ],
        'poa_status' => [
            'type'       => 'ENUM',
            'constraint' => ["'none'", "'pending'", "'verified'", "'expired'", "'revoked'", "'partial'", "'full'"],
            'default'    => 'none',
            'after'      => 'has_power_of_attorney'
        ],
        'poa_grantor_name' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'poa_status'
        ],
        'poa_attorney_name' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'poa_grantor_name'
        ],
        'poa_attorney_phone' => [
            'type'       => 'VARCHAR',
            'constraint' => 20,
            'null'       => true,
            'after'      => 'poa_attorney_name'
        ],
        'poa_issue_date' => [
            'type' => 'DATE',
            'null' => true,
            'after' => 'poa_attorney_phone'
        ],
        'poa_expiry_date' => [
            'type' => 'DATE',
            'null' => true,
            'after' => 'poa_issue_date'
        ],
        'poa_sales_authority' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_expiry_date'
        ],
        'poa_financial_authority' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_sales_authority'
        ],
        'poa_legal_authority' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_financial_authority'
        ],
        'poa_document_signing' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_legal_authority'
        ],
        'poa_receipt_authority' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_document_signing'
        ],
        'poa_full_authority' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_receipt_authority'
        ],
        'poa_verification_status' => [
            'type'       => 'ENUM',
            'constraint' => ["'pending'", "'verified'", "'rejected'", "'pending_documents'"],
            'default'    => 'pending',
            'after'      => 'poa_full_authority'
        ],
        'poa_verified_date' => [
            'type' => 'DATE',
            'null' => true,
            'after' => 'poa_verification_status'
        ],
        'poa_verified_by' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'poa_verified_date'
        ],
        'poa_document_filename' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'poa_verified_by'
        ],
        'poa_notes' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'poa_document_filename'
        ],
        'emi_enabled' => [
            'type'       => 'TINYINT',
            'constraint' => 1,
            'default'    => 0,
            'after'      => 'poa_notes'
        ],
        'emi_interest_type' => [
            'type'       => 'ENUM',
            'constraint' => ["'none'", "'flat'", "'reducing'"],
            'default'    => 'none',
            'after'      => 'emi_enabled'
        ],
        'emi_interest_rate_annual' => [
            'type'       => 'DECIMAL',
            'constraint' => [5, 2],
            'null'       => true,
            'after'      => 'emi_interest_type'
        ],
        'emi_penalty_rate_annual' => [
            'type'       => 'DECIMAL',
            'constraint' => [5, 2],
            'null'       => true,
            'after'      => 'emi_interest_rate_annual'
        ],
        'emi_grace_days' => [
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
            'after'      => 'emi_penalty_rate_annual'
        ],
        'emi_default_tenor_months' => [
            'type'       => 'INT',
            'constraint' => 11,
            'null'       => true,
            'after'      => 'emi_grace_days'
        ],
        'survey_info' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'emi_default_tenor_months'
        ],
        'patta_info' => [
            'type' => 'TEXT',
            'null' => true,
            'after' => 'survey_info'
        ],
        'pr_document' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'patta_info'
        ],
        'current_document' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'pr_document'
        ],
        'layout_plan_document' => [
            'type'       => 'VARCHAR',
            'constraint' => 255,
            'null'       => true,
            'after'      => 'current_document'
        ],
    ];

    foreach ($fields_to_add as $field_name => $field_config) {
        if (!$CI->db->field_exists($field_name, $table)) {
            $CI->db->query("ALTER TABLE $table ADD COLUMN `$field_name` " . build_column_definition($field_config));
        }
    }
}

function build_column_definition($config)
{
    $type = strtoupper($config['type']);
    $def = $type;

    if (isset($config['constraint'])) {
        if (is_array($config['constraint'])) {
            $def .= '(' . implode(',', $config['constraint']) . ')';
        } else {
            $def .= '(' . $config['constraint'] . ')';
        }
    }

    if (isset($config['default'])) {
        $def .= " DEFAULT '" . $config['default'] . "'";
    }

    if (isset($config['null']) && $config['null'] === false) {
        $def .= ' NOT NULL';
    } else {
        $def .= ' NULL';
    }

    return $def;
}

// Run the migration
extend_real_estate_projects_table();
