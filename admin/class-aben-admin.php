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
class Aben_Admin
{

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
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_menu', array($this, 'add_admin_menus'));
		add_action('admin_init', array($this, 'register_settings'));



	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/aben-admin.css', array(), $this->version, 'all');

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/aben-admin.js', array('jquery'), $this->version, false);

	}


	public function add_admin_menus()
	{
		add_menu_page(
			__('ABEN', 'auto-bulk-email-notification'),
			__('ABEN', 'auto-bulk-email-notification'),
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
	public function register_settings()
	{
		register_setting('aben_settings', 'aben_option', array($this, 'sanitize_settings'));
	}

	public function settings_page()
	{
		$options = get_option('aben_option');
		?>
		<div class="wrap">
			<h1><?php esc_html_e('Auto Bulk Email Notification', 'auto-bulk-email-notification'); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields('aben_settings'); ?>
				<?php do_settings_sections('settings_page'); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Select Post Type', 'auto-bulk-email-notification'); ?></th>
						<td>
							<select name="aben_option[post_type]">
								<?php
								$post_types = get_post_types(array('public' => true), 'objects');
								foreach ($post_types as $post_type) {
									$selected = (isset($options['post_type']) && $options['post_type'] === $post_type->name) ? 'selected' : '';
									echo '<option value="' . esc_attr($post_type->name) . '" ' . $selected . '>' . esc_html($post_type->label) . '</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Select Users (Roles)', 'auto-bulk-email-notification'); ?></th>
						<td>
							<select name="aben_option[roles]">
								<?php
								global $wp_roles;
								$roles = $wp_roles->roles;
								foreach ($roles as $role => $details) {
									$selected = (isset($options['roles']) && $options['roles'] === $role) ? 'selected' : '';
									echo '<option value="' . esc_attr($role) . '" ' . $selected . '>' . esc_html($details['name']) . '</option>';
								}
								?>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Email Frequency', 'auto-bulk-email-notification'); ?></th>
						<td>
							<select name="aben_option[email_frequency]">
								<option value="once_day" <?php selected($options['email_frequency'], 'once_day'); ?>>
									<?php esc_html_e('Once in a Day', 'auto-bulk-email-notification'); ?>
								</option>
								<option value="twice_day" <?php selected($options['email_frequency'], 'twice_day'); ?>>
									<?php esc_html_e('Twice in a Day', 'auto-bulk-email-notification'); ?>
								</option>
								<option value="after_2_hours" <?php selected($options['email_frequency'], 'after_2_hours'); ?>>
									<?php esc_html_e('After 2 Hours', 'auto-bulk-email-notification'); ?></option>
								<option value="after_1_hour" <?php selected($options['email_frequency'], 'after_1_hour'); ?>>
									<?php esc_html_e('After 1 Hour', 'auto-bulk-email-notification'); ?>
								</option>
								<option value="post_publish" <?php selected($options['email_frequency'], 'post_publish'); ?>>
									<?php esc_html_e('On Post Publish', 'auto-bulk-email-notification'); ?>
								</option>
							</select>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><?php esc_html_e('Show Email Template', 'auto-bulk-email-notification'); ?></th>
						<td>
							<?php

							$email_template = $options['email_template'];
							wp_editor(isset($email_template) ? $email_template : '', 'aben_option[email_template]'); ?>
						</td>
					</tr>

				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}



	public function sanitize_settings($input)
	{
		$sanitized_input = array();

		// Sanitize each input field
		if (isset($input['post_type'])) {
			$sanitized_input['post_type'] = sanitize_text_field($input['post_type']);
		}

		if (isset($input['roles'])) {
			$sanitized_input['roles'] = sanitize_text_field($input['roles']);
		}

		if (isset($input['email_frequency'])) {
			$sanitized_input['email_frequency'] = sanitize_text_field($input['email_frequency']);
		}

		if (isset($input['email_template'])) {
			$sanitized_input['email_template'] = wp_kses_post($input['email_template']);
		}

		return $sanitized_input;
	}
}
