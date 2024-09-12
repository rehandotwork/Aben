<?php

if (!defined('ABSPATH')) {
    exit;
}

// Function to send test email using wp_mail()
function aben_send_test_email()
{
    // Email details
    $to = 'test@example.com'; // Recipient email address
    $subject = 'SMTP Test Email via wp_mail';
    $message = '<p>This is a test email sent using wp_mail function.</p>';
    $headers = array('Content-Type: text/html; charset=UTF-8');

    // Send email
    if (wp_mail($to, $subject, $message, $headers)) {
        echo '<div class="notice notice-success"><p>Email sent successfully to ' . esc_html($to) . '.</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>Message could not be sent.</p></div>';
    }
}

// Trigger email on the admin notice hook
add_action('admin_notices', 'aben_send_test_email');
