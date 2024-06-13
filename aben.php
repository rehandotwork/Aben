<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rehan.work
 * @since             1.0.0
 * @package           Aben
 *
 * @wordpress-plugin
 * Plugin Name:       Auto Bulk Email Notifications
 * Plugin URI:        https://rehan.work/aben
 * Description:       This plugin allows WordPress admins to automate bulk email notifications to their users directly form the website. It can be set for any type of post created on the website like blogs, products, News, Jobs etc. Use it to love it.
 * Version:           1.0.0
 * Author:            Rehan Khan
 * Author URI:        https://rehan.work/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aben
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'ABEN_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aben-activator.php
 */
function activate_aben() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aben-activator.php';
	Aben_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aben-deactivator.php
 */
function deactivate_aben() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-aben-deactivator.php';
	Aben_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_aben' );
register_deactivation_hook( __FILE__, 'deactivate_aben' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-aben.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aben() {

	$plugin = new Aben();
	$plugin->run();

}
run_aben();

function aben_get_users()
{
    $users = get_users(array(
        'role' => 'subscriber',
    ));

    var_dump($users);
}

function aben_add_user_meta()
{

    add_user_meta($user_id, 'aben_notification', true, boolean);

}