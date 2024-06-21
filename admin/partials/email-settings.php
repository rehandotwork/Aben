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

// Function tp get users with user meta "aben_notification" set to true
function aben_get_users_email()
{

    $get_settings = aben_get_options();
    $user_role = $get_settings['user_roles'];

    // echo $user_role;

    $users = get_users(array('role' => $user_role));

    // var_dump($users);

    $email_addresses = array();

    foreach ($users as $user) {

        $email_addresses[] = $user->user_email;

    }

    $to = implode(',', $email_addresses);

    return $to;
}
