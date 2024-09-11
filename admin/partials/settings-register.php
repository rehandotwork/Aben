<?php // ABEN Settings Page

if (!defined('ABSPATH')) {

    exit;

}

function aben_display_settings_page()
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $tabs = array(
        'general' => 'General',
        'smtp' => 'SMTP',
        'email' => 'Email Template',
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
        do_settings_sections('aben_section_email_setting');
    }

    // Add a hidden field to identify the active tab if needed
    echo '<input type="hidden" name="aben_tab" value="' . esc_attr($current_tab) . '" />';

    submit_button();
    ?>
        </form>
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
        'aben_callback_section_receiver_setting',
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
        'archive_page_slug',
        'Archive Page Slug',
        'aben_callback_field_text',
        'aben_section_general_setting',
        'aben_section_general_setting',
        ['id' => 'archive_page_slug', 'label' => 'Slug of selected post type archive page']
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
        'aben_callback_section_sender_setting',
        'aben_section_smtp_setting'
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

    // Email Template Tab
    add_settings_section(
        'aben_section_email_setting',
        '',
        'aben_callback_section_email_setting',
        'aben_section_email_setting'
    );

    add_settings_field(
        'email_subject',
        'Email Subject',
        'aben_callback_field_text',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'email_subject', 'label' => 'Email Subject']
    );

    add_settings_field(
        'email_body',
        'Email Template',
        'aben_callback_field_textarea',
        'aben_section_email_setting',
        'aben_section_email_setting',
        ['id' => 'email_body', 'label' => 'Email body (Text/Markup)']
    );

}

add_action('admin_init', 'aben_register_settings');