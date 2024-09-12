<?php // Validation Callbacks

if (!defined('ABSPATH')) {
    exit;
}

function aben_callback_validate_options($input)
{
    // Retrieve existing options from the database
    $options = get_option('aben_options', array());

    // Merge the new input with the existing options
    $input = array_merge($options, $input);

    // Sanitize and validate each setting from the input
    if (isset($input['post_type'])) {
        $input['post_type'] = sanitize_text_field($input['post_type']);
    }

    if (isset($input['archive_page_slug'])) {
        $input['archive_page_slug'] = sanitize_text_field($input['archive_page_slug']);
    }

    if (isset($input['user_roles'])) {
        $input['user_roles'] = sanitize_text_field($input['user_roles']);
    }

    if (isset($input['email_frequency'])) {
        $input['email_frequency'] = sanitize_text_field($input['email_frequency']);
    }

    if (isset($input['email_subject'])) {
        $input['email_subject'] = sanitize_text_field($input['email_subject']);
    }

    if (isset($input['email_body'])) {
        $input['email_body'] = wp_kses_post($input['email_body']);
    }

    if (isset($input['smtp_host'])) {
        $input['smtp_host'] = sanitize_text_field($input['smtp_host']);
    }

    if (isset($input['smtp_port'])) {
        $input['smtp_port'] = intval($input['smtp_port']);
    }

    if (isset($input['smtp_encryption'])) {
        $input['smtp_encryption'] = sanitize_text_field($input['smtp_encryption']);
    }

    if (isset($input['smtp_username'])) {
        $input['smtp_username'] = sanitize_text_field($input['smtp_username']);
    }

    if (isset($input['smtp_password'])) {
        // Use WordPress's hashing function
        $input['smtp_password'] = sanitize_text_field($input['smtp_password']);
    }

    if (isset($input['from_name'])) {
        // Use WordPress's hashing function
        $input['from_name'] = sanitize_text_field($input['from_name']);
    }

    if (isset($input['from_email'])) {
        // Use WordPress's hashing function
        $input['from_email'] = sanitize_email($input['from_email']);
    }

    if (isset($input['number_of_posts'])) {
        $input['number_of_posts'] = intval($input['number_of_posts']);
    }

    if (isset($input['unsubscribe_link'])) {
        $input['unsubscribe_link'] = esc_url_raw($input['unsubscribe_link']);
    }

    $input['view_all_number'] = !empty($input['view_all_number']) ? 1 : 0;

    $input['show_unsubscribe'] = !empty($input['show_unsubscribe']) ? 1 : 0;

    return $input;
}
