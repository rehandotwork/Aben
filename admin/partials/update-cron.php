<?php //Update Aben cron on Plugin settings update

if (!defined('ABSPATH')) {

    exit;

}

function aben_update_cron()
{
    $cron_settings = aben_get_cron_settings();

    if (wp_next_scheduled('aben_cron_event')) {

        // $timestamp = wp_next_scheduled('aben_cron_event');
        // echo $timestamp;

        wp_clear_scheduled_hook('aben_cron_event');

        wp_schedule_event(time(), $cron_settings, 'aben_cron_event');

    }

}
