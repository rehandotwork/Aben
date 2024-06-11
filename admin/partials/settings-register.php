<?php // ABEN Settings Page

if( ! defined( 'ABSPATH' ) ) {

    exit;

}

function aben_display_settings_page () {

if ( !current_user_can( 'manage_options' ) ) return;

?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
    
            <?php

                settings_fields( 'aben_options' );

                do_settings_sections( 'aben' );

                submit_button();

                ?>
        </form>
</div>

<?php
}

//ABEN Register Settings

function aben_register_settings() {

	register_setting(
		'aben_options', 
		'aben_options', 
		'aben_callback_validate_options'
	);

	/* Setting Sections Start */
	
	add_settings_section( 
		'aben_section_email_notification', 
		'Email Notification', 
		'aben_callback_section_email_notification', 
		'aben'
	);
	
	add_settings_section( 
		'aben_section_email_template', 
		'Email Template', 
		'aben_callback_section_email_template', 
		'aben'
	);

	/* Setting Sections End */


	/* Setting Fields Start */
	
	add_settings_field( 
		'post_type', 
		'Post Type', 
		'aben_callback_field_select', 
		'aben', 
		'aben_section_email_notification', 
		[ 'id' => 'post_type', 'label' => 'Enable notification for post type' ]
	);
	
	add_settings_field( 
		'user_roles', 
		'Users', 
		'aben_callback_field_select', 
		'aben', 
		'aben_section_email_notification', 
		[ 'id' => 'user_roles', 'label' => 'Enable notification for users' ]
	);
	
	add_settings_field( 
		'email_frequency', 
		'Email Frequency', 
		'aben_callback_field_select', 
		'aben', 
		'aben_section_email_notification', 
		[ 'id' => 'email_frequency', 'label' => 'When to send Email' ]
	);
	
	add_settings_field( 
		'email_subject', 
		'Email Subject', 
		'aben_callback_field_text', 
		'aben', 
		'aben_section_email_template', 
		[ 'id' => 'email_subject', 'label' => 'Email Subject' ]
	);
	
	add_settings_field( 
		'email_body', 
		'Email Body', 
		'aben_callback_field_textarea', 
		'aben', 
		'aben_section_email_template', 
		[ 'id' => 'email_body', 'label' => 'Email body' ]
	);

	/* Setting Fields End */
}

add_action( 'admin_init', 'aben_register_settings' );
