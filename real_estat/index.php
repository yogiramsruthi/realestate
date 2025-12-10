<?php

defined('BASEPATH') or exit('No direct script access allowed');

define('REAL_ESTATE_MODULE_NAME', 'real_estate');

/*
|--------------------------------------------------------------
|  Activation / Uninstall hooks
|--------------------------------------------------------------
*/
register_activation_hook(REAL_ESTATE_MODULE_NAME, 'real_estate_module_activation');
register_uninstall_hook(REAL_ESTATE_MODULE_NAME, 'real_estate_module_uninstall');

/**
 * Run on module activation
 */
function real_estate_module_activation()
{
    $CI = &get_instance();

    // Load database and helpers
    $CI->load->database();
    $CI->load->helper('realestate/realestate');

    // Create required tables
    require_once(__DIR__ . '/migrations/001_create_realestate_base.php');
    real_estate_module_create_tables();
}

/**
 * Run on module uninstall (optional: drop tables)
 */
function real_estate_module_uninstall()
{
    // If you want to drop tables on uninstall, uncomment below:
    /*
    $CI = &get_instance();
    $CI->db->query('DROP TABLE IF EXISTS `' . db_prefix() . 'realestate_projects`');
    */
}

/*
|--------------------------------------------------------------
|  Hooks
|--------------------------------------------------------------
*/
hooks()->add_action('admin_init', 'real_estate_init_menu_items');
hooks()->add_action('admin_init', 'real_estate_init_permissions');

/**
 * Register sidebar menu item
 */
function real_estate_init_menu_items()
{
    $CI = &get_instance();

    if (!is_staff_logged_in()) {
        return;
    }

    if (!has_permission('real_estate', '', 'view')) {
        return;
    }

       $CI->app_menu->add_child_sidebar_menu_item('real-estate-menu', [
        'slug' => 'real-estate-projects',
        'name' => _l('real_estate_projects'),
        'href' => admin_url('real_estate/projects'),
        'icon' => 'fa fa-folder-open',
        'position' => 1
    ]);

}

/**
 * Register permissions
 */
function real_estate_init_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . ' (global)',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('real_estate', $capabilities, _l('real_estate_menu'));
}
