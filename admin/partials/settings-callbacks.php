<?php //Setting Callbacks

if (!defined('ABSPATH')) {

    exit;

}

// Section Callbacks

function aben_callback_section_email_notification()
{
}

function aben_callback_section_email_template()
{
}

//Fields Callbacks

function aben_callback_field_text($args)
{

    $options = get_option('aben_options', aben_options_default());

    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    echo '<input id="aben_options_' . $id . '"
                name="aben_options[' . $id . ']"
                type="text"
                size="40"
                value="' . $value . '"><br />';

    echo '<label for="aben_options_' . $id . '">' . $label . '</label>';
}

function aben_callback_field_textarea($args)
{

    $options = get_option('aben_options', aben_options_default());

    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    // Get the allowed tags for this textarea
    $allowed_tags = wp_kses_allowed_html('post');

    // Get the value for this textarea
    $value = isset($options[$id]) ? wp_kses(stripslashes_deep($options[$id]), $allowed_tags) : '';

    echo '<textarea id="aben_options_' . $id . '"
                    name="aben_options[' . $id . ']"
                    rows="10"
                    cols="100">' . $value . '</textarea><br />';
    echo '<label for="aben_options_' . $id . '">' . $label . '</label>';

    // echo $value;
}

function aben_callback_field_select($args)
{

    $options = get_option('aben_options', aben_options_default());

    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

    $select_options = [];

    if ($id === 'post_type') {

        $post_types = get_post_types(array('public' => true), 'names');

        $select_options = $post_types;

        // var_dump($select_options);

    } else if ($id === 'user_roles') {

        global $wp_roles;

        if (!isset($wp_roles)) {
            $wp_roles = new WP_Roles();
        }

        $roles = $wp_roles->roles;

        // print_r($roles);

        $role_names = array_keys($roles);

        // print_r($role_names);

        foreach ($role_names as $role_name) {

            $select_options[$role_name] = ucwords($role_name);

        }

    } else if ($id === 'email_frequency') {

        $select_options = array(
            'once_in_a_day' => 'Once in a Day',
            'twice_in_a_day' => 'Twice in a Day',
            'after_2_hours' => 'After 2 Hours',
            'after_1_hour' => 'After 1 Hour',
            'on_post_publish' => 'On Post Publish',

        );

        // var_dump($select_options);

    }

    echo '<select id="aben_options_' . $id . '"
                  name="aben_options[' . $id . ']">';

    foreach ($select_options as $value => $option) {

        $selected = selected($selected_option, $value, false);

        echo '<option value="' . $value . '" ' . $selected . '>' . ucwords($option) . '</option>';
    }

    echo '</select> <br /><label for="aben_options_' . $id . '">' . $label . '</label>';

}
