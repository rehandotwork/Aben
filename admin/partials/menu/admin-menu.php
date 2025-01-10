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

    //Sub Menu Pages
    add_submenu_page(
        'aben',
        'Dashboard',
        'Dashborad',
        'manage_options',
        'aben',
        'aben_display_settings_page'
    );
    add_submenu_page(
        'aben',
        'Event',
        'Event',
        'manage_options',
        'aben-events',
        'aben_display_events_page'
    );

}
add_action('admin_menu', 'aben_add_top_level_menu');