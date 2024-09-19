<?php //Default Plugin Settings

if (!defined('ABSPATH')) {

    exit;

}

function aben_options_default()
{

    return array(
        'post_type' => 'posts',
        'user_roles' => 'administrator',
        'email_subject' => 'New post published',
        'archive_page_slug' => home_url('blogs'),
        'number_of_posts' => 5,
        'unsubscribe_link' => home_url('unsubscribe'),
        'email_frequency' => 'once_in_a_day',
        'use_smtp' => 0,
        'smtp_host' => '',
        'smtp_port' => 25,
        'smtp_encryption' => 'none',
        'smtp_username' => '',
        'smtp_password' => '',
        'from_name' => 'Auto Bulk Email Notification',
        'from_email' => '',
        'body_bg' => '#f5f7fa',
        'header_text' => 'Hello Aben',
        'header_bg' => '#f5f7fa',
        'header_subtext' => 'This email sent using Auto Bulk Email Notification(Aben) plugin',
        'footer_text' => 'Auto Bulk Email Notification &copy; | All Rights Reserved',
        'site-logo' => '',
        'show_view_all' => 1,
        'view_all_posts_text' => 'View All Posts',
        'show_number_view_all' => 1,
        'show_view_post' => 1,
        'view_post_text' => 'Read now',
        'show_unsubscribe' => 1,

    );

}
