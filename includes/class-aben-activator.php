<?php

/**
 * Fired during plugin activation
 *
 * @link       https://rehan.work
 * @since      1.2.0
 *
 * @package    Aben
 * @subpackage Aben/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.2.0
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
     * @since    1.2.0
     */
    public static function activate()
    {

        $default_settings             = aben_options_default();
        $default_settings['aben_key'] = aben_generate_encryption_key();
        add_option('aben_options', $default_settings);

    }

}
