<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rehan.work
 * @since      1.0.0
 *
 * @package    Aben
 * @subpackage Aben/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Aben
 * @subpackage Aben/includes
 * @author     Rehan Khan <hello@rehan.work>
 */
class Aben_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */
    public static function activate()
    {

        $default_settings = aben_options_default();

        add_option('aben_options', $default_settings);

        $encryption_key = aben_generate_encryption_key();

        // Path to wp-config.php
        $config_file = ABSPATH . 'wp-config.php';

        // Check if the key is already defined
        if (!defined('ABEN_ENCRYPTION_KEY')) {
            // Prepare the line to add
            $key_line = "define('ABEN_ENCRYPTION_KEY', '{$encryption_key}');\n";

            // Use file_put_contents to add the line to wp-config.php
            file_put_contents($config_file, $key_line, FILE_APPEND | LOCK_EX);
        }

        if (!defined('ALTERNATE_WP_CRON')) {
            // Prepare the line to add
            $key_line = "define('ALTERNATE_WP_CRON', true);";

            // Use file_put_contents to add the line to wp-config.php
            file_put_contents($config_file, $key_line, FILE_APPEND | LOCK_EX);
        }

    }

}
