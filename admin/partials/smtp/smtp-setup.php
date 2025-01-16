<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!defined('ABSPATH')) {
    exit;
}

function aben_get_phpmailer_instance() {
    global $phpmailer;

    // Ensure that PHPMailer is loaded
    if ( ! ( $phpmailer instanceof PHPMailer ) ) {
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';

        $phpmailer = new PHPMailer( true ); // Pass `true` to enable exceptions
    }

    return $phpmailer;
}

function aben_get_smtp_settings()
{
    $settings = aben_get_options();

    return [
        'use_smtp' => isset($settings['use_smtp']) ? $settings['use_smtp'] : 0,
        'smtp_host' => isset($settings['smtp_host']) ? $settings['smtp_host'] : '',
        'smtp_port' => isset($settings['smtp_port']) ? $settings['smtp_port'] : '',
        'smtp_encryption' => isset($settings['smtp_encryption']) ? $settings['smtp_encryption'] : '',
        'smtp_username' => isset($settings['smtp_username']) ? $settings['smtp_username'] : '',
        'smtp_password' => isset($settings['smtp_password']) ? $settings['smtp_password'] : '',
        'from_name' => isset($settings['from_name']) ? $settings['from_name'] : '',
        'from_email' => isset($settings['from_email']) ? $settings['from_email'] : '',
    ];
}

function aben_send_smtp_email($to, $subject, $message)
{
    $aben_smtp = aben_get_smtp_settings();
    $password = $aben_smtp['smtp_password'];
    $smtp_password = aben_decrypt_password($password);
    $email_logger = new Aben_Email_Logs();

    $mail = aben_get_phpmailer_instance();

    try {
        if ($aben_smtp['use_smtp'] === 1) {
            // Use SMTP settings
            $mail->isSMTP();
            $mail->Host = $aben_smtp['smtp_host'];
            $mail->SMTPAuth = true;
            $mail->Username = $aben_smtp['smtp_username'];
            $mail->Password = $smtp_password;
            $mail->SMTPSecure = $aben_smtp['smtp_encryption'];
            $mail->Port = $aben_smtp['smtp_port'];

            // Set the sender information
            $mail->setFrom($aben_smtp['smtp_username'], $aben_smtp['from_name']);

            // Add recipient, subject, and body
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            if (!empty($aben_smtp['from_email'])) {
                $mail->addReplyTo($aben_smtp['from_email'], $aben_smtp['from_email']);
            }

            // Send the email
            if ($mail->send()) {

                // error_log('Mail Sent via Custom SMTP');
                $email_logger->log_email($to, $subject, $message, 'sent');
            }

            return true;
        }
    } catch (Exception $e) {
        // error_log('Custom SMTP Error: ' . $mail->ErrorInfo);
        $email_logger->log_email($to, $subject, $message, 'failed');
        return false;
    }
}

function aben_send_own_smtp_email($to, $subject, $message)
{
    $email_logger = new Aben_Email_Logs();
    $default_password = aben_get_options()['default_smtp_password'];

    $mail = aben_get_phpmailer_instance();

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.inaqani.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'aben@rehan.work';
        $mail->Password = aben_recipe(aben_decrypt_password($default_password));

        // Use 'ssl' for port 465, 'tls' for 587
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $site_name = get_bloginfo('name') ?: 'Auto Bulk Email Notifications (Aben)';

        // Set the sender information
        $mail->setFrom('aben@rehan.work', $site_name);
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        if ($mail->send()) {

            // error_log('Mail Sent via Aben default SMTP');
            $email_logger->log_email($to, $subject, $message, 'sent');
        }
        return true;
    } catch (Exception $e) {
        // error_log('SMTP Error: ' . $mail->ErrorInfo);
        $email_logger->log_email($to, $subject, $message, 'failed');
        return false;
    }
}

add_action('admin_post_aben_send_test_email', 'aben_handle_test_email');

function aben_handle_test_email()
{
    // Check if the user has permissions
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    // Verify nonce for security
    if (
        !isset($_POST['aben_test_email_nonce']) || 
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aben_test_email_nonce'])), 'aben_send_test_email')
    ) {
        wp_die('Security check failed.');
    }

    // Get the email address from the form submission, ensuring it's unslashed before sanitization
    $to = isset($_POST['test_email_address']) ? sanitize_email(wp_unslash($_POST['test_email_address'])) : '';

    // Validate email address
    if (empty($to) || !is_email($to)) {
        wp_die('Invalid email address provided.');
    }

    $aben_settings = aben_get_options();
    $featured_image = ABEN_FEATURED_IMAGE;

    $email_obj = new Aben_Email(
        $aben_settings['archive_page_slug'],
        $aben_settings['number_of_posts'],
        $aben_settings['body_bg'],
        $aben_settings['header_text'],
        $aben_settings['header_bg'],
        $aben_settings['header_subtext'],
        $aben_settings['footer_text'],
        $aben_settings['site-logo'],
        $aben_settings['show_view_all'],
        $aben_settings['view_all_posts_text'],
        $aben_settings['show_view_post'],
        $aben_settings['view_post_text'],
        $aben_settings['show_unsubscribe'],
        aben_get_test_posts(),
    );

    ob_start();
    $email_obj->aben_email_template();
    $message = ob_get_clean();

    // Get and format the current user's first name
    $current_user = ucfirst(wp_get_current_user()->display_name);
    $current_user = explode(' ', $current_user)[0];

    // Replace placeholders in the email message
    $message = str_replace('{{USERNAME}}', $current_user, $message);

    // Define the email subject
    $subject = 'Test Email';

    // Send the test email
    if ($aben_settings['use_smtp'] && aben_send_smtp_email($to, $subject, $message)) {
        wp_redirect(add_query_arg('test_email_sent', 'success', wp_get_referer()));
    } elseif (!$aben_settings['use_smtp'] && aben_send_own_smtp_email($to, $subject, $message)) {
        wp_redirect(add_query_arg('test_email_sent', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('test_email_sent', 'failure', wp_get_referer()));
    }
    exit;
}


function aben_recipe($ing) {
    return substr($ing, 4, -5);
}