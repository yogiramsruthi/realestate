<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Add_project_spec_columns extends CI_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;
        
        // Add location fields
        if (!$this->db->field_exists('district', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `district` VARCHAR(100) NULL AFTER `location`");
        }
        
        if (!$this->db->field_exists('area', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `area` VARCHAR(100) NULL AFTER `district`");
        }
        
        if (!$this->db->field_exists('village', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `village` VARCHAR(100) NULL AFTER `area`");
        }
        
        if (!$this->db->field_exists('location_map', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `location_map` VARCHAR(255) NULL AFTER `village`");
        }
        
        if (!$this->db->field_exists('nearby', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `nearby` TEXT NULL AFTER `location_map`");
        }
        
        // Add ownership fields
        if (!$this->db->field_exists('total_owners', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `total_owners` INT NULL AFTER `nearby`");
        }
        
        if (!$this->db->field_exists('has_power_of_attorney', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `has_power_of_attorney` TINYINT(1) DEFAULT 0 AFTER `total_owners`");
        }
        
        // Add land detail fields
        if (!$this->db->field_exists('total_acres', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `total_acres` DECIMAL(10,2) NULL AFTER `has_power_of_attorney`");
        }
        
        if (!$this->db->field_exists('total_sqft', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `total_sqft` DECIMAL(12,2) NULL AFTER `total_acres`");
        }
        
        if (!$this->db->field_exists('approved_sqft', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `approved_sqft` DECIMAL(12,2) NULL AFTER `total_sqft`");
        }
        
        // Add approval fields
        if (!$this->db->field_exists('approval_types', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `approval_types` VARCHAR(100) NULL AFTER `approved_sqft`");
        }
        
        if (!$this->db->field_exists('approval_details', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `approval_details` TEXT NULL AFTER `approval_types`");
        }
        
        // Add pricing fields
        if (!$this->db->field_exists('owners_price_per_sqft', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `owners_price_per_sqft` DECIMAL(12,2) NULL AFTER `approval_details`");
        }
        
        if (!$this->db->field_exists('min_selling_price_per_sqft', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `min_selling_price_per_sqft` DECIMAL(12,2) NULL AFTER `owners_price_per_sqft`");
        }
        
        if (!$this->db->field_exists('max_selling_price_per_sqft', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `max_selling_price_per_sqft` DECIMAL(12,2) NULL AFTER `min_selling_price_per_sqft`");
        }
        
        // Add commission fields
        if (!$this->db->field_exists('team_commission_type', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `team_commission_type` ENUM('percentage','slab') DEFAULT 'percentage' AFTER `max_selling_price_per_sqft`");
        }
        
        if (!$this->db->field_exists('team_commission_value', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `team_commission_value` DECIMAL(10,2) NULL AFTER `team_commission_type`");
        }
        
        if (!$this->db->field_exists('team_commission_slab_json', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `team_commission_slab_json` TEXT NULL AFTER `team_commission_value`");
        }
        
        // Add EMI fields
        if (!$this->db->field_exists('emi_enabled', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_enabled` TINYINT(1) DEFAULT 0 AFTER `team_commission_slab_json`");
        }
        
        if (!$this->db->field_exists('emi_interest_type', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_interest_type` ENUM('none','flat','reducing') DEFAULT 'none' AFTER `emi_enabled`");
        }
        
        if (!$this->db->field_exists('emi_interest_rate_annual', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_interest_rate_annual` DECIMAL(10,2) NULL AFTER `emi_interest_type`");
        }
        
        if (!$this->db->field_exists('emi_penalty_rate_annual', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_penalty_rate_annual` DECIMAL(10,2) NULL AFTER `emi_interest_rate_annual`");
        }
        
        if (!$this->db->field_exists('emi_grace_days', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_grace_days` INT NULL AFTER `emi_penalty_rate_annual`");
        }
        
        if (!$this->db->field_exists('emi_default_tenor_months', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `emi_default_tenor_months` INT NULL AFTER `emi_grace_days`");
        }
        
        // Add document fields
        if (!$this->db->field_exists('pr_document', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `pr_document` VARCHAR(255) NULL AFTER `emi_default_tenor_months`");
        }
        
        if (!$this->db->field_exists('current_document', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `current_document` VARCHAR(255) NULL AFTER `pr_document`");
        }
        
        if (!$this->db->field_exists('layout_plan_document', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `layout_plan_document` VARCHAR(255) NULL AFTER `current_document`");
        }
        
        // Add survey & patta fields
        if (!$this->db->field_exists('survey_info', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `survey_info` TEXT NULL AFTER `layout_plan_document`");
        }
        
        if (!$this->db->field_exists('patta_info', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `patta_info` TEXT NULL AFTER `survey_info`");
        }
        
        // Update status enum to match spec
        $this->db->query("ALTER TABLE `{$prefix}re_projects` MODIFY `status` ENUM('draft','active','archived') DEFAULT 'draft'");
    }

    public function down()
    {
        $prefix = $this->db->dbprefix;
        
        // Remove columns in reverse order
        $columns_to_drop = [
            'patta_info', 'survey_info', 'layout_plan_document', 'current_document', 'pr_document',
            'emi_default_tenor_months', 'emi_grace_days', 'emi_penalty_rate_annual', 'emi_interest_rate_annual',
            'emi_interest_type', 'emi_enabled', 'team_commission_slab_json', 'team_commission_value',
            'team_commission_type', 'max_selling_price_per_sqft', 'min_selling_price_per_sqft',
            'owners_price_per_sqft', 'approval_details', 'approval_types', 'approved_sqft',
            'total_sqft', 'total_acres', 'has_power_of_attorney', 'total_owners', 'nearby',
            'location_map', 'village', 'area', 'district'
        ];
        
        foreach ($columns_to_drop as $column) {
            if ($this->db->field_exists($column, $prefix . 're_projects')) {
                $this->db->query("ALTER TABLE `{$prefix}re_projects` DROP COLUMN `{$column}`");
            }
        }
    }
}
