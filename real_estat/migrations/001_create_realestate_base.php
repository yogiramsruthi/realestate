<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Create basic Real Estate tables
 */
function real_estate_module_create_tables()
{
    $CI = &get_instance();
    $CI->load->dbforge();

    // Projects table – basic fields (we will extend later)
    if (!$CI->db->table_exists(db_prefix() . 'realestate_projects')) {
        $fields = [
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'project_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
            'project_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 191,
                'null'       => false,
            ],
            'short_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'district' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'taluk' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'village' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'survey_numbers' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'approval_type' => [
                'type'       => 'VARCHAR',
                'constraint' => 50, // dtcp_rera / panchayat_78go / patta_layout / farm_land / other
                'null'       => true,
            ],
            'total_raw_acres' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'total_raw_sqft' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'total_approved_sqft' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'total_plots' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'dtcp_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'rera_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'approval_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'approval_expiry_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'project_manager_staff_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => true,
            ],
            'booking_validity_days' => [
                'type'       => 'INT',
                'constraint' => 11,
                'null'       => false,
                'default'    => 30,
            ],
            'allow_emi' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => false,
                'default'    => 1,
            ],
            'default_emi_interest' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => false,
                'default'    => '0.00',
            ],
            'default_emi_due_day' => [
                'type'       => 'TINYINT',
                'constraint' => 2,
                'null'       => true,
            ],
            'date_created' => [
                'type' => 'DATETIME',
                'null' => false,
                'default' => date('Y-m-d H:i:s'),
            ],
            'date_updated' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ];

        $CI->dbforge->add_field($fields);
        $CI->dbforge->add_key('id', true);
        $CI->dbforge->create_table(db_prefix() . 'realestate_projects', true);
    }
}
