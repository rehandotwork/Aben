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
        'auto-bulk-email-notifications',
        'aben_display_settings_page',
        'dashicons-email-alt2',
        26,
    );

    add_submenu_page(
        'auto-bulk-email-notifications',
        'Buy Pro',
        'Buy Pro',
        'manage_options',
        'aben-buy-pro',
        'aben_display_pro_page' );
}

add_action('admin_menu', 'aben_add_top_level_menu');