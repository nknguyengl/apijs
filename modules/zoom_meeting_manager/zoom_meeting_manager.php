<?php defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: Zoom Meeting Manager
Description: Manages Zoom Meetings
Version: 1.1.0
Author: Aleksandar Stojanov
Author URI: https://idevalex.com
Requires at least: 2.3.2
*/

define('ZOOM_MEETING_MANAGER_MODULE_NAME', 'zoom_meeting_manager');
define('ZOOM_MEETING_MANAGER_CSS', module_dir_url(ZOOM_MEETING_MANAGER_MODULE_NAME, 'assets/css/styles.css'));
define('ZOOM_MEETING_MANAGER_JS', module_dir_url(ZOOM_MEETING_MANAGER_MODULE_NAME, 'assets/js/main.js'));

hooks()->add_action('admin_init', 'zmm_register_user_permissions');
hooks()->add_action('admin_init', 'zmm_register_menu_items');
hooks()->add_action('app_admin_head', 'zmm_head_components');
hooks()->add_action('app_admin_footer', 'zmm_js_footer_components');
hooks()->add_action('after_email_templates', 'zmm_add_email_templates');
register_merge_fields('zoom_meeting_manager/merge_fields/zoom_meeting_manager_merge_fields');
hooks()->add_filter('other_merge_fields_available_for', 'zmm_register_other_merge_fields');

$CI = &get_instance();

/**
 * Table names
 */
define('ZMM_TABLE_ZOOM', db_prefix() . 'zmm');
define('ZMM_TABLE_PARTICIPANTS', db_prefix() . 'zmm_participants');
define('ZMM_TABLE_NOTES', db_prefix() . 'zmm_notes');

/**
 * Hook for assigning staff permissions for
 *
 * @return void
 */
function zmm_register_user_permissions()
{
	$capabilities = [];

	$capabilities['capabilities'] = [
		'view'   => _l('permission_view'),
		'create' => _l('permission_create'),
		'delete' => _l('permission_delete'),
	];

	register_staff_capabilities('zoom_meeting_manager', $capabilities, _l('zmm_module_name'));
}

/**
 * Register new menu item in sidebar menu
 */
function zmm_register_menu_items()
{
	$CI = &get_instance();

	if (staff_can('view')) {
		$CI->app_menu->add_sidebar_menu_item(ZOOM_MEETING_MANAGER_MODULE_NAME, [
			'name'     => _l('zmm_module_name_menu'),
			'href'     => admin_url('zoom_meeting_manager/index'),
			'icon'     => 'fa fa-phone',
			'position' => 25,
		]);
	}
}

/**
 * Check if can have permissions then apply new tab in settings
 */
if (staff_can('view', 'settings')) {
	hooks()->add_action('admin_init', 'zmm_add_settings_tab');
}

/**
 * @return void
 */
function zmm_add_settings_tab()
{
	if (is_admin()) {
		$CI = &get_instance();
		$CI->app_tabs->add_settings_tab('zoom-meeting-manager-settings', [
			'name'     => _l('zmm_module_name'),
			'view'     => 'zoom_meeting_manager/settings',
			'position' => 32,
		]);
	}
}

if (!function_exists('zmm_head_components')) {
	/**
	 * Injects module CSS
	 * @return void
	 */
	function zmm_head_components()
	{
		echo '<link href="' . ZOOM_MEETING_MANAGER_CSS . "?v=" . time() . '"  rel="stylesheet" type="text/css" >';
	}
}

if (!function_exists('zmm_js_footer_components')) {
	/**
	 * Injects module js
	 * @return void
	 */
	function zmm_js_footer_components()
	{
		echo '<script src="' . ZOOM_MEETING_MANAGER_JS . "?v=" . time() . '"></script>';
	}
}


/**
 * Register module activation hook
 */
register_activation_hook(ZOOM_MEETING_MANAGER_MODULE_NAME, 'zmm_theme_activation_hook');

/**
 * The activation function
 */
function zmm_theme_activation_hook()
{
	require(__DIR__ . '/install.php');
}

/**
 * Register module language files
 */
register_language_files(ZOOM_MEETING_MANAGER_MODULE_NAME, ['zmm']);

/**
 * Load the module helper file
 */
$CI->load->helper(ZOOM_MEETING_MANAGER_MODULE_NAME . '/zmm');


/**
 * Register other merge fields
 *
 * @param array $for
 * @return void
 */
function zmm_register_other_merge_fields($for)
{
	$for[] = 'zoom_meeting_manager';

	return $for;
}

if (!function_exists('zmm_add_email_templates')) {
	/**
	 * Init zoom module email templates and assign / load languages
	 * @return void
	 */
	function zmm_add_email_templates()
	{
		$CI = &get_instance();

		$data['zoom_meeting_manager_templates'] = $CI->emails_model->get(['type' => 'zoom_meeting_manager', 'language' => 'english']);

		$CI->load->view('zoom_meeting_manager/email_templates', $data);
	}
}
