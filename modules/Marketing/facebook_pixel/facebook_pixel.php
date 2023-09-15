<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Facebook Pixel
Description: Facebook Pixel module for Perfex CRM
Version: 1.0
Requires at least: 2.3.*
*/

define('facebook_pixel_MODULE_NAME', 'facebook_pixel');

$CI = &get_instance();

/**
 * Load the module helper
 */
$CI->load->helper(facebook_pixel_MODULE_NAME . '/facebook_pixel');

/**
 * Register activation module hook
 */
register_activation_hook(facebook_pixel_MODULE_NAME, 'facebook_pixel_activation_hook');

function facebook_pixel_activation_hook()
{
    require_once(__DIR__ . '/install.php');
}

/**
 * Register language files, must be registered if the module is using languages
 */
register_language_files(facebook_pixel_MODULE_NAME, [facebook_pixel_MODULE_NAME]);

/**
 * Actions for inject the custom styles
 */
hooks()->add_action('app_admin_footer', 'facebook_pixel_admin_head');
hooks()->add_action('app_customers_footer', 'facebook_pixel_clients_area_head');
hooks()->add_filter('module_facebook_pixel_action_links', 'module_facebook_pixel_action_links');
hooks()->add_action('admin_init', 'facebook_pixel_init_menu_items');

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
function module_facebook_pixel_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('facebook_pixel') . '">' . _l('settings') . '</a>';

    return $actions;
}
/**
 * Admin area applied styles
 * @return null
 */
function facebook_pixel_admin_head()
{
    facebook_pixel_script('facebook_pixel_admin_area');
}

/**
 * Clients area theme applied styles
 * @return null
 */
function facebook_pixel_clients_area_head()
{
    facebook_pixel_script('facebook_pixel_clients_area');
}

/**
 * Custom CSS
 * @param  string $main_area clients or admin area options
 * @return null
 */
function facebook_pixel_script($main_area)
{
    $clients_or_admin_area             = get_option($main_area);
    if (get_option('facebook_pixel') == 'enable') {
        $facebook_pixel_admin_and_clients_area = get_option('facebook_pixel_clients_and_admin_area');
        if (!empty($clients_or_admin_area) || !empty($facebook_pixel_admin_and_clients_area)) {
            if (!empty($clients_or_admin_area)) {
                $clients_or_admin_area = html_entity_decode(clear_textarea_breaks($clients_or_admin_area));
                echo $clients_or_admin_area . PHP_EOL;
            }
            if (!empty($facebook_pixel_admin_and_clients_area)) {
                $facebook_pixel_admin_and_clients_area = html_entity_decode(clear_textarea_breaks($facebook_pixel_admin_and_clients_area));
                echo $facebook_pixel_admin_and_clients_area . PHP_EOL;
            }
        }
    }
}

/**
 * Init theme style module menu items in setup in admin_init hook
 * @return null
 */
function facebook_pixel_init_menu_items()
{
    if (is_admin()) {
        $CI = &get_instance();
        /**
         * If the logged in user is administrator, add custom menu in Setup
         */
        $CI->app_menu->add_setup_menu_item('facebook-pixel', [
            'href'     => admin_url('facebook_pixel'),
            'name'     => _l('facebook_pixel'),
            'position' => 66,
        ]);
    }
}