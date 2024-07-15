<?php // Add User Meta

if (!defined('ABSPATH')) {

    exit;

}

//Fetch Users

function aben_get_users()
{
    // $options = get_option('aben_options', 'aben_options_default');

    // $selected_role = $options['user_roles'];

    // echo $selected_role;

    $users = get_users(); // All Users

    return $users;

}

// Add User Meta on Registration

add_action('user_register', 'aben_add_user_meta', 10, 1);

function aben_add_user_meta($user_id)
{
    add_user_meta($user_id, 'aben_notification', '1');
}

// Adds User Meta to Existing Users
function aben_add_user_meta_to_existing_users()
{
    $users = aben_get_users();

    foreach ($users as $user) {

        $user_id = $user->ID;
        // echo $user_id;

        if (!metadata_exists('user', $user_id, 'aben_notification')) {

            add_user_meta($user_id, 'aben_notification', '1');

            // error_log('User Meta Added');

        }
        // else {
        //     error_log('User meta already present');
        // }

    }

}
