<?php
// Send Email

if (!defined('ABSPATH')) {
    exit;
}

require_once 'email-settings.php';

$aben_settings = aben_get_options();

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

        global $aben_settings;

        $post_archive_slug = $aben_settings['archive_page_slug'];

        $email_subject = $aben_settings['email_subject'];

        $email_body = get_email_body($posts_to_send, $post_count, $posts_published_today, $post_archive_slug);

        $admin_email = get_bloginfo('admin_email');

        $headers[] = 'Content-Type:text/html';

        // $headers[] = 'From: ' . get_bloginfo('name') . ' <' . $admin_email . '>';

        $email_addresses = aben_get_users_email();

        if (!empty($email_addresses)) {

            foreach ($email_addresses as $email_address) {

                $user = get_user_by('email', $email_address);

                $user_display_name = ucwords($user->display_name);

                $user_display_name = explode(' ', $user_display_name);

                $user_firstname = $user_display_name[0];

                // echo $user_firstname;

                $personalized_email_body = str_replace('{{USERNAME}}', $user_firstname, $email_body); // Changing placeholders in email body

                if (1 === $aben_settings['use_smtp']) {

                    aben_send_smtp_email($email_address, $email_subject, $personalized_email_body) ? error_log('Email sent to ' . $email_address . ' using SMTP') : '';

                } else {

                    wp_mail($email_address, $email_subject, $personalized_email_body, $headers) ? error_log('Email sent to ' . $email_address . ' using wp_mail') : '';

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
    global $aben_settings;

    //Text
    $header_text = $aben_settings['header_text'];
    $header_subtext = $aben_settings['header_subtext'];
    $footer_text = $aben_settings['footer_text'];
    $view_all_posts_text = $aben_settings['view_all_posts_text'];
    $view_post_text = $aben_settings['view_post_text'];
    $unsubscribe_link = $aben_settings['unsubscribe_link'];
    $archive_page_slug = $aben_settings['archive_page_slug'];

    //Stlying
    $header_bg = $aben_settings['header_bg'];
    $body_bg = $aben_settings['body_bg'];

    //Media
    $site_logo = $aben_settings['site_logo'];

    $placeholders = array(

        '{{HEADER_TEXT}}' => $header_text,
        '{{HEADER_SUBTEXT}}' => $header_subtext,
        '{{VIEW_POST_TEXT}}' => $view_post_text,
        '{{VIEW_ALL_POSTS_TEXT}}' => $view_all_posts_text,
        '{{POSTS_NUMBER}}' => $posts_published_today,
        '{{FOOTER_TEXT}}' => $footer_text,
        '{{SITE_LOGO}}' => $site_logo,
        '{{UNSUBSCRIBE_LINK}}' => $unsubscribe_link,
        '{{ALL_POSTS_PAGE_LINK}}' => $archive_page_slug,

        '{{BODY_BG}}' => $body_bg,
        '{{HEADER_BG}}' => $header_bg,

    );

    //Email Template
    $email_template = aben_get_email_template();

    $email_template = str_replace(array_keys($placeholders), array_values($placeholders), $email_template);

    $email_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gulfworking.com | Daily Gulf Jobs</title>
</head>
<body style="font-family: Open Sans, sans-serif; margin: 0; padding: 0;">
    <div style="width:100%;max-width:600px;margin:auto;box-shadow: 0 0 10px #00000014;">
        <div style="background-color:' . $header_bg . ';padding:1px 20px;color: #fff;line-height: 1;">
           <h1 style="color: #fff">Daily Gulf Jobs</h1>
           <p><strong>Hi {{USERNAME}}, </strong>apply to the latest Gulf jobs below</p>
        </div>
        <div style="padding:20px 20px 10px 20px;background: ' . $body_bg . '">';

    foreach ($posts_to_send as $post) {
        $title = $post['title'];
        $link = $post['link'];
        $author = $post['author'];
        $country = $post['country'];

        $email_body .= '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; border-bottom: 1px solid #f1f1f1; padding-bottom: 10px;">
                <div style="width: 80%;">
                    <h2 style="font-size: 16px; color: #008dcd; margin: 0;">' . $title . '</h2>
                    <p style="font-size: 14px; color: #333333; margin: 5px 0 0;">Location - ' . $country . '</p>
                </div>
				<div style="width:20%;text-align:center;align-content: center;">
                <a href="' . $link . '" style="display: inline-block; padding: 5px 20px; color: #fff; text-decoration: none; background-color: #0EAD5D; border-radius: 25px; height: fit-content;align-self:center;">' . $view_post_text . '</a>
				</div>
            </div>';
    }

    $email_body .= '<div style="display:flex;padding:10px;">
			<div style="width:100%;text-align:center;">
				<a href="' . home_url($archive_page_slug) . '" style="display: inline-block; padding: 10px 20px; background-color: #DE4D4C; color: #ffffff; text-decoration: none; border-radius: 25px;">' . $view_all_posts_text . ' (' . $posts_published_today . ')</a>
			</div>
			</div>
		</div>
        <div style="background-color:#efefef;color:#808080;text-align:center;padding:20px;">
            <a href="' . home_url() . '"><img src="' . $site_logo . '" alt="Site Logo" style="max-width:180px;margin-top:10px;"></a>
            <p>' . $footer_text . '</p>
            <p style="margin-top: -10px;">
                <a href="' . home_url($unsubscribe_link) . '" style="color: #808080; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>';

    return $email_template;
}
