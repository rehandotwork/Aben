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
        $options['smtp_port'] = sanitize_text_field($input['smtp_port']);
    }

    // Return the updated options array
    return $options;
}
