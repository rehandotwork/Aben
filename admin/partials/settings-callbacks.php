<?php //Setting Callbacks

if (!defined('ABSPATH')) {

    exit;

}

// Section Callbacks

function aben_callback_section_general_setting()
{
}

function aben_callback_section_smtp_setting()
{
}

function aben_callback_section_email_setting()
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

    /* echo '<textarea id="aben_options_' . $id . '"
    name="aben_options[' . $id . ']"
    rows="10"
    cols="100">' . $value . '</textarea><br />';
    echo '<label for="aben_options_' . $id . '">' . $label . '</label>'; */
    echo '<div style="font-family:Open Sans,sans-serif;margin:0;padding:0;background-color: #f5f7fa;color: #1f2430;">
	<div style="width:100%;max-width:500px;margin: auto;">
        <div style="padding:20px;">
           <p style="font-size:16px"><strong>Hello Rehan</strong> <img width="16px" data-emoji="👋" class="an1" alt="👋" aria-label="👋" draggable="false" src="https://fonts.gstatic.com/s/e/notoemoji/15.1/1f44b/72.png" loading="lazy">&nbsp;</p>
			<p style="font-size:16px;">We bring you the newest Gulf jobs matching your profile: <span>Construction Manager jobs in Dubai - <a href="#" style="text-decoration: none;">edit preference</a></span></p>
        </div>
		 <div style="padding:10px">
		 <div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
          <div style="width:80%">
            <p style="font-size:16px;margin:0;color: #008dcd;">First Post Title</p>
            <p style="font-size:14px;color:#333333;margin:5px 0 0">Excerpt</p>
          </div>
		  <div style="width:20%;align-content: center;text-align: center;">
			<a href="#" style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">Apply</a>
		</div>
		</div>
		<div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
          <div style="width:80%">
            <p style="font-size:16px;margin:0;color: #008dcd;">Second Post Title</p>
            <p style="font-size:14px;color:#333333;margin:5px 0 0">Excerpt</p>
          </div>
		  <div style="width:20%;align-content: center;text-align: center;">
			<a href="#" style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">Apply</a>
		</div>
		</div>
		<div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
          <div style="width:80%">
            <p style="font-size:16px;margin:0;color: #008dcd;">Third Post Title</p>
            <p style="font-size:14px;color:#333333;margin:5px 0 0">Excerpt</p>
          </div>
		  <div style="width:20%;align-content: center;text-align: center;">
			<a href="#" style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">Apply</a>
		</div>
		</div>
		<div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
          <div style="width:80%">
            <p style="font-size:16px;margin:0;color: #008dcd;">Fourth Post Title</p>
            <p style="font-size:14px;color:#333333;margin:5px 0 0">Excerpt</p>
          </div>
		  <div style="width:20%;align-content: center;text-align: center;">
			<a href="#" style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">Apply</a>
		</div>
		</div>
		<div style="display:flex;margin-bottom:20px;padding:20px;background: white;">
          <div style="width:80%">
            <p style="font-size:16px;margin:0;color: #008dcd;">Fifth Post Title</p>
            <p style="font-size:14px;color:#333333;margin:5px 0 0">Excerpt</p>
          </div>
		  <div style="width:20%;align-content: center;text-align: center;">
			<a href="#" style="display:inline-block;padding:5px 20px;color:#fff;text-decoration:none;background-color:#0ead5d;border-radius:25px;height:fit-content">Apply</a>
		</div>
		</div>
		<div style="display:flex;padding-bottom:10px;">
			<div style="width:100%;text-align:center;">
            	<a href="#" style="display:inline-block;padding:10px 20px;background-color:#165d31;color:#ffffff;text-decoration:none;border-radius:25px">View All (30) Jobs</a>
			</div>
        </div>
		</div>
        <div style="color:#808080;text-align:center;padding:20px;">
            <a href="#"><img src="https://gulfworking.com/wp-content/uploads/2024/08/gw-logo.png" alt="Site Logo" style="max-width:180px;margin-top: 10px;"></a>
            <p>Gulfworking.com © 2024 All rights reserved.</p>
            <p><a href="#" style="color:#808080;text-decoration:none">Unsubscribe</a></p>
		</div>
		</div>';
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

    } else if ($id === 'smtp_encryption') {

        $select_options = array(
            'none' => 'None',
            'tls' => 'TLS',
            'ssl' => 'SSL',
        );

    } else if ($id === 'number_of_posts') {

        $select_options = array(
            1 => '1',
            2 => '2',
            3 => '3',
            4 => '4',
            5 => '5',
            6 => '6',
            7 => '7',
            8 => '8',
            9 => '9',
            10 => 'All',

        );

    } else if ($id === 'email_frequency') {

        $select_options = array(
            'once_in_a_day' => 'Once in a Day',
            'once_in_a_week' => 'Once in a Week',
        );

        // var_dump($select_options);

        aben_update_cron(); // Refer to update-cron.php

    }

    echo '<select id="aben_options_' . $id . '"
                  name="aben_options[' . $id . ']">';

    foreach ($select_options as $value => $option) {

        $selected = selected($selected_option, $value, false);

        echo '<option value="' . $value . '" ' . $selected . '>' . ucwords($option) . '</option>';
    }

    echo '</select> <br /><label for="aben_options_' . $id . '">' . $label . '</label>';

}

