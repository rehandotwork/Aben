<?php
// Ensure this file is being called from WordPress.
if (!defined('ABSPATH')) {
    exit;
}

// Hook to register settings.
add_action('admin_init', 'aben_register_settings');

// Function to display the settings page.
function aben_display_settings_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    $tabs = array(
        'general'      => 'General',
        'email'        => 'Email Template',
        'smtp'         => 'SMTP',
        'email_logs'   => 'Email Logs',
        'unsubscribe'  => 'Unsubscribe',
    );

    $current_tab = isset($_GET['tab']) ? sanitize_text_field(wp_unslash($_GET['tab'])) : 'general';
    $current_tab = isset($tabs[$current_tab]) ? $current_tab : 'general';
    ?>

<div id="aben-app">
    <?php if (isset($_GET['settings-updated'])): ?>
    <div id="aben-notice" class="notice notice-success is-dismissible">
        <p>Settings saved successfully.</p>
    </div>
    <?php endif; ?>

    <div id="aben-header">
        <div id="aben-logo">
            <img src="<?php echo esc_url(ABEN_PLUGIN_LOGO); ?>" alt="ABEN Logo">
        </div>

        <nav class="nav-tab-wrapper" id="aben-nav">
            <div id="aben-nav-menu">
                <?php foreach ($tabs as $tab => $name): ?>
                <a href="?page=auto-bulk-email-notifications&tab=<?php echo esc_html($tab); ?>"
                    class="nav-tab <?php echo esc_html($current_tab === $tab ? 'nav-tab-active' : ''); ?>">
                    <?php echo esc_html($name); ?>
                </a>
                <?php endforeach; ?>
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
    } elseif ($current_tab === 'email') {
        echo '<div class = "aben-app__subheading"> 
        <p>Template Settings </p>
        <p>Email Preview</p>
        </div>';
        echo '<div id = "aben-email-tab-grid" style="display:grid; grid-template-columns:4fr 6fr; grid-gap:1rem;">';
        do_settings_sections('aben_section_email_setting');

        $site_logo = isset(aben_get_options()['site_logo']) ? aben_get_options()['site_logo'] : '';
        $show_view_post = aben_get_options()['show_view_post'];
        $featured_image = ABEN_FEATURED_IMAGE;

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
        $current_page = isset($_GET['paged']) ? absint(sanitize_text_field(wp_unslash($_GET['paged']))) : 1;
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
            echo count($logs) > $per_page ? wp_kses_post(paginate_links($pagination_args)) : '';
            echo '</div>';
        } else {
            echo '<table class="widefat fixed aben-email-logs">';
            echo '<thead><tr>';
            echo '<th width = 5%>#</th><th width = 30%>Subject</th><th width = 30%>To</th><th width = 10%>Status</th><th width = 20%>Date/Time</th>';
            echo '</tr></thead><tbody>';
            echo '<tr><td colspan="5">No email logs found.</td></tr>';
            echo '</tbody></table>';
        }
    }    if ($current_tab !== 'email_logs' && $current_tab !== 'unsubscribe' && $current_tab !== 'license') {
        submit_button();
    }
    ?>
        </form>
    </div>
    <?php if ($current_tab == 'email'):
        aben_send_test_email();
    endif;?>

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
        '<a id ="aben_remove_branding" href="https://rehan.work/aben" target=_blank>Remove Branding "Powered by Aben"</a>',
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

/**
 * Display the Test Email Form and Feedback Messages
 */
function aben_send_test_email() {
    // Retrieve the status of test email sending
    $test_email_sent = isset($_GET['test_email_sent']) 
        ? sanitize_text_field(wp_unslash($_GET['test_email_sent'])) 
        : '';
    ?>

<!-- Display success or error message if available -->
<?php if (!empty($test_email_sent)): ?>
<div id="aben-notice--<?php echo esc_html($test_email_sent === 'success' ? 'success' : 'error'); ?>"
    class="notice notice-<?php echo esc_html($test_email_sent === 'success' ? 'success' : 'error'); ?> is-dismissible">
    <p>
        <?php echo esc_html($test_email_sent === 'success' 
                    ? 'Test email sent successfully.' 
                    : 'SMTP connection failed. Please check your credentials and try again.'
                ); ?>
    </p>
</div>
<?php endif; ?>

<!-- Test Email Form -->
<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="aben-test-form">
    <p style="float: right;">
        <input type="email" id="test_email_address" name="test_email_address" placeholder="Enter Email Address"
            class="regular-text" required />
        <input type="hidden" name="action" value="aben_send_test_email" />
        <input type="submit" class="button button-primary" value="Send Test Email" />
    </p>
    <?php wp_nonce_field('aben_send_test_email', 'aben_test_email_nonce'); ?>
</form>
<?php
}

function aben_display_pro_page() {?>
<div class="wrap aben-pro-page">
    <img src="<?php echo esc_url(ABEN_PLUGIN_LOGO)?>" alt="">
    <div class="aben-pro-card">
        <div class="header">
            <h1>Pro Features</h1>
        </div>
        <div class="body">
            <ul>
                <li>Customize Template with Dynamic Data</li>
                <li>Send Event Emails</li>
                <li>Remove <strong>'Aben Branding'</strong> from Email Footer</li>
            </ul>
        </div>
        <div class="footer">
            <a href="https://rehan.work/aben" class="button button-primary" target="_blank">Buy Pro Now &#10138;</a>
        </div>
    </div>
</div>
<?php }