<?php // Build Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('admin_notices', 'aben_get_today_posts');

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

    $posts_to_email = array();

    if (!empty($posts_published_today)) {

        foreach ($posts as $post) {

            $id = $post->ID;

            $title = $post->post_title;

            $excerpt = $post->post_excerpt;

            $link = get_permalink($id);

            $author_id = $post->post_author;

            $author = get_the_author_meta('display_name', $author_id);

            if (taxonomy_exists('country')) {

                $location = get_the_terms($id, 'country'); // This data and dependant data is only for Gulfworking, Not for general purpose. Hard coded term fetching 'country'
                // print_r($location);
                $country = $location[0]->name;

            } else {
                $country = '';
            }

            $posts_to_email[] = array(
                'title' => $title,
                'link' => $link,
                'excerpt' => $excerpt,
                'author' => $author,
                'country' => $country,
            );

            // $count++;

        }
        return array(

            'posts_to_email' => $posts_to_email,
            'posts_published_today' => $posts_published_today,
        );

    }

}
