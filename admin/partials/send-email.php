<?php
// Send Email

if (!defined('ABSPATH')) {
    exit;
}

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
        $email_body = get_email_body($posts_to_send, $post_count);

        $headers = ['Content-Type:text/html'];
        $email_addresses = aben_get_users_email();

        if (!empty($email_addresses)) {
            foreach ($email_addresses as $email_address) {
                if (wp_mail($email_address, $email_subject, $email_body, $headers)) {
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

function get_email_body($posts_to_send, $post_count)
{
    $email_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gulfworking.com | Daily Gulf Jobs</title>
</head>
<body style="font-family: Open Sans, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="width: 100%; max-width: 646px; margin: 0 auto;">
        <div style="background-image: url(https://stg.gulfworking.com/wp-content/uploads/2024/07/246-2462187_dubai-jobs-banner-hd-png-download-removebg-preview.png); background-position:bottom; background-size: cover; height: 180px; text-align: center;"></div>
    </div>
    <div style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); margin-top: -14px;">
        <div style="background-color: #008dcd; color: #fff; padding: 1px 20px; display: flex;">
            <div style="width:80%">
                <h1>Daily Gulf Jobs</h1>
                <p style="margin-top: -15px;">Get latest gulf jobs for India by authorized HR consultancies</p>
            </div>
            <div style="width:20%;align-content: center;text-align: right;border-left: 1px solid #fff; margin: 10px 0;">
                <p>MY Profile</p>
            </div>
        </div>
        <div style="padding: 20px;">';

    foreach ($posts_to_send as $post) {
        $title = $post['title'];
        $link = $post['link'];
        $author = $post['author'];

        $email_body .= '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid #f1f1f1; padding-bottom: 10px;">
                <div style="width: 70%;">
                    <h2 style="font-size: 20px; color: #008dcd; margin: 0;">'.$title.'/h2>
                    <p style="font-size: 14px; color: #333333; margin: 5px 0 0;">Country</p>
                </div>
                <a href="'.$link.'" style="display: inline-block; padding: 10px 20px; color: #fff; text-decoration: none; background-color: #0EAD5D; border-radius: 25px;">Apply</a>
            </div>';
    }

    $email_body .= '        </div>
        <div style="background-color: #ffffff; text-align: center; padding-bottom: 20px;">
            <a href="https://gulfworking.com/browse-jobs/" style="display: inline-block; padding: 10px 20px; background-color: #DE4D4C; color: #ffffff; text-decoration: none; border-radius: 25px;">View All (35) Jobs</a>
        </div>
        <div style="background-color: #f1f1f1; color: #808080; text-align: center; padding: 10px;">
            <a href="https://gulfworking.com"><img src="https://gulfworking.com/wp-content/uploads/2023/01/finalLogo-cropped-1.svg" alt="Site Logo" style="max-width: 180px; margin: 20px 0 10px 0;"></a>
            <p>Gulfworking.com &copy; 2024 All rights reserved.</p>
            <p>
                <a href="https://gulfworking.com/notifications" style="color: #808080; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>';

    return $email_body;
}
?>
