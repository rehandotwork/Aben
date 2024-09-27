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
 * Plugin Name:       Auto Bulk Email Notifications (Aben)
 * Plugin URI:        https://rehan.work/aben
 * Description:       The simplest way to engage your subscribers or customers by scheduling and sending emails for your latest blogs, products, news etc. Just automate and send bulk emails directly from your website.
 * Version:           1.0.0
 * Author:            Rehan Khan
 * Author URI:        https://rehan.work/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       aben
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('ABEN_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-aben-activator.php
 */
function activate_aben()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-aben-activator.php';
    Aben_Activator::activate();

    aben_add_user_meta_to_existing_users(); //Refer add-user-meta.php
    aben_register_cron();

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-aben-deactivator.php
 */
function deactivate_aben()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-aben-deactivator.php';
    Aben_Deactivator::deactivate();

    aben_deregister_cron();

}

// Always include these files (both frontend and admin)
include_once dirname(__FILE__) . '/admin/partials/user/add-user-meta.php';
include_once dirname(__FILE__) . '/admin/partials/cron/cron-setup.php';
include_once dirname(__FILE__) . '/admin/partials/cron/register-cron.php';
include_once dirname(__FILE__) . '/admin/partials/email/send-email.php';
include_once dirname(__FILE__) . '/admin/partials/cron/update-cron.php';
include_once dirname(__FILE__) . '/admin/partials/email/email-build.php';
include_once dirname(__FILE__) . '/admin/partials/email/class-aben-email.php';

// Only include these files for admin
if (is_admin()) {
    include_once dirname(__FILE__) . '/admin/partials/menu/admin-menu.php';
    include_once dirname(__FILE__) . '/admin/partials/settings/settings-register.php';
    include_once dirname(__FILE__) . '/admin/partials/settings/settings-default.php';
    include_once dirname(__FILE__) . '/admin/partials/settings/settings-callbacks.php';
    include_once dirname(__FILE__) . '/admin/partials/settings/settings-validate.php';
    include_once dirname(__FILE__) . '/admin/partials/user/add-user-settings.php';
    include_once dirname(__FILE__) . '/admin/partials/smtp/smtp-setup.php';
}

register_activation_hook(__FILE__, 'activate_aben');

register_deactivation_hook(__FILE__, 'deactivate_aben');

function aben_show_plugin_settings_link($links, $file)
{
    if (plugin_basename(__FILE__) == $file) {
        $settings_link = '<a href="admin.php?page=aben">' . __('Settings') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}
add_filter('plugin_action_links', 'aben_show_plugin_settings_link', 10, 2);

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-aben.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_aben()
{

    $plugin = new Aben();
    $plugin->run();

}
run_aben();
