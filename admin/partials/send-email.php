<?php // Send Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('save_post', 'aben_send_email', 10);

function aben_send_email()
{

    $aben_get_posts_result = aben_get_today_posts();

    $posts_to_send = $aben_get_posts_result['posts_to_email'];
    // var_dump($posts_to_send);

    $posts_published_today = $aben_get_posts_result['posts_published_today'];

    $get_settings = aben_get_options();

    $email_subject = $get_settings['email_subject'];

    $email_body = $get_settings['email_body'];

    foreach ($posts_to_send as $post) { // Appending Fetched Posts to Email Body

        $title = $post['title'];
        $link = $post['link'];
        // echo $title;

        $email_body .= '<h3>' . $title . '</h3><a href = ' . $link . '>Apply Now</a>';

    }

    $email_body .= $posts_published_today > 10 ?
    '<p>Check ' . $posts_published_today - 10 . ' more posts <a href = "' . home_url('/browse-jobs') . '">here</a></p>.' :
    '<p>Check more posts <a href = "' . home_url('/browse-jobs') . '">here</a></p>';
    // echo $email_body;

    $headers = ['Content-Type:text/html'];

    $email_addresses = aben_get_users_email();

    foreach ($email_addresses as $email_address) {

        wp_mail($email_address, $email_subject, $email_body, $headers);

    }

}

// aben_send_email();
