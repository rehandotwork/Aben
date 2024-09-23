<?php // Set ABEN Cron
if (!defined('ABSPATH')) {

    exit;

}

add_action('aben_cron_event', 'aben_send_email');
// add_action('admin_notices', 'aben_register_cron');

function aben_register_cron()
{
    error_log('aben_register_cron called');
    $cron_settings = aben_get_cron_settings()['sending_frequency'];
    // echo ($cron_settings);
    $day_of_week = intval(aben_get_cron_settings()['day_of_week']);
    // var_dump($day_of_week);

    if ($cron_settings === 'daily') {

        if (!wp_next_scheduled('aben_cron_event')) {

            $timestamp = strtotime('today 23:00:00 +0530'); // at 11PM India Standard Time

            if (time() >= $timestamp) {

                $timestamp = strtotime('tomorrow 23:00:00 +0530'); // at 11PM India Standard Time

            }

            wp_schedule_event($timestamp, $cron_settings, 'aben_cron_event');

            error_log('aben_cron_event scheduled at ' . date('Y-m-d H:i:s', time()));

        }
    } else if ($cron_settings === 'weekly') {

        if (!wp_next_scheduled('aben_cron_event')) {

            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            $day_of_week = isset($day_of_week) ? $days[$day_of_week] : 'Saturday'; // Default to Friday if invalid.

            $timestamp_weekly = strtotime("next $day_of_week 23:00:00 +0530");

            wp_schedule_event($timestamp_weekly, 'weekly', 'aben_cron_event');

            error_log('Weekly aben_cron_event scheduled at ' . date('Y-m-d H:i:s', $timestamp_weekly));
        }
    }
}

function aben_deregister_cron()
{
    // wp_unschedule_event( $timestamp:integer, $hook:string, $args:array, $wp_error:boolean )
    error_log('aben_deregister_cron called');

    wp_clear_scheduled_hook('aben_cron_event');
}
