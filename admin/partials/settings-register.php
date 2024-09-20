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
        'smtp' => 'SMTP',
        'email' => 'Email Template',
        'test_email' => 'Test Email',
    );

    $current_tab = isset($_GET['tab']) && isset($tabs[$_GET['tab']]) ? $_GET['tab'] : 'general';

    ?>

    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

        <nav class="nav-tab-wrapper">
            <?php foreach ($tabs as $tab => $name): ?>
                <a href="?page=aben&tab=<?php echo $tab; ?>" class="nav-tab <?php echo $current_tab === $tab ? 'nav-tab-active' : ''; ?>">
                    <?php echo $name; ?>
                </a>
            <?php endforeach;?>
        </nav>

        <form action="options.php" method="post">
            <?php
settings_fields('aben_options');

    // Display only the relevant settings based on the active tab
    if ($current_tab === 'general') {
        do_settings_sections('aben_section_general_setting');
    } elseif ($current_tab === 'smtp') {
        do_settings_sections('aben_section_smtp_setting');
    } elseif ($current_tab === 'email') {
        echo '<div id = "aben-email-tab-grid" style="display:grid; grid-template-columns:4fr 6fr; grid-gap:1rem;">';
        do_settings_sections('aben_section_email_setting');
        // do_settings_sections('aben_section_email_template');
        
        $aben_email_dashboard = new Aben_Email(
            '', //mail_subject
            'https://aben.com/blogs', //archive_page_slug
            6, //number_of_posts
            'https://aben.com/unsubscribe', //unsubscribe_link
            '#f0eeff', //body_bg
            'Hi Rehan', //header_text
            '#f0eeff', //header_bg
            'Check out our daily posts and send your feedback.', //header_subtext
            'Copyright 2024 | Aben Inc.', //footer_text
            'http://gw.local/wp-content/uploads/2023/01/finalLogo-cropped-1.svg', //site_logo
            true, //show_view_all
            'View All Posts', //view_all_posts_text
            true, //show_number_view_all
            true, //show_view_post
            'Read', //view_post_text
            true, //show_unsubscribe
            [
                [
                    'title' => 'Understanding WordPress Plugins',
                    'link' => 'https://example.com/understanding-wordpress-plugins',
                    'excerpt' => 'Learn about the basics of WordPress plugins, how they work, and why they are useful.',
                ],
                [
                    'title' => '10 Tips for Optimizing Your Website',
                    'link' => 'https://example.com/optimizing-your-website',
                    'excerpt' => 'Follow these essential tips to ensure your website runs smoothly and efficiently.',
                ],
                [
                    'title' => 'The Importance of SEO in 2024',
                    'link' => 'https://example.com/importance-of-seo',
                    'excerpt' => 'SEO remains crucial for online success. Discover how to stay ahead in 2024.',
                ],
                [
                    'title' => 'Best Practices for Web Development',
                    'link' => 'https://example.com/web-development-best-practices',
                    'excerpt' => 'Adopt these best practices to enhance your web development workflow and deliver top-notch projects.',
                ],
                [
                    'title' => 'How to Boost Website Security',
                    'link' => 'https://example.com/boost-website-security',
                    'excerpt' => 'Learn the steps you can take to improve your website’s security and protect against potential threats.',
                ],
            ] //posts_to_send 
        );
        $aben_email_dashboard->aben_email_template();
        echo '</div>';
    }

    // Add a hidden field to identify the active tab if needed
    echo '<input type="hidden" name="aben_tab" value="' . esc_attr($current_tab) . '" />';

    // Add submit button for all tabs except "test_email"
    if ($current_tab !== 'test_email') {
        submit_button();
    }
    ?>
        </form>

        <?php if ($current_tab === 'test_email'): ?>
            <!-- Display success or error message if available -->
            <?php if (isset($_GET['test_email_sent'])): ?>
                <div class="notice notice-<?php echo $_GET['test_email_sent'] === 'success' ? 'success' : 'error'; ?> is-dismissible">
                    <p><?php echo $_GET['test_email_sent'] === 'success' ? 'Test email sent successfully.' : 'SMTP connection failed. Please check your credentials and try again'; ?></p>
                </div>
            <?php endif;?>

            <!-- Test Email Form -->
            <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <p>
                    <label for="test_email_address">Send Test Mail To:</label>
                    <input type="email" id="test_email_address" name="test_email_address" class="regular-text" required />
                </p>
                <p>
                    <input type="hidden" name="action" value="aben_send_test_email" />
                    <input type="submit" class="button button-primary" value="Send Test Email" />
                </p>
                <?php wp_nonce_field('aben_send_test_email', 'aben_test_email_nonce');?>
            </form>

        <?php endif;?>
    </div>
    <?php
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
        'post_type',
        'Post Type',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'post_type', 'label' => 'Enable notification for post type']
    );

    add_settings_field(
        'user_roles',
        'Users',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'user_roles', 'label' => 'Enable notification for users']
    );

    add_settings_field(
        'email_subject',
        'Email Subject',
        'aben_callback_field_text',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'email_subject', 'label' => 'Email Subject']
    );

    add_settings_field(
        'archive_page_slug',
        'All Posts Page Link',
        'aben_callback_field_text',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'archive_page_slug', 'label' => 'Path of all posts page']
    );

    add_settings_field(
        'unsubscribe_link',
        'Unsubscribe Link',
        'aben_callback_field_text',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'unsubscribe_link', 'label' => 'Link for unsubscribe from email notification']
    );

    add_settings_field(
        'email_frequency',
        'Email Frequency',
        'aben_callback_field_select',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'email_frequency', 'label' => 'When to send Email']
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
        'Use SMTP',
        'aben_callback_field_checkbox',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'use_smtp', 'label' => 'Check to use SMTP service']
    );

    add_settings_field(
        'smtp_host',
        'SMTP Host',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_host', 'label' => 'SMTP hostname or IP']
    );

    add_settings_field(
        'smtp_port',
        'SMTP Port',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_port', 'label' => 'SMTP port']
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
        ['id' => 'smtp_username', 'label' => 'SMTP username']
    );

    add_settings_field(
        'smtp_password',
        'Password',
        'aben_callback_field_password',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'smtp_password', 'label' => 'SMTP password']
    );

    add_settings_field(
        'from_name',
        'From',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'from_name', 'label' => 'From name you want to send in email']
    );

    add_settings_field(
        'from_email',
        'Reply to',
        'aben_callback_field_text',
        'aben_section_smtp_setting',
        'aben_section_smtp_setting',
        ['id' => 'from_email', 'label' => 'Reply to email address']
    );

    // Email Template Tab
    add_settings_section(
        'aben_section_email_setting',
        '',
        'aben_callback_section_email_setting',
        'aben_section_email_setting'
    );

    add_settings_field(
        'body_bg',
        'Body Background',
        'aben_callback_field_color',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'body_bg', 'label' => 'Background color of body']
    );

    add_settings_field(
        'header_text',
        'Header Text',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_text', 'label' => 'Text in the email header']
    );

    add_settings_field(
        'header_bg',
        'Header Background',
        'aben_callback_field_color',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_bg', 'label' => 'Background color of header']
    );

    add_settings_field(
        'header_subtext',
        'Header Subtext',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'header_subtext', 'label' => 'Subtext in the email header']
    );

    add_settings_field(
        'footer_text',
        'Footer Text',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'footer_text', 'label' => 'Footer text in the email']
    );

    add_settings_field(
        'site_logo',
        'Site Logo',
        'aben_callback_field_media',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'site_logo', 'label' => 'Site logo in the email']
    );

    add_settings_field(
        'number_of_posts',
        'Number of Posts',
        'aben_callback_field_select',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'number_of_posts', 'label' => 'Number of posts to send in email']
    );

    add_settings_field(
        'show_view_all',
        'Show View All Posts Button',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_view_all', 'label' => 'Display View All button in email']
    );

    add_settings_field(
        'view_all_posts_text',
        'Change "View All Posts" text to',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'view_all_posts_text', 'label' => '']
    );

    add_settings_field(
        'show_number_view_all',
        'Show Posts Number',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_number_view_all', 'label' => 'Show posts number in "View All" button']
    );

    add_settings_field(
        'show_view_post',
        'Show View Post',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_view_post', 'label' => 'Show "View Post" button in posts list']
    );

    add_settings_field(
        'view_post_text',
        'Change "View Post" text to',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'view_post_text', 'label' => '']
    );

    add_settings_field(
        'show_unsubscribe',
        'Show Unsubscribe',
        'aben_callback_field_checkbox',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'show_unsubscribe', 'label' => 'Check to show unsubscribe link in email']
    );

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
