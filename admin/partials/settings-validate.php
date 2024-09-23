<?php // Validation Callbacks

if (!defined('ABSPATH')) {
    exit;
}

function aben_callback_validate_options($input)
{
    // Retrieve existing options from the database
    $options = aben_get_options();

    // Merge the new input with the existing options
    $input = array_merge($options, $input);

    // Sanitize and validate each setting from the input
    if (isset($input['body_bg'])) {
        $input['body_bg'] = sanitize_hex_color($input['body_bg']);
    }

    if (isset($input['header_text'])) {
        $input['header_text'] = sanitize_text_field($input['header_text']);
    }

    if (isset($input['header_bg'])) {
        $input['header_bg'] = sanitize_hex_color($input['header_bg']);
    }

    if (isset($input['header_subtext'])) {
        $input['header_subtext'] = sanitize_text_field($input['header_subtext']);
    }

    if (isset($input['footer_text'])) {
        $input['footer_text'] = sanitize_text_field($input['footer_text']);
    }

    if (isset($input['site_logo'])) {
        $input['site_logo'] = sanitize_text_field($input['site_logo']);
    }

    if (isset($input['view_all_posts_text'])) {
        $input['view_all_posts_text'] = sanitize_text_field($input['view_all_posts_text']);
    }

    if (isset($input['view_post_text'])) {
        $input['view_post_text'] = sanitize_text_field($input['view_post_text']);
    }

    if (isset($input['post_type'])) {
        $input['post_type'] = sanitize_text_field($input['post_type']);
    }

    if (isset($input['archive_page_slug'])) {
        $input['archive_page_slug'] = esc_url_raw($input['archive_page_slug']);
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
        $input['smtp_password'] = sanitize_text_field($input['smtp_password']);
    }

    if (isset($input['from_name'])) {
        $input['from_name'] = sanitize_text_field($input['from_name']);
    }

    if (isset($input['from_email'])) {
        $input['from_email'] = sanitize_email($input['from_email']);
    }

    if (isset($input['number_of_posts'])) {
        $input['number_of_posts'] = intval($input['number_of_posts']);
    }

    if (isset($input['unsubscribe_link'])) {
        $input['unsubscribe_link'] = esc_url_raw($input['unsubscribe_link']);
    }

    if (isset($input['day_of_week'])) {
        $input['day_of_week'] = sanitize_text_field($input['day_of_week']);
    }

    // Handle checkboxes (binary values)
    $input['show_view_all'] = !empty($input['show_view_all']) ? 1 : 0;
    $input['show_unsubscribe'] = !empty($input['show_unsubscribe']) ? 1 : 0;
    $input['use_smtp'] = !empty($input['use_smtp']) ? 1 : 0;
    $input['show_number_view_all'] = !empty($input['show_number_view_all']) ? 1 : 0;
    $input['show_view_post'] = !empty($input['show_view_post']) ? 1 : 0;

    return $input;
}

function aben_encrypt_password($password)
{

    $encryption_key = getenv('ABEN_ENCRYPTION_KEY'); // Replace with a secure method of obtaining your key
    $iv_length = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($iv_length);
    $encrypted_password = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted_password . '::' . $iv); // Store IV with the encrypted password

}

function aben_generate_encryption_key($length = 32)
{
    return bin2hex(random_bytes($length)); // Generates a random key
}

function aben_decrypt_password($encrypted_password)
{
    $encryption_key = getenv('ABEN_ENCRYPTION_KEY'); // Securely retrieve the encryption key
    list($encrypted_data, $iv) = explode('::', base64_decode($encrypted_password), 2); // Split data and IV
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv); // Decrypt and return password
}

// add_action('admin_notices', 'aben_check');

// function aben_check()
// {
//     $hashed_password = aben_encrypt_password('$377%$sM583*w#5%$jx%Bo67^&m2');
//     echo "Hashed password - {$hashed_password} <br/>";
//     $decrypted_password = aben_decrypt_password($hashed_password);
//     echo "Real Password - {$decrypted_password}";

// }
