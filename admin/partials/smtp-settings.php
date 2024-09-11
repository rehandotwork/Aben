<?php // ABEN Settings Page

if (!defined('ABSPATH')) {

    exit;

}

add_action('admin_notices', 'get_smtp_settings');

function get_smtp_settings()
{

    $headers = array('Content-Type: text/html; charset=UTF-8');
    $subject = "Aben Test Email";
    $message = "This is a test email from the Aben(Auto Bulk Email Notifications";

    $settings = aben_get_options();

    if ($settings) {
        echo "SMTP Function Executed";

    }
}
