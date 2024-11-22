<?php // Validation Callbacks

if (!defined('ABSPATH')) {
    exit;
}

function aben_callback_validate_options($input)
{
    // Retrieve existing options from the database
    $options = aben_get_options();

    // Loop through each key in the input and update values, including empty ones where appropriate
    foreach ($input as $key => $value) {
        // Handle validation based on field type
        switch ($key) {
            case 'body_bg':
            case 'header_bg':
                // Allow empty value to clear the color
                $options[$key] = empty($value) ? '' : sanitize_hex_color($value);
                break;

            case 'header_text':
            case 'header_subtext':
            case 'footer_text':
            case 'view_all_posts_text':
            case 'view_post_text':
            case 'post_type':
            case 'user_roles':
            case 'email_frequency':
            case 'email_subject':
            case 'smtp_host':
            case 'smtp_encryption':
            case 'smtp_username':
            case 'from_name':
            case 'day_of_week':
                // Allow empty value to clear text fields
                $options[$key] = sanitize_text_field($value);
                break;

            case 'email_time':
                if (!empty($value)) {
                    $time = sanitize_text_field($value); // e.g., "22:00"

                    // Get the current date and site timezone
                    $timezone = wp_timezone_string();
                    $date = new DateTime('now', new DateTimeZone($timezone)); // Current date in site timezone

                    // Set the time based on user input
                    list($hour, $minute) = explode(':', $time);
                    $date->setTime((int) $hour, (int) $minute); // Set the user-defined time

                    // Convert to UNIX timestamp and save
                    $timestamp = $date->getTimestamp();
                    $options[$key] = $timestamp;
                }
                break;

            case 'site_logo':
                // Allow empty value to remove the logo
                $options[$key] = sanitize_url($value);
                break;

            case 'archive_page_slug':
            case 'unsubscribe_link':
                // Allow empty value to remove the URL
                $options[$key] = esc_url_raw($value);
                break;

            case 'email_body':
                $options[$key] = wp_kses_post($value);
                break;

            case 'smtp_port':
            case 'number_of_posts':
                $options[$key] = intval($value);
                break;

            case 'smtp_password':
                if (!empty($value)) {
                    $options[$key] = aben_encrypt_password($value);
                } else {
                    // If no password was provided, keep the existing one
                    $options[$key] = isset($options['smtp_password']) ? $options['smtp_password'] : '';
                }
                break;

            case 'from_email':
                $options[$key] = sanitize_email($value);
                break;

            case 'show_view_all':
            case 'show_unsubscribe':
            case 'use_smtp':
            case 'show_number_view_all':
            case 'show_view_post':
                $options[$key] = !empty($value) ? 1 : 0;
                break;
        }
    }
    return $options;
}

function aben_generate_encryption_key($length = 32)
{
    return bin2hex(random_bytes($length)); // Generates a random key
}

function aben_encrypt_password($password)
{
    $encryption_key = aben_get_options()['aben_key'];

    if (!$encryption_key) {

        error_log('Encryption key not found. Please set ABEN_ENCRYPTION_KEY constant in wp-config file.');

    } else {
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $iv = openssl_random_pseudo_bytes($iv_length);

        // Encrypt the password using AES-256-CBC
        $encrypted_password = openssl_encrypt($password, 'aes-256-cbc', $encryption_key, 0, $iv);

        // Combine the encrypted password with the IV for storage
        return base64_encode($iv . $encrypted_password);
    }
}

function aben_decrypt_password($encrypted_password)
{
    $encryption_key = aben_get_options()['aben_key'];

    if (!$encryption_key) {

        error_log('Encryption key not found. Please set ABEN_ENCRYPTION_KEY constant in wp-config file.');

    } else {
        $iv_length = openssl_cipher_iv_length('aes-256-cbc');
        $decoded = base64_decode($encrypted_password);

        // Extract the IV and the encrypted password from the decoded string
        $iv = substr($decoded, 0, $iv_length);
        $encrypted_password = substr($decoded, $iv_length);

        // Ensure IV is exactly 16 bytes (aes-256-cbc expects 16 bytes for IV)
        if (strlen($iv) < $iv_length) {
            $iv = str_pad($iv, $iv_length, "\0"); // Padding with null bytes
        }

        // Decrypt the password using the same key and IV
        return openssl_decrypt($encrypted_password, 'aes-256-cbc', $encryption_key, 0, $iv);
    }
}
