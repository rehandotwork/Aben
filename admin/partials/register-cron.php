<?php // Set ABEN Cron
if (!defined('ABSPATH')) {

    exit;

}

// register_activation_hook(__FILE__, 'aben_register_cron');
add_action('aben_send_email_event', 'aben_send_email');

function aben_register_cron()
{
    $cron_settings = aben_get_cron_settings();
    // echo $cron_settings;

    if (!wp_next_scheduled('aben_send_email_event')) {
        // Schedule daily at 11 PM (23:00)
        wp_schedule_event(strtotime('23:00:00'), $cron_settings, 'aben_send_email_event');
    }

}

// register_deactivation_hook(__FILE__, 'aben_deregister_cron');

function aben_deregister_cron()
{
    wp_clear_scheduled_hook('aben_send_email_event');
}
