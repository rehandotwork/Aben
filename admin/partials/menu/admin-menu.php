<?php // ABEN Menu

if (!defined('ABSPATH')) {

    exit;

}

function aben_add_top_level_menu()
{
    add_menu_page(
        'Settings: Auto Bulk Email Notifications',
        'Aben',
        'manage_options',
        'aben',
        'aben_display_settings_page',
        'dashicons-email-alt2',
        26,
    );
}

add_action('admin_menu', 'aben_add_top_level_menu');
