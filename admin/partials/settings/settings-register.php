<?php // ABEN Settings Page

if (!defined('ABSPATH')) {

    exit;

}

add_action('admin_init', 'aben_register_settings');

function aben_display_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $tabs = array(
        'general' => 'General',
        'email' => 'Email Template',
        'smtp' => 'SMTP',
        'email_logs' => 'Email Logs',
        'unsubscribe' => 'Unsubscribe',
        'license' => 'License',
    );

    $current_tab = 'general';

    if (isset($_GET['tab'])) {
        // Unslash the input and sanitize it
        $tab = sanitize_text_field(wp_unslash($_GET['tab']));
    
        // Check if the tab exists in the $tabs array
        if (isset($tabs[$tab])) {
            $current_tab = $tab;
        }
    }

    ?>

<div id="aben-app">
    <?php if (isset($_GET['settings-updated'])) {
        echo '<div id="aben-notice" class="notice notice-success is-dismissible"><p>Saved.</p></div>';
    }
    ?>
    <div id="aben-header">

        <div id="aben-logo"><img src="<?php echo esc_url(PLUGIN_LOGO);?>" alt=""></div>

        <nav class="nav-tab-wrapper" id="aben-nav">
            <div id="aben-nav-menu">
                <?php foreach ($tabs as $tab => $name): ?>
                <a href="?page=aben&tab=<?php echo esc_html($tab); ?>"
                    class="nav-tab <?php echo $current_tab === $tab ? 'nav-tab-active' : ''; ?>">
                    <?php echo esc_html($name); ?>
                </a>
                <?php endforeach;?>
            </div>
        </nav>
    </div>

    <div id="aben-body">

        <form action="options.php" method="post">
            <?php
