<?php
// Send Email

if (!defined('ABSPATH')) {
    exit;
}
// add_action('admin_notices', 'aben_send_email');

function aben_send_email()
{
    error_log('aben_send_email function was called at ' . current_time('mysql'));

    $aben_get_posts_result = aben_get_today_posts();

    if (!empty($aben_get_posts_result)) {

        $posts_published_today = $aben_get_posts_result['posts_published_today'];

        $posts_to_send = $aben_get_posts_result['posts_to_email'];

        $post_count = count($posts_to_send);
    }

    if (!empty($posts_published_today)) {

        $get_settings = aben_get_options();

        $post_archive_slug = $get_settings['archive_page_slug'];

        $email_subject = $get_settings['email_subject'];

        $email_body = get_email_body($posts_to_send, $post_count, $posts_published_today, $post_archive_slug);

        $admin_email = get_bloginfo('admin_email');

        $headers[] = 'Content-Type:text/html';

        $headers[] = 'From: Gulfworking.com | Daily Gulf Jobs <' . $admin_email . '>';

        $email_addresses = aben_get_users_email();

        if (!empty($email_addresses)) {

            foreach ($email_addresses as $email_address) {

                $user = get_user_by('email', $email_address);

                $user_display_name = ucwords($user->display_name);

                $user_display_name = explode(' ', $user_display_name);

                $user_firstname = $user_display_name[0];

                // echo $user_firstname;

                $personalized_email_body = str_replace('{{USERNAME}}', $user_firstname, $email_body); // Changing placeholders in email body

                if (wp_mail($email_address, $email_subject, $personalized_email_body, $headers)) {

                    error_log('Email sent to ' . $email_address);

                }
            }

        } else {
            error_log('No user has opted for notification');
        }
    } else {
        echo '<script> console.log("No Posts Found for Today")</script>';
    }
}

function get_email_body($posts_to_send, $post_count, $posts_published_today, $post_archive_slug)
{
    $email_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gulfworking.com | Daily Gulf Jobs</title>
</head>
<body style="font-family: Open Sans, sans-serif; margin: 0; padding: 0; background-color: #F4F4F4;">
    <div style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #FFFFFF; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-top: 10px;">
        <div style="background-color: #008DCD; color: #fff; padding: 1px 20px;">
            <div style="width:80%">
                <h1>Daily Gulf Jobs</h1>
                <p style="margin-top: -15px;">Get latest gulf jobs for India by authorized HR consultancies</p>
            </div>
        </div><div style="background-color: #fff; color: #333; padding: 1px 20px; border-bottom: 1px solid #ddd;">
            <div style="width:80%">
                <p><strong>Hi {{USERNAME}}, </strong>apply to the latest Gulf jobs below</p>
            </div>
        </div>
        <div style="padding: 20px;">';

    foreach ($posts_to_send as $post) {
        $title = $post['title'];
        $link = $post['link'];
        $author = $post['author'];
        $country = $post['country'];

        $email_body .= '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f1f1; padding-bottom: 10px;">
                <div style="width: 70%;">
                    <h2 style="font-size: 20px; color: #008dcd; margin: 0;">' . $title . '</h2>
                    <p style="font-size: 14px; color: #333333; margin: 5px 0 0;">Location - ' . $country . '</p>
                </div>
                <a href="' . $link . '" style="display: inline-block; padding: 10px 20px; color: #fff; text-decoration: none; background-color: #0EAD5D; border-radius: 25px;">Apply</a>
            </div>';
    }

    $email_body .= '        </div>
        <div style="background-color: #ffffff; text-align: center; padding-bottom: 20px;">
            <a href="' . home_url($post_archive_slug) . '" style="display: inline-block; padding: 10px 20px; background-color: #DE4D4C; color: #ffffff; text-decoration: none; border-radius: 25px;">View All (' . $posts_published_today . ') Jobs</a>
        </div>
        <div style="background-color: #f1f1f1; color: #808080; text-align: center; padding: 10px;">
            <a href="' . home_url() . '"><img src="https://gulfworking.com/wp-content/uploads/2023/01/finalLogo-cropped-1.svg" alt="Site Logo" style="max-width: 180px; margin: 20px 0 10px 0;"></a>
            <p>Gulfworking.com &copy; 2024 All rights reserved.</p>
            <p>
                <a href="' . home_url('account/notification') . '" style="color: #808080; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>';

    return $email_body;
}
