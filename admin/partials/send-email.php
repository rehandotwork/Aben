<?php // Send Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('save_post', 'aben_send_email', 10, 2);

function aben_send_email($post_id, $post)
{

    $get_settings = aben_get_options();

    $email_subject = $get_settings['email_subject'];

    $email_body = $get_settings['email_body'];

    $headers = ['Content-Type:text/html'];

    $email_addresses = aben_get_users_email();

    foreach ($email_addresses as $email_address) {

        wp_mail($email_address, $email_subject, $email_body, $headers);

    }

}