settings_fields('aben_options');

    // Display only the relevant settings based on the active tab
    if ($current_tab === 'general') {
        echo '<div class = "aben-app__subheading"> 
        <p>General Settings</p>
        </div>';
        echo '<div id ="aben-general-settings">';
        do_settings_sections('aben_section_general_setting');
        echo '</div>';
    } elseif ($current_tab === 'smtp') {
        echo '<div class = "aben-app__subheading"> 
        <p>Configure SMTP Settings</p>
        </div>';
        echo '<div id ="aben-smtp-settings">';
        do_settings_sections('aben_section_smtp_setting');
        echo '</div>';
    } elseif ($current_tab === 'license') {
        if((isset($_GET['license_status']))) {
            if ($_GET['license_status'] === 'success') {
                echo '<div id="aben-notice--success" class="notice notice-success is-dismissible">
        <p>License activation successfull.</p>
    </div>';
            } elseif($_GET['license_status'] === 'error') {
                echo '<div id="aben-notice--error" class="notice notice-error is-dismissible">
        <p>There was an error validating the license key. Please try again.</p>
    </div>';
            } elseif($_GET['license_status'] === 'nonce_error') {
                echo '<div id="aben-notice--error" class="notice notice-error is-dismissible">
        <p>Security check failed. Please try again.</p>
    </div>';
            }
        }   
        echo '<div class = "aben-app__subheading"> 
        <p>License Activation</p>
        </div>'; if(!Aben_Email::is_pro()){ ?>
            <div id="aben-license-settings">
                <div class="form-wrapper">
                    <form method="POST" action="">
                        <input type="hidden" name="aben_license_action" value="validate_license" />
                        <label for="aben-activate-license">Enter License Key</label>
                        <input type="text" id="aben-activate-license" name="aben_license_key" required />
                        <?php wp_nonce_field('aben_validate_license', 'aben_license_nonce'); ?>
                        <input type="submit" class="button button-primary" value="Activate License" />
                    </form>
                </div>
                <div class="get-license">
                    <p>Don't have the license key, get one now for only $5 <a href="https://rehan.work/aben" target="
                    _blank">Click here to buy</a></p>
                </div>
            </div>
            <?php } else { ?>
            <div id="aben-license-settings">
                <div class="aben-pro">
                    <p class="aben-pro-active-message">License Status : Active</p>
                </div>
                <?php 
                do_settings_sections( 'aben_section_license_setting' );
                submit_button()?>
            </div>
            <?php }
    } elseif ($current_tab === 'email') {
        echo '<div class = "aben-app__subheading"> 
        <p>Template Settings </p>
        <p>Email Preview</p>
        </div>';
        echo '<div id = "aben-email-tab-grid" style="display:grid; grid-template-columns:4fr 6fr; grid-gap:1rem;">';
        do_settings_sections('aben_section_email_setting');

        $site_logo = isset(aben_get_options()['site_logo']) ? aben_get_options()['site_logo'] : '';
        $show_view_post = aben_get_options()['show_view_post'];
        $featured_image = FEATURED_IMAGE;

        $aben_email_dashboard = new Aben_Email(
            'https://rehan.work/blog/', //archive_page_slug
            10, //number_of_posts
            '#f0eeff', //body_bg
            'Hi Rehan', //header_text
            '#f0eeff', //header_bg
            'Check out our daily posts and send your feedback.', //header_subtext
            'Copyright 2024 | Aben Inc.', //footer_text
            $site_logo, //site_logo
            true, //show_view_all
            'View All Posts', //view_all_posts_text
            $show_view_post, //show_view_post
            'Read', //view_post_text
            true, //show_unsubscribe
            aben_get_test_posts(),
        );
        $aben_email_dashboard->aben_email_template();
        echo '</div>';
    } else if ($current_tab === 'email_logs') {
        echo '<div class = "aben-app__subheading"> 
        <p>Email Logs</p>
        <p style="justify-self:end;">Logs older than 30 days will be automatically deleted.</p>
        </div>';
        $logger = new Aben_Email_Logs();
        $per_page = 150; 
        $current_page = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
        $offset = ($current_page - 1) * $per_page; 

        // Fetch logs with limit and offset
        $logs = $logger->get_logs($per_page, $offset);
        $total_logs = $logger->get_logs_count();

        if (!empty($logs)) {
            echo '<table class="widefat fixed aben-email-logs">';
            echo '<thead><tr>';
            echo '<th width = 5%>#</th><th width = 30%>Subject</th><th width = 30%>To</th><th width = 10%>Status</th><th width = 20%>Date/Time</th>';
            echo '</tr></thead><tbody>';

            $count = $offset + 1; // Start count based on the offset
            foreach ($logs as $log) {
                $date = new DateTime($log->sent_at);
                $sent_at = $date->format('j F Y / H:i A');

                echo '<tr>';
                echo '<td>' . esc_html($count++) . '</td>';
                echo '<td>' . esc_html($log->subject) . '</td>';
                echo '<td>' . esc_html($log->email_to) . '</td>';
                echo '<td>' . esc_html($log->status) . '</td>';
                echo '<td>' . esc_html($sent_at) . '</td>';
                echo '</tr>';
            }

            echo '</tbody></table>';

            // Add pagination
            $pagination_args = [
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'current' => max(1, $current_page),
                'total' => ceil($total_logs / $per_page),
                'prev_text' => '&laquo; Previous',
                'next_text' => 'Next &raquo;',
            ];

            echo '<div class="pagination-wrap">';
            echo wp_kses_post(paginate_links($pagination_args));
            echo '</div>';
        } else {
            echo '<table class="widefat fixed aben-email-logs">';
            echo '<thead><tr>';
            echo '<th width = 5%>#</th><th width = 30%>Subject</th><th width = 30%>To</th><th width = 10%>Status</th><th width = 20%>Date/Time</th>';
            echo '</tr></thead><tbody>';
            echo '<tr><td colspan="5">No email logs found.</td></tr>';
            echo '</tbody></table>';
        }
    }
    if ($current_tab !== 'email_logs' && $current_tab !== 'unsubscribe' && $current_tab !== 'license') {
        submit_button();
    }
    ?>
        </form>
    </div>
    <?php if ($current_tab == 'email'):
        aben_send_test_email();
    endif;?>

    <?php if ($current_tab == 'license'): ?>

    <?php endif; ?>

    <?php if ($current_tab === 'email_logs'):

    endif;?>

    <?php if ($current_tab === 'unsubscribe'): ?>

    <div id="aben-unsubscribe-tab">
        <div class="aben-app__subheading">
            <p>Unsubscribed Users</p>
        </div>
        <div class="unsubscribe-header">
            <!-- Add to Unsubscribed Form -->
            <form method="post" action="">
                <input type="email" name="aben_unsubscribe_email" placeholder="Enter email address" required>
                <input type="submit" name="aben_add_unsubscribed" class="button action" value="Add to Unsubscribed">
            </form>
        </div>
        <table class="widefat fixed" cellspacing="0">
            <thead>
                <tr>
                    <th class="manage-column column-columnname" width="20px;" scope="col">#</th>
                    <th class="manage-column column-columnname" scope="col">Email</th>
                    <th class="manage-column column-columnname" scope="col">Name</th>
                    <th class="manage-column column-columnname" scope="col">Role</th>
                    <th class="manage-column column-columnname" scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
