<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if (!defined('ABSPATH')) {
    exit;
}

function aben_get_email_template()
{
    // Start output buffering to capture the output
    ob_start();

    // Include the email template to process PHP code
    $email_template_path = plugin_dir_path(__FILE__) . 'email-template.php';

    if (file_exists($email_template_path)) {
        include $email_template_path; // This will execute PHP code inside the template
    } else {
        return ''; // Return an empty string if the file does not exist
    }

    // Get the output content and stop buffering
    $email_template = ob_get_clean();

    // Return the processed template content
    return $email_template;
}

// Include PHPMailer from the plugin's `includes` directory
require_once plugin_dir_path(__DIR__) . '../../includes/vendor/autoload.php';

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
    $email_template = aben_get_email_template();
    $password = $aben_smtp['smtp_password'];
    $smtp_password = aben_decrypt_password($password);

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

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
            $mail->addReplyTo($aben_smtp['from_email'], $aben_smtp['from_email']);
            $mail->Subject = $subject;
            $mail->Body = $message;

            // Send the email
            $mail->send();
            error_log('Mail Sent via Custom SMTP');
            return true;
        }
    } catch (Exception $e) {
        error_log('Custom SMTP Error: ' . $mail->ErrorInfo);
        return false;
    }
}

function aben_send_own_smtp_email($to, $subject, $message)
{
    $email_template = aben_get_email_template();

    // Create a new PHPMailer instance
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'mail.inaqani.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'notify@rehan.work';
        $mail->Password = 'qXTsK_%I#,%3';

        // Use 'ssl' for port 465, 'tls' for 587
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Set the sender information
        $mail->setFrom('notify@rehan.work', 'Aben');
        $mail->addAddress($to);
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        // Send the email
        $mail->send();
        error_log('Mail Sent via SMTP');
        return true;
    } catch (Exception $e) {
        error_log('SMTP Error: ' . $mail->ErrorInfo);
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
    if (!isset($_POST['aben_test_email_nonce']) || !wp_verify_nonce($_POST['aben_test_email_nonce'], 'aben_send_test_email')) {
        wp_die('Security check failed.');
    }

    // Get the email address from the form submission
    $to = isset($_POST['test_email_address']) ? sanitize_email($_POST['test_email_address']) : '';

    $aben_settings = aben_get_options();

    $email_obj = new Aben_Email(
        $aben_settings['archive_page_slug'],
        $aben_settings['number_of_posts'],
        $aben_settings['body_bg'],
        $aben_settings['header_text'],
        $aben_settings['header_bg'],
        $aben_settings['header_subtext'],
        $aben_settings['footer_text'],
        $aben_settings['site_logo'],
        $aben_settings['show_view_all'],
        $aben_settings['view_all_posts_text'],
        $aben_settings['show_view_post'],
        $aben_settings['view_post_text'],
        $aben_settings['show_unsubscribe'],
        [
            [
                'title' => 'Understanding WordPress Plugins',
                'link' => 'https://example.com/understanding-wordpress-plugins',
                'excerpt' => 'Learn about the basics of WordPress plugins, how they work, and why they are useful.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => '10 Tips for Optimizing Your Website',
                'link' => 'https://example.com/optimizing-your-website',
                'excerpt' => 'Follow these essential tips to ensure your website runs smoothly and efficiently.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'The Importance of SEO in 2024',
                'link' => 'https://example.com/importance-of-seo',
                'excerpt' => 'SEO remains crucial for online success. Discover how to stay ahead in 2024.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'Best Practices for Web Development',
                'link' => 'https://example.com/web-development-best-practices',
                'excerpt' => 'Adopt these best practices to enhance your web development workflow and deliver top-notch projects.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
            [
                'title' => 'How to Boost Website Security',
                'link' => 'https://example.com/boost-website-security',
                'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                'featured_image_url' => 'https://styles.redditmedia.com/t5_2qh49/styles/communityIcon_357lawpgz5x11.png',
            ],
        ]
    );

    ob_start();
    $email_obj->aben_email_template();
    $message = ob_get_clean();
    $subject = 'Test Email';

    // Send the test email
    if ($aben_settings['use_smtp'] && aben_send_smtp_email($to, $subject, $message)) {
        wp_redirect(add_query_arg('test_email_sent', 'success', wp_get_referer()));
    } else if (!$aben_settings['use_smtp'] && aben_send_own_smtp_email($to, $subject, $message)) {
        wp_redirect(add_query_arg('test_email_sent', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('test_email_sent', 'failure', wp_get_referer()));
    }
    exit;
}
