<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Real Estate Management
Description: Complete Real Estate Management System for managing projects, plots, bookings, and sales
Version: 1.0.0
Requires at least: 2.3.*
*/

define('REAL_ESTAT_MODULE_NAME', 'real_estat');

hooks()->add_action('admin_init', 'real_estat_module_init_menu_items');
hooks()->add_action('app_admin_head', 'real_estat_add_head_components');
hooks()->add_action('app_admin_footer', 'real_estat_load_js');

/**
 * Register activation module hook
 */
register_activation_hook(REAL_ESTAT_MODULE_NAME, 'real_estat_module_activation_hook');

function real_estat_module_activation_hook()
{
    $CI = &get_instance();
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(REAL_ESTAT_MODULE_NAME, [REAL_ESTAT_MODULE_NAME]);

/**
 * Init real estate module menu items in setup in admin_init hook
 */
function real_estat_module_init_menu_items()
{
    $CI = &get_instance();

    $capabilities = [
        'view'   => _l('permission_view') . ' (' . _l('permission_global') . ')',
        'create' => _l('permission_create'),
        'edit'   => _l('permission_edit'),
        'delete' => _l('permission_delete'),
    ];

    register_staff_capabilities('real_estate_projects', $capabilities, _l('real_estate_projects'));
    register_staff_capabilities('real_estate_plots', $capabilities, _l('real_estate_plots'));
    register_staff_capabilities('real_estate_bookings', $capabilities, _l('real_estate_bookings'));
    register_staff_capabilities('real_estate_payments', $capabilities, _l('real_estate_payments'));

    $accounting_caps = [
        'accounts' => _l('real_estate_accounting'),
    ];
    register_staff_capabilities('real_estate', $accounting_caps, _l('accounting_exports'));

    if (has_permission('real_estate_projects', '', 'view') || has_permission('real_estate_plots', '', 'view') || has_permission('real_estate_bookings', '', 'view')) {
        $CI->app_menu->add_sidebar_menu_item('real-estate', [
            'slug'     => 'real-estate',
            'name'     => _l('real_estate'),
            'icon'     => 'fa fa-building',
            'position' => 15,
        ]);

        if (has_permission('real_estate_projects', '', 'view')) {
            $CI->app_menu->add_sidebar_children_item('real-estate', [
                'slug'     => 'real-estate-projects',
                'name'     => _l('real_estate_projects'),
                'href'     => admin_url('real_estat/projects'),
                'position' => 1,
            ]);
        }

        if (has_permission('real_estate_plots', '', 'view')) {
            $CI->app_menu->add_sidebar_children_item('real-estate', [
                'slug'     => 'real-estate-plots',
                'name'     => _l('real_estate_plots'),
                'href'     => admin_url('real_estat/plots'),
                'position' => 2,
            ]);
        }

        if (has_permission('real_estate_bookings', '', 'view')) {
            $CI->app_menu->add_sidebar_children_item('real-estate', [
                'slug'     => 'real-estate-bookings',
                'name'     => _l('real_estate_bookings'),
                'href'     => admin_url('real_estat/bookings'),
                'position' => 3,
            ]);
        }

        if (has_permission('real_estate_payments', '', 'view')) {
            $CI->app_menu->add_sidebar_children_item('real-estate', [
                'slug'     => 'real-estate-payments',
                'name'     => _l('real_estate_payments'),
                'href'     => admin_url('real_estat/payments'),
                'position' => 4,
            ]);
        }

        $CI->app_menu->add_sidebar_children_item('real-estate', [
            'slug'     => 'real-estate-reports',
            'name'     => _l('real_estate_reports'),
            'href'     => admin_url('real_estat/reports'),
            'position' => 5,
        ]);

        $CI->app_menu->add_sidebar_children_item('real-estate', [
            'slug'     => 'real-estate-settings',
            'name'     => _l('settings'),
            'href'     => admin_url('real_estat/settings'),
            'position' => 6,
        ]);

        if (has_permission('real_estate', '', 'accounts')) {
            $CI->app_menu->add_sidebar_children_item('real-estate', [
                'slug'     => 'real-estate-accounting-exports',
                'name'     => _l('accounting_exports'),
                'href'     => admin_url('real_estat/accounting_exports'),
                'position' => 7,
            ]);
        }
    }
}

/**
 * Add additional CSS and JS files in head
 */
function real_estat_add_head_components()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if (!(strpos($viewuri, '/admin/real_estat') === false)) {
        echo '<link href="' . module_dir_url(REAL_ESTAT_MODULE_NAME, 'assets/css/real_estat.css') . '?v=' . time() . '"  rel="stylesheet" type="text/css" />';
    }
}

/**
 * Load JS files in footer
 */
function real_estat_load_js()
{
    $CI = &get_instance();
    $viewuri = $_SERVER['REQUEST_URI'];
    
    if (!(strpos($viewuri, '/admin/real_estat') === false)) {
        echo '<script src="' . module_dir_url(REAL_ESTAT_MODULE_NAME, 'assets/js/real_estat.js') . '?v=' . time() . '"></script>';
    }
}
