<?php // Build Email

if (!defined('ABSPATH')) {

    exit;

}

function aben_get_options() //Fetching Plugin Settings and Returning in Array
{

    $options = get_option('aben_options', 'aben_options_default');
    $setting = array();

    foreach ($options as $key => $value) {

        $setting[$key] = $value;

    }

    return $setting;

}

// Function to get users email with user meta "aben_notification" set to true
function aben_get_users_email()
{

    $get_settings = aben_get_options();
    $user_role = $get_settings['user_roles'];

    // Prepare arguments array to fetch users
    $args = array(
        'role' => $user_role,
        'meta_key' => 'aben_notification',
        'meta_value' => true,
    );

    // echo $user_role;

    $users = get_users($args);

    // var_dump($users);

    $email_addresses = array();

    foreach ($users as $user) {

        if (!in_array($user->user_email, $email_addresses)) {

            $email_addresses[] = $user->user_email;
        }

    }

    $email_addresses = array_unique($email_addresses); // Filtering out duplicates

    // print_r($email_addresses);

    // $to = implode(',', $email_addresses);

    return $email_addresses;
}

function aben_get_today_posts()
{
    $today = getdate(); // Get Today's Date
    // print_r($today);

    $year = $today['year'];
    // echo $year;
    $month = $today['mon'];
    // echo $month;
    $day = $today['mday'];
    // echo $day;

    $args = array(
        'numberposts' => -1,
        'date_query' => array(
            array(
                'year' => $year,
                'month' => $month,
                'day' => $day,
            ),
        ),

    );

    $posts = get_posts($args);
    // print_r($posts);

    $posts_published_today = count($posts);
    // echo $posts_published_today;

    $count = 0; // To control posts loop iteration

    $posts_to_email = array();

    if (!empty($posts_published_today)) {

        foreach ($posts as $post) {

            if ($count >= 10) {
                break; // Loop breaks after 10 posts
            }

            $id = $post->ID;

            $title = $post->post_title;
            // echo $title;

            $link = get_permalink($id);

            $posts_to_email[$count] = array(
                'title' => $title,
                'link' => $link,
            );

            // $author_id = intval($post->post_author);

            // $author_id = $post->post_author;

            // var_dump($author_id);

            // $author_id = get_the_author_meta('ID', $author_id);

            // $author = get_the_author_meta('email', $author_id);

            // var_dump($author);

            $count++;

        }
        return array(

            'posts_to_email' => $posts_to_email,
            'posts_published_today' => $posts_published_today,
        );

    }

    // var_dump($posts_to_email);

}
