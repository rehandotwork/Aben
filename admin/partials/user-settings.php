<?php // User Profile Page Settings

if (!defined('ABSPATH')) {

    exit;

}

add_action('edit_user_profile', 'aben_show_user_meta');
add_action('show_user_profile', 'aben_show_user_meta');
add_action('edit_user_profile_update', 'aben_update_user_meta');
add_action('personal_options_update', 'aben_update_user_meta');
