<?php // Send Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('save_post', 'aben_send_email');

function aben_send_email()
{

    error_log('aben_send_email function was called at ' . current_time('mysql'));

    // if (wp_is_post_autosave($post_id)) {
    //     return;
    // }

    // // Check if this is a revision
    // if (wp_is_post_revision($post_id)) {
    //     return;
    // }

    // // Check if the post is published
    // $post_status = get_post_status($post_id);
    // if ($post_status !== 'publish') {
    //     return;
    // }
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
        $email_body = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Open Sans, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .logo-container {
            width: 100%;
            max-width: 600px;
            margin: 0px auto;
        }
        .logo {
            max-width: 180px;
            margin: 20px 0 10px 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #008dcd;
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .view-all-blogs-container {
            background-color: #ffffff;
            text-align: center;
            padding-bottom: 20px;
        }
        .view-all-blogs {
            display: inline-block;
            padding: 10px 20px;
            background-color: #DE4D4C;;
            color: #ffffff;
            text-decoration: none;
            border-radius: 25px;
        }
        .footer {
            background-color: #f1f1f1;
            color: #808080;
            text-align: center;
            padding: 10px;
        }
        .blog-post {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #f1f1f1;
            padding-bottom: 10px;
        }
        .blog-post-content {
            width: 70%;
        }
        .blog-post-title {
            font-size: 20px;
            color: #008dcd;
            margin: 0;
        }
        .blog-post-summary {
            font-size: 16px;
            color: #333333;
            margin: 5px 0 0;
        }
        .apply-now {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            text-decoration: none;
            background-color: #165d31;
            border-radius: 25px;
        }
        @media (max-width: 600px) {
            .blog-post {
                flex-direction: column;
                align-items: flex-start;
            }
            .blog-post-content {
                width: 100%;
            }
            .apply-now {
                margin-top: 10px;
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="logo-container">
        <a href="https://gulfworking.com"><img src="https://gulfworking.com/wp-content/uploads/2023/01/finalLogo-cropped-1.svg" alt="Site Logo" class="logo"></a>
    </div>
    <div class="container">
        <div class="header">
            <h1>Daily Gulf Jobs</h1>
            <p>Get latest gulf jobs by authorized human resource consultancies</p>
        </div>
        <div class="content">

        foreach ($posts_to_send as $post) { // Appending Fetched Posts to Email Body

            $title = $post['title'];
            $link = $post['link'];
            $author = $post['author'];
            // echo $title;

            // $email_body .= '<h4>' . $title . ' <a href = ' . $link . '>Apply Here</a></h4>';

            $email_body .= '<div class="blog-post">
                <div class="blog-post-content">
                    <h2 class="blog-post-title">'.$title.'</h2>
                    <p class="blog-post-summary">Location: Country | By: '.$author.'</p>
                </div>
                <a href="'.$link.'" class="apply-now">Apply Now</a>
            </div>';

        }

        $email_body .= '</div>
        <div class="view-all-blogs-container">
            <a href="http://yourwebsite.com/blog" class="view-all-blogs">View All (35) Jobs</a>
        </div>
        <div class="footer">
            <p>Gulfworking.com &copy; 2024 All rights reserved.</p>
            <p>
                <a href="http://yourwebsite.com/unsubscribe" style="color: #808080; text-decoration: none;">Unsubscribe</a>
            </p>
        </div>
    </div>
</body>
</html>';

        // $email_body .= $posts_published_today > 10 ?
        // '<p>Check ' . $posts_published_today - 10 . ' more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here.</a></p>' :
        // '<section><p>Check more posts <a href = "' . home_url('/' . $post_archive_slug) . '">here</a></p>';

        // echo $email_body;

        $headers = ['Content-Type:text/html'];

        $email_addresses = aben_get_users_email();

        // print_r($email_addresses);
        // error_log(var_export($email_addresses, true));

        if (!empty($email_addresses)) {

            foreach ($email_addresses as $index => $email_address) {

                // echo $email_address;
                if (wp_mail($email_address, $email_subject, $email_body, $headers)) {
                    error_log('Email sent to' . $email_address);
                    // unset($email_addresses[$index]);

                    // print_r($email_addresses);

                }
                // echo ('Mail Sent');

                // echo count($email_addresses);

            }
        } else {
            error_log('No user has opted for notification');
        }

    } else {
        echo '<script> console.log("No Posts Found for Today")</script>';
    }

}

// aben_send_email();
