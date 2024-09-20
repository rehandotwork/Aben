<?php // Build Email

if (!defined('ABSPATH')) {

    exit;

}

// add_action('admin_notices', 'aben_get_weekly_posts');

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
            'posts_published' => $posts_published_today,
        );

    }

}

function aben_get_weekly_posts($selected_day_num)
{
    // Ensure the selected day number is between 0 and 6
    if ($selected_day_num < 0 || $selected_day_num > 6) {
        return 'Invalid day number. Please provide a number between 0 (Sunday) and 6 (Saturday).';
    }

    // Get today's date
    $today = getdate();
    $current_day_of_week = $today['wday']; // 0 (for Sunday) through 6 (for Saturday)

    // Calculate the difference to get back to the last occurrence of the selected day
    $days_since_selected = ($current_day_of_week >= $selected_day_num) ?
    $current_day_of_week - $selected_day_num :
    7 - ($selected_day_num - $current_day_of_week);

    // Find the date of the last occurrence of the selected day (start of the week)
    $start_of_week = strtotime("-$days_since_selected days");

    // End of the week would be 6 days after the start of the week
    $end_of_week = strtotime("+6 days", $start_of_week);

    // Format the dates for the query
    $start_date = date('Y-m-d', $start_of_week); // Start date (e.g., last Saturday)
    $end_date = date('Y-m-d', $end_of_week); // End date (e.g., following Friday)

    // Query posts within the week range
    $args = array(
        'numberposts' => -1,
        'date_query' => array(
            array(
                'after' => $start_date,
                'before' => $end_date,
                'inclusive' => true, // Include start and end dates
            ),
        ),
    );

    $posts = get_posts($args);
    $posts_published_this_week = count($posts);

    $posts_to_email = array();

    if (!empty($posts_published_this_week)) {
        foreach ($posts as $post) {
            $id = $post->ID;
            $title = $post->post_title;
            $excerpt = $post->post_excerpt;
            $link = get_permalink($id);
            $author_id = $post->post_author;
            // $author = get_the_author_meta('display_name', $author_id);

            // if (taxonomy_exists('country')) {
            //     $location = get_the_terms($id, 'country'); // For 'country' taxonomy (if it exists)
            //     $country = $location ? $location[0]->name : '';
            // } else {
            //     $country = '';
            // }

            $posts_to_email[] = array(
                'title' => $title,
                'link' => $link,
                'excerpt' => $excerpt,
                // 'author' => $author,
                // 'country' => $country,
            );
        }

        return array(
            'posts_to_email' => $posts_to_email,
            'posts_published' => $posts_published_this_week,
        );
    }

    return null; // No posts found for the week
}
