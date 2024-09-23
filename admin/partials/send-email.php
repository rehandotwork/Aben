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
    $email_template = aben_get_email_template();

    error_log('aben_send_email function was called at ' . current_time('mysql'));

    $aben_get_posts_result = aben_get_posts_for_email();

    // var_dump($aben_get_posts_result);

    if (!empty($aben_get_posts_result)) {

        $posts_published_today = $aben_get_posts_result['posts_published'];

        $posts_to_send = $aben_get_posts_result['posts_to_email'];

        $post_count = count($posts_to_send);
    }

    if (!empty($posts_published_today)) {

        global $aben_settings;

        $post_archive_slug = $aben_settings['archive_page_slug'];

        $email_subject = $aben_settings['email_subject'];

        $email_body = aben_replace_placeholder($email_template);

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
                    aben_send_smtp_email($email_address, $email_subject, $personalized_email_body);
                    // error_log('Email sent to ' . $email_address . ' using custom SMTP');

                } else {
                    aben_send_own_smtp_email($email_address, $email_subject, $personalized_email_body);
                }
            }

        } else {
            error_log('No user has opted for notification');
        }
    } else {
        echo '<script> console.log("No Posts Found for Today")</script>';
    }
}

function aben_replace_placeholder($template)
{
    global $aben_settings;

    $posts_published_today = aben_get_posts_for_email()['posts_published'];
    // var_dump($posts_published_today);

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

    $processed_email = str_replace(array_keys($placeholders), array_values($placeholders), $template);

    return $processed_email;

}

function aben_get_posts_for_email()
{

    global $aben_settings;
    $email_frequency = $aben_settings['email_frequency'];
    $day_of_week = intval($aben_settings['day_of_week']);

    if ($email_frequency === 'once_in_a_week') {

        $aben_get_posts_result = aben_get_weekly_posts($day_of_week);

    } else {
        $aben_get_posts_result = aben_get_today_posts();
    }

    return $aben_get_posts_result;
}

// $posts = aben_get_posts_for_email();
// var_dump($posts);
