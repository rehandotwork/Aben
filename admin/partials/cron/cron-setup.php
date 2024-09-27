<?php // Set up settings to register Cron

if (!defined('ABSPATH')) {

    exit;

}
// require_once 'email-build.php';

// add_action('admin_notices', 'aben_get_cron_settings');

// print_r($settings);

function aben_get_cron_settings()
{
    $settings = aben_get_options();

    $email_frequency = $settings['email_frequency'];
    // var_dump($email_frequency);
    $day_of_week = intval($settings['day_of_week']);

    $email_time = $settings['email_time'];

    $timezone = isset($settings['timezone']) ? $settings['timezone'] : wp_timezone_string();

    $sending_frequency = '';

    switch ($email_frequency) {

        case 'once_in_a_day':
            $sending_frequency = 'daily';
            break;
        case 'once_in_a_week':
            $sending_frequency = 'weekly';
            break;
        default:
            $sending_frequency = 'weekly';

    }

    return [
        'sending_frequency' => $sending_frequency,
        'day_of_week' => $day_of_week,
        'email_time' => $email_time,
        'timezone' => $timezone,
    ];

}

// $cron_setting = aben_get_cron_settings();
// var_dump($cron_setting);