// Query to fetch all users with 'aben_notification' meta set to '0'
    $args = array(
        'meta_key' => 'aben_notification',
        'meta_value' => '0',
        'meta_compare' => '=',
    );

    // Get the users based on the query
    $unsubscribed_users = get_users($args);

    $serial_number = 1;

    if (!empty($unsubscribed_users)) {
        // Loop through each unsubscribed user
        foreach ($unsubscribed_users as $user) {
            // Get user roles (WordPress users can have multiple roles)
            $roles = $user->roles;
            $role_display = implode(', ', $roles); // Display roles as comma-separated

            // Generate the URL for subscribing the user again
            $subscribe_url = add_query_arg(array(
                'action' => 'aben_subscribe_user',
                'user_id' => $user->ID,
            ), admin_url('admin.php'));

            ?>
                <tr>
                    <td><?php echo esc_html($serial_number); ?></td>
                    <td><?php echo esc_html($user->user_email); ?></td>
                    <td><?php echo esc_html(ucwords($user->display_name)); ?></td>
                    <td><?php echo esc_html(ucwords($role_display)); ?></td>
                    <td>
                        <form method="post" action="<?php echo esc_url($subscribe_url); ?>">
                            <input type="hidden" name="user_id" value="<?php echo esc_attr($user->ID); ?>">
                            <input type="submit" class="button action" value="Subscribe Again">
                        </form>
                    </td>
                </tr>
                <?php
$serial_number++;
        }
    } else {
        ?>
                <tr>
                    <td colspan="4">No unsubscribed users found.</td>
                </tr>
                <?php
}
    ?>
            </tbody>
        </table>
    </div>
    <?php endif;?>
</div>
<?php 
}
function aben_handle_license_submission() {
    if (isset($_POST['aben_license_action']) && $_POST['aben_license_action'] === 'validate_license') {
        if (!isset($_POST['aben_license_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['aben_license_nonce'])), 'aben_validate_license')) {
            wp_redirect(add_query_arg('license_status', 'nonce_error', wp_get_referer()));
            exit;
        }

        if (!isset($_POST['aben_license_key'])) {
            wp_redirect(add_query_arg('license_status', 'missing_license_key', wp_get_referer()));
            exit;
        }

        $license_key = sanitize_text_field(wp_unslash($_POST['aben_license_key']));

        $result = aben_send_license_validation_request($license_key);

        if (is_wp_error($result)) {
            wp_redirect(add_query_arg('license_status', 'error', wp_get_referer()));
            exit;
        }

        if ($result === true) {
            wp_redirect(add_query_arg('license_status', 'success', wp_get_referer()));
        } else {
            wp_redirect(add_query_arg('license_status', 'error', wp_get_referer()));
        }

        exit; // Make sure the script ends after the redirect
    }
}
add_action('init', 'aben_handle_license_submission');

function aben_send_license_validation_request($license_key) {
    $url = 'https://rehan.work/aben/wp-json/custom/v1/license';
    $body = wp_json_encode(array('license_key' => $license_key));
    $args = array(
        'method'    => 'POST',
        'body'      => $body,
        'headers'   => array(
            'Content-Type' => 'application/json',
        ),
    );
    $response = wp_remote_post($url, $args);
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        echo esc_html("Error: $error_message");
        return false;
    }
    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body);
    if ($data === true) {
        $options = get_option('aben_options');
    // If the options are serialized, unserialize them first
    if (is_string($options)) {
        $options = maybe_unserialize($options);
    }
    if (!is_array($options)) {
        // If $options is not an array, initialize it as an empty array
        $options = [];
    }
    $options['pro'] = true;
    // Re-serialize and update the option in the database
    update_option('aben_options', $options);
        return true; 
    } else {
        return false;
    }
}

