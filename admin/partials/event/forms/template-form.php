<?php

if (!defined('ABSPATH')) {
    exit;
}

global $aben_events;
$options = $aben_events->get_options();
?>
<form method="post" action="options.php" style="max-height:unset">
    <?php
    // Security fields for the Options API
    settings_fields('aben_event_options_group');
    ?>

    <table class="form-table" id="aben-event-settings-table">
        <!-- Template Options -->
        <?php
        // Settings for the wp_editor
        $content_editor_settings = [
            'textarea_name'  => 'aben_event_options[template][content]',
            'textarea_rows'  => 10,
            'tinymce' => [
                'toolbar1' => 'formatselect,bold,italic,underline,alignleft,aligncenter,alignright,link',
                'block_formats' => 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;',
            ],
            'quicktags'      => true,
            'drag_drop_upload' => false,
            'media_buttons'  => true,
            'wpautop'         => true,
        ];

        $template_fields = [
            'header_text'       => 'Header Text',
            'content'           => 'Body',
            'show_button'       => 'Show Button',
            'button_text'       => 'Button Text',
            'button_url'        => 'Button URL',
            'site_logo'         => 'Site Logo',
            'footer_text'       => 'Footer Text',
            'header_bg'         => 'Header BG',
            'body_bg'           => 'Email BG',
            'content_bg'        => 'Body BG',
            'button_bg'         => 'Button BG',
            'button_text_color' => 'Button Text Color',
            'footer_bg'         => 'Footer BG',
        ];

        $color_fields_html = ''; // Placeholder for all color fields
        foreach ($template_fields as $field => $label) {
            $value = isset($options['template'][$field]) ? $options['template'][$field] : '';

            if ($field === 'show_button') {
                // Checkbox field for "show_button"
                ?>
        <tr>
            <th scope="row">
                <label for="aben_event_options_template_<?php echo $field; ?>"><?php echo $label; ?></label>
            </th>
            <td>
                <input type="checkbox" name="aben_event_options[template][<?php echo $field; ?>]"
                    id="aben_event_options_template_<?php echo $field; ?>" value="1" <?php checked($value, true); ?>>
                <span>Yes</span>
            </td>
        </tr>
        <?php
            } elseif (strpos($field, 'bg') !== false || strpos($field, 'color') !== false) {
                // Collect color fields in a separate variable
                ob_start(); // Start output buffering
                ?>
        <tr>
            <td style="padding: 0;">
                <input type="color" name="aben_event_options[template][<?php echo $field; ?>]"
                    id="aben_event_options_template_<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>"
                    class="color-picker" data-default-color="<?php echo esc_attr($value); ?>">
                <p><?php echo $label; ?></p>

            </td>
        </tr>
        <?php
                $color_fields_html .= ob_get_clean(); // Append the output to the color fields HTML
            } elseif ($field === 'content') {
                // Textarea field for "content"
                ?>
        <tr>
            <th scope="row">
                <label for="aben_event_options_template_<?php echo $field; ?>"><?php echo $label; ?></label>
            </th>
            <td>
                <?php
                wp_editor(wp_kses_post($value), 'aben_event_options_template_' . $field, $content_editor_settings);
                ?>
            </td>
        </tr>
        <?php
            } elseif ($field === 'site_logo') {
                // Media uploader field for "site_logo"
                ?>
        <tr>
            <th scope="row">
                <label for="aben_event_options_template_<?php echo $field; ?>"><?php echo $label; ?></label>
            </th>
            <td>
                <input type="hidden" name="aben_event_options_template_site_logo"
                    id="aben_event_options_template_site_logo"
                    value="<?php echo esc_attr($options['template']['site_logo']); ?>" class="regular-text">
                <button type="button" class="button aben-media-upload-button">Select Logo</button>
                <br>
                <p class="description">Upload or select a site logo from the Media Library.</p>
            </td>
        </tr>
        <?php
            } else {
                // General input field for all other fields
                ?>
        <tr>
            <th scope="row">
                <label for="aben_event_options_template_<?php echo $field; ?>"><?php echo $label; ?></label>
            </th>
            <td>
                <input type="text" name="aben_event_options[template][<?php echo $field; ?>]"
                    id="aben_event_options_template_<?php echo $field; ?>" value="<?php echo esc_attr($value); ?>">
            </td>
        </tr>
        <?php
            }
        }

        // Output the collected color fields wrapped in a container
        if (!empty($color_fields_html)) {
            ?>
        <th scope="row">
            <label for="color-fields-table">Color Fields</label>
        </th>
        <tr>
            <td colspan="2" style="padding: 0;">
                <table id="color-fields-table">
                    <?php echo $color_fields_html; ?>
                </table>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th scope="row" colspan="2" title="Remove Powered by Aben from Email Footer"><a id="aben_remove_branding"
                    href="/wp-admin/admin.php?page=aben&amp;tab=license">Remove Branding "Powered by Aben"</a></th>
            <td><label for="aben_options_remove_branding"><a href="https://rehan.work/aben" target="_blank"><img
                            style="max-width:150px; margin-top:-2px;" id="aben_branding" src=""></a></label></td>
        </tr>
    </table>

    <p class="submit">
        <input id="submit" type="submit" class="button button-primary" value="Save Changes">
    </p>
</form>