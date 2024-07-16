<?php //Update Aben cron on Plugin settings update

if (!defined('ABSPATH')) {

    exit;

}

function aben_update_cron()
{
    $cron_settings = aben_get_cron_settings();

    if (wp_next_scheduled('aben_cron_event')) {

        wp_clear_scheduled_hook('aben_cron_event');

        $timestamp = strtotime('today 18:30:00 +0530'); // at 11PM India Standard Time

        if (time() >= $timestamp) {

            $timestamp = strtotime('tomorrow 18:30:00 +0530'); // at 11PM India Standard Time

        }

        wp_schedule_event($timestamp, 'aben_five_seconds', 'aben_cron_event');
        error_log('aben_cron_event updated at ' . date('Y-m-d H:i:s', time()));

    }

}