function aben_verify_license_key($license_key) {
    $api_url = 'https://rehan.work/aben/wp-json/custom/v1/license';
    $response = wp_remote_get($api_url);
    if (is_wp_error($response)) {
        return new WP_Error('api_request_failed', 'Error connecting to the license server.');
    }
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!isset($data['aben_license_keys'])) {
        return new WP_Error('invalid_api_response', 'Invalid response from license server.');
    }
    $valid_license_keys = $data['aben_license_keys'];
    if (in_array($license_key, $valid_license_keys)) {
        $options = get_option('aben_options');
    // If the options are serialized, unserialize them first
    if (is_string($options)) {
        $options = maybe_unserialize($options);
    }
    // Ensure $options is an array
    if (!is_array($options)) {
        // If $options is not an array, initialize it as an empty array
        $options = [];
    }
    // Update the 'pro' key to true
    $options['pro'] = true;
    // Re-serialize and update the option in the database
    update_option('aben_options', $options);
        return true; // License key is valid
    } else {
        return new WP_Error('invalid_license_key', 'The provided license key is invalid.');
    }
}

//Hide Other Plugin Admin Notices
add_action('admin_head', 'aben_hide_other_plugin_notices');
function aben_hide_other_plugin_notices() {
    $screen = get_current_screen(  );
    if ( $screen->id == 'toplevel_page_aben' ) {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
}
}

