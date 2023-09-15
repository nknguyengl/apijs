<?php

/**
 * Ensures that the module init file can't be accessed directly, only within the application.
 */
defined('BASEPATH') or exit('No direct script access allowed');

/*
	Module Name: Social Media Login module
	Description: Allow customers to register and log into Perfex CRM through their Google, Facebook, LinkedIn and Twitter account.
	Version: 1.0.0
	Requires at least: 2.3.*
	Author: Themesic Interactive
	Author URI: https://codecanyon.net/user/themesic/portfolio
*/

define('SOCIAL_MEDIA_LOGIN_MODULE_NAME', 'social_media_login');

hooks()->add_action('before_client_logout','google_session_logout');
hooks()->add_action('before_client_logout','facebook_session_logout');
hooks()->add_action('before_client_logout','linkedin_session_logout');
hooks()->add_action('before_client_logout','twitter_session_logout');

register_activation_hook(SOCIAL_MEDIA_LOGIN_MODULE_NAME, 'social_media_login_activation_hook');
register_deactivation_hook(SOCIAL_MEDIA_LOGIN_MODULE_NAME, 'social_media_login_deactivation_hook');

register_language_files(SOCIAL_MEDIA_LOGIN_MODULE_NAME, [SOCIAL_MEDIA_LOGIN_MODULE_NAME]);

/**
 * Add additional settings for this module in the module list area
 * @param  array $actions current actions
 * @return array
 */
hooks()->add_filter('module_social_media_login_action_links', 'module_social_media_login_action_links');

function module_social_media_login_action_links($actions)
{
    $actions[] = '<a href="' . admin_url('settings?group=social_media_login') . '">' . _l('social_login_menu_name') . '</a>';

    return $actions;
}

/*
 * Check if can have permissions then apply new tab in settings
 */
hooks()->add_action('admin_init', 'social_media_login_add_settings_tab');

/**
 * [social_media_login_add_settings_tab net menu item in setup->settings].
 *
 * @return void
 */
function social_media_login_add_settings_tab()
{
    $CI = &get_instance();
    $CI->app_tabs->add_settings_tab('social_media_login', [
        'name' => _l('social_login_menu_name'),
        'view' => 'social_media_login/settings',
        'position' => 36,
    ]);
}

function google_session_logout()
{
	redirect(site_url('social_media_login/google_logout'));
}

function facebook_session_logout()
{
	redirect(site_url('social_media_login/facebook_logout'));
}

function linkedin_session_logout()
{
	redirect(site_url('social_media_login/linkedin_logout'));
}

function twitter_session_logout()
{
	redirect(site_url('social_media_login/twitter_logout'));
}

function social_media_login_activation_hook()
{
	$from = __DIR__.'/views/my_login.php';
	$to   = FCPATH.'/application/views/themes/perfex/views/my_login.php';
	 
	copy($from, $to);

    $options = array(
        'google_key' => "",
        'google_id' => "",
        'google_btn_status' => "Inactive",
        'linkedin_key' => "",
        'linkedin_id' => "",
        'linkedin_btn_status' => "Inactive",
        'twitter_key' => "",
        'twitter_id' => "",
        'twitter_btn_status' => "Inactive",
        'facebook_key' => "",
        'facebook_id' => "",
        'facebook_btn_status' => "Inactive",
        'social_media_login_module_status' => "Inactive"
    );
    
    foreach ($options as $key => $value)
    {
        update_option($key, $value);
    }
}

function social_media_login_deactivation_hook()
{
	$File   = FCPATH.'/application/views/themes/perfex/views/my_login.php';
	if(file_exists($File))
	{
		unlink($File);
	}
	
}