function aben_callback_field_checkbox($args)
{
    // Retrieve the current options from the database, or use default values
    $options = get_option('aben_options', array());

    // Get the ID and label for the field from the arguments
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    // Check if the checkbox should be checked
    $checked = isset($options[$id]) && $options[$id] == 1 ? 'checked' : '';

    echo '<input type="hidden" name="aben_options[' . esc_attr($id) . ']" value="0">';

    // Render the checkbox input field
    echo '<input id="aben_options_' . esc_attr($id) . '"
                name="aben_options[' . esc_attr($id) . ']"
                type="checkbox"
                value="1"
                ' . $checked . '>';

    // Render the label for the checkbox
    echo '<label for="aben_options_' . esc_attr($id) . '">' . esc_html($label) . '</label>';
}

function aben_callback_field_password($args)
{
    // Retrieve the current options from the database, or use default values
    $options = get_option('aben_options', array());

    // Get the ID and label for the field from the arguments
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    // Retrieve the current value for the field, if set
    $value = isset($options[$id]) ? esc_attr($options[$id]) : '';

    // Render the password input field
    echo '<input id="aben_options_' . esc_attr($id) . '"
              name="aben_options[' . esc_attr($id) . ']"
              type="password"
              size="40"
              value="' . $value . '"><br />';

    // Render the label for the password field
    echo '<label for="aben_options_' . esc_attr($id) . '">' . esc_html($label) . '</label>';
}

function aben_callback_field_color($args)
{
    // Retrieve the current options from the database, or use default values
    $options = get_option('aben_options', array());

    // Get the ID and label for the field from the arguments
    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    // Retrieve the current value for the field, if set
    $value = isset($options[$id]) ? esc_attr($options[$id]) : '#000000'; // Default to black if no color is set

    // Render the color input field
    echo '<input id="aben_options_' . esc_attr($id) . '"
              name="aben_options[' . esc_attr($id) . ']"
              type="color"
              value="' . $value . '"><br />';

    // Render the label for the color field
    echo '<label for="aben_options_' . esc_attr($id) . '">' . esc_html($label) . '</label>';
}

function aben_callback_field_media($args)
{
    $options = get_option('aben_options', aben_options_default());

    $id = isset($args['id']) ? $args['id'] : '';
    $label = isset($args['label']) ? $args['label'] : '';

    $value = isset($options[$id]) ? esc_url($options[$id]) : '';

    echo '<input type="hidden" id="aben_options_' . esc_attr($id) . '"
                name="aben_options[' . esc_attr($id) . ']"
                value="' . esc_attr($value) . '">';
    echo '<button type="button" class="button aben-media-upload-button">Upload Image</button>';
    echo '<img id="aben_' . esc_attr($id) . '_preview" src="' . esc_url($value) . '" style="max-width:100px;margin-top:10px;' . ($value ? 'display:block;' : 'display:none;') . '">';
    echo '<button type="button" class="button aben-media-remove-button" style="' . ($value ? 'display:block;' : 'display:none;') . '">Remove Image</button>';
    echo '<br><label for="aben_options_' . esc_attr($id) . '">' . esc_html($label) . '</label>';
}
