<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://rehan.work
 * @since      1.0.0
 *
 * @package    Aben
 * @subpackage Aben/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aben
 * @subpackage Aben/admin
 * @author     Rehan Khan <hello@rehan.work>
 */
class Aben_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aben_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aben_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/aben-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aben_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aben_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aben-admin.js', array( 'jquery' ), $this->version, false );

	}

}
function add_admin_menus()
	{
		add_menu_page(
			__('Aben', 'auto-bulk-email-notification'),
			__('Aben', 'auto-bulk-email-notification'),
			'manage_options',
			'auto-bulk-email-notification',
			array($this, 'settings_page'),
			'dashicons-email-alt',
			6
		);

		add_submenu_page(
			'auto-bulk-email-notification',
			__('Settings', 'auto-bulk-email-notification'),
			__('Settings', 'auto-bulk-email-notification'),
			'manage_options',
			'auto-bulk-email-notification-settings',
			array($this, 'settings_page')
		);


	}
