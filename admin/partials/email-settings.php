<?php // Build Email

if (!defined('ABSPATH')) {

    exit;

}

function aben_get_options()
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
    if (!in_array($user->user_email, $email_addresses)) {
        foreach ($users as $user) {

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
        // 'fields' => 'ids',
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

    foreach ($posts as $post) {

        $id = $post->ID;

        $title = $post->post_title;

        $link = get_permalink($id);

        $author_id = $post->post_author;

        // $author_id = get_the_author_meta('ID', $author_id);

        // $p = get_the_author_meta('nicename', $author_id);

        // echo $p;

    }

}

aben_get_today_posts();
