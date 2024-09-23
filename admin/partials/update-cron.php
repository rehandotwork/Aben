<?php //Update Aben cron on Plugin settings update

if (!defined('ABSPATH')) {

    exit;

}

add_action('update_option_aben_options', 'aben_update_cron');

function aben_update_cron()
{
    $cron_settings = aben_get_cron_settings()['sending_frequency'];

    if (wp_next_scheduled('aben_cron_event')) {
        wp_clear_scheduled_hook('aben_cron_event');
    }

    // Schedule for Daily
    if ($cron_settings === 'daily') {
        $timestamp = strtotime('today 23:00:00 +0530'); // at 11 PM India Standard Time

        if (time() >= $timestamp) {
            $timestamp = strtotime('tomorrow 23:00:00 +0530'); // at 11 PM India Standard Time
        }

        wp_schedule_event($timestamp, 'daily', 'aben_cron_event');
        error_log('Daily Aben Cron Updated ' . date('Y-m-d H:i:s', $timestamp));
    }
    // Schedule for Weekly
    else if ($cron_settings === 'weekly') {
        $day_of_week = intval(aben_get_cron_settings()['day_of_week']); // Get the day of the week as an integer
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        // Ensure valid day of the week
        $day_name = isset($days[$day_of_week]) ? $days[$day_of_week] : 'Saturday'; // Default to Saturday if invalid

        $timestamp_weekly = strtotime("next $day_name 23:00:00 +0530");

        wp_schedule_event($timestamp_weekly, 'weekly', 'aben_cron_event');
        error_log('Weekly Aben Cron Updated ' . date('Y-m-d H:i:s', $timestamp_weekly));
    }
}
