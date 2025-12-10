<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Real Estate Management
Description: Comprehensive real estate management module for Perfex CRM with customer, project, plot, booking, accounts and team management.
Version: 1.0.0
Author: Real Estate Module
Author URI: https://github.com/yogiramsruthi/realestate
Requires at least: 2.3.*
*/

define('REALESTATE_MODULE_NAME', 'realestate');
define('REALESTATE_MODULE_VERSION', '1.0.0');

/**
 * Register activation module hook
 */
register_activation_hook(REALESTATE_MODULE_NAME, 'realestate_module_activation_hook');

function realestate_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(REALESTATE_MODULE_NAME, [REALESTATE_MODULE_NAME]);

/**
 * Init module menu items in setup in admin_init hook
 */
hooks()->add_action('admin_init', 'realestate_module_init_menu_items');

/**
 * Init module menu items
 */
function realestate_module_init_menu_items()
{
    $CI = &get_instance();

    if (has_permission('realestate', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('realestate', [
            'name'     => _l('realestate'),
            'icon'     => 'fa fa-building',
            'position' => 10,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-dashboard',
            'name'     => _l('realestate_dashboard'),
            'href'     => admin_url('realestate/dashboard'),
            'icon'     => 'fa fa-dashboard',
            'position' => 1,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-projects',
            'name'     => _l('realestate_projects'),
            'href'     => admin_url('realestate/projects'),
            'icon'     => 'fa fa-building-o',
            'position' => 2,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-plots',
            'name'     => _l('realestate_plots'),
            'href'     => admin_url('realestate/plots'),
            'icon'     => 'fa fa-th',
            'position' => 3,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-bookings',
            'name'     => _l('realestate_bookings'),
            'href'     => admin_url('realestate/bookings'),
            'icon'     => 'fa fa-calendar-check-o',
            'position' => 4,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-accounts',
            'name'     => _l('realestate_accounts'),
            'href'     => admin_url('realestate/accounts'),
            'icon'     => 'fa fa-money',
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('realestate', [
            'slug'     => 'realestate-team',
            'name'     => _l('realestate_team'),
            'href'     => admin_url('realestate/team'),
            'icon'     => 'fa fa-users',
            'position' => 6,
        ]);
    }
}

/**
 * Register permissions
 */
hooks()->add_action('admin_init', 'realestate_permissions');

function realestate_permissions()
{
    $capabilities = [];

    $capabilities['capabilities'] = [
        'view'   => _l('permission_view') . '(' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('realestate', $capabilities, _l('realestate'));
}
