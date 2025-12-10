<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Enhance_power_of_attorney extends CI_Migration
{
    public function up()
    {
        $prefix = $this->db->dbprefix;
        
        // Replace simple boolean with detailed POA status
        if (!$this->db->field_exists('poa_status', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_status` ENUM('none','general','special','enduring','pending','expired','revoked') DEFAULT 'none' AFTER `has_power_of_attorney`");
        }
        
        // POA Holder Information
        if (!$this->db->field_exists('poa_grantor_name', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_grantor_name` VARCHAR(255) NULL AFTER `poa_status`");
        }
        
        if (!$this->db->field_exists('poa_attorney_name', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_attorney_name` VARCHAR(255) NULL AFTER `poa_grantor_name`");
        }
        
        if (!$this->db->field_exists('poa_attorney_phone', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_attorney_phone` VARCHAR(20) NULL AFTER `poa_attorney_name`");
        }
        
        // POA Dates
        if (!$this->db->field_exists('poa_issue_date', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_issue_date` DATE NULL AFTER `poa_attorney_phone`");
        }
        
        if (!$this->db->field_exists('poa_expiry_date', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_expiry_date` DATE NULL AFTER `poa_issue_date`");
        }
        
        // POA Rights and Authorities
        if (!$this->db->field_exists('poa_sales_authority', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_sales_authority` TINYINT(1) DEFAULT 0 AFTER `poa_expiry_date`");
        }
        
        if (!$this->db->field_exists('poa_financial_authority', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_financial_authority` TINYINT(1) DEFAULT 0 AFTER `poa_sales_authority`");
        }
        
        if (!$this->db->field_exists('poa_legal_authority', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_legal_authority` TINYINT(1) DEFAULT 0 AFTER `poa_financial_authority`");
        }
        
        if (!$this->db->field_exists('poa_document_signing', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_document_signing` TINYINT(1) DEFAULT 0 AFTER `poa_legal_authority`");
        }
        
        if (!$this->db->field_exists('poa_receipt_authority', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_receipt_authority` TINYINT(1) DEFAULT 0 AFTER `poa_document_signing`");
        }
        
        if (!$this->db->field_exists('poa_full_authority', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_full_authority` TINYINT(1) DEFAULT 0 AFTER `poa_receipt_authority`");
        }
        
        // POA Verification
        if (!$this->db->field_exists('poa_verification_status', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_verification_status` ENUM('not_verified','pending','verified','invalid') DEFAULT 'not_verified' AFTER `poa_full_authority`");
        }
        
        if (!$this->db->field_exists('poa_verified_date', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_verified_date` DATE NULL AFTER `poa_verification_status`");
        }
        
        if (!$this->db->field_exists('poa_verified_by', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_verified_by` VARCHAR(255) NULL AFTER `poa_verified_date`");
        }
        
        // POA Document
        if (!$this->db->field_exists('poa_document_filename', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_document_filename` VARCHAR(255) NULL AFTER `poa_verified_by`");
        }
        
        // POA Notes
        if (!$this->db->field_exists('poa_notes', $prefix . 're_projects')) {
            $this->db->query("ALTER TABLE `{$prefix}re_projects` ADD COLUMN `poa_notes` TEXT NULL AFTER `poa_document_filename`");
        }
    }

    public function down()
    {
        $prefix = $this->db->dbprefix;
        
        // Drop all POA enhancement columns
        $poa_columns = [
            'poa_status',
            'poa_grantor_name',
            'poa_attorney_name',
            'poa_attorney_phone',
            'poa_issue_date',
            'poa_expiry_date',
            'poa_sales_authority',
            'poa_financial_authority',
            'poa_legal_authority',
            'poa_document_signing',
            'poa_receipt_authority',
            'poa_full_authority',
            'poa_verification_status',
            'poa_verified_date',
            'poa_verified_by',
            'poa_document_filename',
            'poa_notes'
        ];
        
        foreach ($poa_columns as $column) {
            if ($this->db->field_exists($column, $prefix . 're_projects')) {
                $this->db->query("ALTER TABLE `{$prefix}re_projects` DROP COLUMN `{$column}`");
            }
        }
    }
}
