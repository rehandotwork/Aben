<?php // Set up settings to register Cron

if (!defined('ABSPATH')) {

    exit;

}

function aben_get_cron_settings()
{
    $settings = aben_get_options();

    $email_frequency = $settings['email_frequency'];

    $sending_frequency = '';

    switch ($email_frequency) {

        case 'once_in_a_day':
            $sending_frequency = 'daily';
            break;
        case 'once_in_a_week':
            $sending_frequency = 'weekly';
            break;
        default:
            $sending_frequency = 'daily';

    }

    return $sending_frequency;

}
