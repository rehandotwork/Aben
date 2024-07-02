<?php // Send Email

if (!defined('ABSPATH')) {

    exit;

}

add_action('save_post', 'aben_send_email');
function aben_send_email()
{
    $aben_get_posts_result = aben_get_today_posts(); // Refer to email-settings.php

    if (!empty($aben_get_posts_result)) {

        $posts_published_today = $aben_get_posts_result['posts_published_today']; //Refer to email-settings.php

        $posts_to_send = $aben_get_posts_result['posts_to_email'];
        // var_dump($posts_to_send);

    }

    if (!empty($posts_published_today)) {

        // echo ($posts_published_today);

        $get_settings = aben_get_options();

        $post_archive_slug = $get_settings['archive_page_slug'];
        // echo $post_archive_slug;

        $email_subject = $get_settings['email_subject'];

        $email_body = $get_settings['email_body'];

        foreach ($posts_to_send as $post) { // Appending Fetched Posts to Email Body

            $title = $post['title'];
            $link = $post['link'];
            $author = $post['author'];
            // echo $title;

            $email_body .= '<h4>' . $title . ' <a href = ' . $link . '>Apply Here</a></h4>';

        }

        $email_body .= $posts_published_today > 10 ?
        '<p>Check ' . $posts_published_today - 10 . ' more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here.</a></p>' :
        '<section><p>Check more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here</a></p>';

        // echo $email_body;

        $headers = ['Content-Type:text/html'];

        $email_addresses = aben_get_users_email();

        // print_r($email_addresses);

        foreach ($email_addresses as $email_address) {

            wp_mail($email_address, $email_subject, $email_body, $headers);
            // echo "Mail Sent";

        }

    } else {
        echo '<script> console.log("No Posts Found for Today")</script>';
    }

}

// aben_send_email();
