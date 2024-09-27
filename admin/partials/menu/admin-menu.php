<?php // ABEN Menu

if (!defined('ABSPATH')) {

    exit;

}

function aben_add_top_level_menu()
{
    add_menu_page(
        'Aben Settings',
        'Aben',
        'manage_options',
        'aben',
        'aben_display_settings_page',
        'dashicons-email-alt',
        26,
    );
}

add_action('admin_menu', 'aben_add_top_level_menu');
