<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
Module Name: telegram_chat
Description: Default module for sending telegram_chat
Version: 1.0.0
Requires at least: 2.3.*
*/
hooks()->add_action('admin_init', 'telegram_module_init_menu_items');

/**
 * Init surveys module menu items in setup in admin_init hook
 * @return null
 */
function telegram_module_init_menu_items()
{
    $CI = &get_instance();

    $CI->app->add_quick_actions_link([
            'name'       => 'telegram',
            'permission' => 'telegram_chat',
            'url'        => 'telegram_chat',
            'position'   => 79,
            ]);

    if (has_permission('telegram_chat', '', 'view')) {
        $CI->app_menu->add_sidebar_children_item('utilities', [
                'slug'     => 'telegram_chat',
                'name'     => 'Telegram chat',
                'href'     => admin_url('telegram_chat'),
                'position' => 36,
        ]);
    }
}