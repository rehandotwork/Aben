<?php // Set ABEN Cron
if (!defined('ABSPATH')) {

    exit;

}

add_action('aben_cron_event', 'aben_send_email');

function aben_register_cron()
{
    error_log('aben_register_cron called');

    if (!wp_next_scheduled('aben_cron_event')) {

        $cron_settings = aben_get_cron_settings();

        $timestamp = strtotime('today 18:30:00 +0530'); // at 11PM India Standard Time

        if (time() >= $timestamp) {

            $timestamp = strtotime('tomorrow 18:30:00 +0530'); // at 11PM India Standard Time

        }

        wp_schedule_event($timestamp, 'aben_five_seconds', 'aben_cron_event');

        error_log('aben_cron_event scheduled at ' . date('Y-m-d H:i:s', time()));

    }
}

function aben_deregister_cron()
{
    // wp_unschedule_event( $timestamp:integer, $hook:string, $args:array, $wp_error:boolean )
    error_log('aben_deregister_cron called');

    wp_clear_scheduled_hook('aben_cron_event');
}
