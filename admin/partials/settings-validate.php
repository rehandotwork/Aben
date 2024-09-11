<?php // Validation Callbacks

if (!defined('ABSPATH')) {
    exit;
}

function aben_callback_validate_options($input)
{
    // Retrieve existing options from the database
    $options = get_option('aben_options', array());

    // Sanitize and validate each setting from the input
    if (isset($input['post_type'])) {
        $options['post_type'] = sanitize_text_field($input['post_type']);
    }

    if (isset($input['archive_page_slug'])) {
        $options['archive_page_slug'] = sanitize_text_field($input['archive_page_slug']);
    }

    if (isset($input['user_roles'])) {
        $options['user_roles'] = sanitize_text_field($input['user_roles']);
    }

    if (isset($input['email_frequency'])) {
        $options['email_frequency'] = sanitize_text_field($input['email_frequency']);
    }

    if (isset($input['email_subject'])) {
        $options['email_subject'] = sanitize_text_field($input['email_subject']);
    }

    if (isset($input['email_body'])) {
        $options['email_body'] = wp_kses_post($input['email_body']);
    }

    if (isset($input['smtp_host'])) {
        $options['smtp_host'] = sanitize_text_field($input['smtp_host']);
    }

    if (isset($input['smtp_port'])) {
        $options['smtp_port'] = intval($input['smtp_port']);
    }

    if (isset($input['number_of_posts'])) {
        $options['number_of_posts'] = intval($input['number_of_posts']);
    }

    if (isset($input['unsubscribe_link'])) {
        $options['unsubscribe_link'] = esc_url_raw($input['unsubscribe_link']);
    }

    $options['view_all_number'] = isset($input['view_all_number']) ? 1 : 0;

    $options['show_unsubscribe'] = isset($input['show_unsubscribe']) ? 1 : 0;

    // Return the updated options array
    return $options;
}