//ABEN Register Settings
function aben_register_settings()
{

    register_setting(
        'aben_options',
        'aben_options',
        'aben_callback_validate_options'
    );

    // General Tab
    add_settings_section(
        'aben_section_general_setting',
        '',
        'aben_callback_section_general_setting',
        'aben_section_general_setting'
    );

    add_settings_field(
        'email_subject',
        'Email Subject',
        'aben_callback_field_text',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'email_subject', 'label' => '']
    );

    add_settings_field(
        'user_roles',
        'Send To',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'user_roles', 'label' => '']
    );

    add_settings_field(
        'post_type',
        'Post Type',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'post_type', 'label' => '']
    );

    add_settings_field(
        'email_frequency',
        'Email Delivery',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'email_frequency', 'label' => '']
    );

    add_settings_field(
        'day_of_week',
        'Schedule Day',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'day_of_week', 'label' => '']
    );

    add_settings_field(
        'email_time',
        'Schedule Time',
        'aben_callback_field_time',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'email_time', 'label' => '']
    );

    // SMTP Tab
    add_settings_section(
        'aben_section_smtp_setting',
        '',
        'aben_callback_section_smtp_setting',
        'aben_section_smtp_setting'
    );

    add_settings_field(
        'use_smtp',
        'Use Your SMTP',
        'aben_callback_field_checkbox',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'use_smtp', 'label' => 'Yes']
    );

    add_settings_field(
        'smtp_host',
        'SMTP Host',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_host', 'label' => 'e.g., smtp.server.com']
    );

    add_settings_field(
        'smtp_port',
        'SMTP Port',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_port', 'label' => 'e.g., 465 for SSL / 587 for TLS']
    );

    add_settings_field(
        'smtp_encryption',
        'Encryption Type',
        'aben_callback_field_select',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_encryption', 'label' => 'Select encryption type']
    );

    add_settings_field(
        'smtp_username',
        'Username',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_username', 'label' => 'e.g., user@website.com']
    );

    add_settings_field(
        'smtp_password',
        'Password',
        'aben_callback_field_password',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_password', 'label' => '']
    );

    add_settings_field(
        'from_name',
        'From',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'from_name', 'label' => '']
    );

    add_settings_field(
        'from_email',
        'Reply to Email',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'from_email', 'label' => 'e.g., email@website.com']
    );

    // Email Template Tab
    add_settings_section(
        'aben_section_email_setting',
        '',
        'aben_callback_section_email_setting',
        'aben_section_email_setting'
    );

    add_settings_field(
        'header_text',
        'Header Text',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_text', 'label' => 'Use {{USERNAME}} to display user\'s name']
    );

    add_settings_field(
        'header_subtext',
        'Header Subtext',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_subtext', 'label' => '']
    );

    add_settings_field(
        'body_bg',
        'Body Background Color',
        'aben_callback_field_color',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'body_bg', 'label' => '']
    );

    add_settings_field(
        'header_bg',
        'Post Tile Background Color',
        'aben_callback_field_color',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_bg', 'label' => '']
    );

    add_settings_field(
        'number_of_posts',
        'Number of Posts',
        'aben_callback_field_select',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'number_of_posts', 'label' => 'Posts are showing for demonstration only']
    );

    add_settings_field(
        'show_view_post',
        'Show Featured Image',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_view_post', 'label' => 'Yes']
    );

    add_settings_field(
        'show_view_all',
        'Show Button',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_view_all', 'label' => 'Yes']
    );

    add_settings_field(
        'view_all_posts_text',
        'Button Text',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'view_all_posts_text', 'label' => '']
    );

    add_settings_field(
        'archive_page_slug',
        'Button Link',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'archive_page_slug', 'label' => 'e.g., https://website.com/blogs']
    );

    add_settings_field(
        'site_logo',
        'Footer Logo',
        'aben_callback_field_media',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'site_logo', 'label' => '']
    );

    add_settings_field(
        'footer_text',
        'Footer Text',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'footer_text', 'label' => '']
    );

    add_settings_field(
        'show_unsubscribe',
        'Show Unsubscribe',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_unsubscribe', 'label' => 'Yes']
    );
    if(!Aben_Email::is_pro()) {
    add_settings_field(
        'remove_branding',
        '<a id ="aben_remove_branding" href="/wp-admin/admin.php?page=aben&tab=license">Remove Branding "Powered by Aben"</a>',
        'aben_callback_remove_branding',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'remove_branding', 'label' => '']  
    );
    }
    add_settings_section(
        'aben_section_email_template',
        '',
        'aben_callback_section_email_template',
        'aben_section_email_template'
    );

    add_settings_field(
        'email_body',
        '',
        'aben_callback_field_textarea',
        'aben_section_email_template',
        'aben_section_email_template',
        ['id' => 'email_body', 'label' => 'Email body (Text/Markup)']
    );

    //License Tab
    add_settings_section( 
        'aben_section_license_setting',
        '',
        '__return_true',
        'aben_section_license_setting'
    );

    add_settings_field( 
        'revoke_license',
        '', 
        'aben_callback_field_checkbox', 
        'aben_section_license_setting', 
        'aben_section_license_setting',
        ['id' => 'revoke_license', 'label' => 'Revoke License'] );
}

// Hook to sanitize_option to add timezone automatically
add_filter('pre_update_option_aben_options', 'aben_save_timezone_option', 10, 2);

function aben_save_timezone_option($new_value, $old_value)
{
    // Automatically get the site's timezone
    $timezone = wp_timezone_string(); // e.g., 'America/New_York'

    // Add the timezone to the settings array
    $new_value['timezone'] = $timezone;

    return $new_value;
}

function aben_send_test_email()
{?>
<!-- Display success or error message if available -->
<?php if (isset($_GET['test_email_sent'])): ?>
<div id="aben-notice--<?php echo $_GET['test_email_sent'] === 'success' ? 'success' : 'error'; ?>"
    class="notice notice-<?php echo $_GET['test_email_sent'] === 'success' ? 'success' : 'error'; ?> is-dismissible">
    <p><?php echo $_GET['test_email_sent'] === 'success' ? 'Test email sent successfully.' : 'SMTP connection failed. Please check your credentials and try again'; ?>
    </p>
</div>
<?php endif;?>
<!-- Test Email Form -->
<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="aben-test-form">
    <p style="float: right;">
        <input type="email" id="test_email_address" placeholder="Enter Email Address" name="test_email_address"
            class="regular-text" required />
        <input type="hidden" name="action" value="aben_send_test_email" />
        <input type="submit" class="button button-primary" value="Send Test Email" />
    </p>
    <?php wp_nonce_field('aben_send_test_email', 'aben_test_email_nonce');?>
</form>

<?php

}