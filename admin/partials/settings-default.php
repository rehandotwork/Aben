<?php //Default Plugin Settings

if( ! defined( 'ABSPATH' ) ) {

    exit;

}

function aben_options_default() {
	
	return array(
		'post_type' => 'posts',
		'user_roles' => 'subscriber',
		'email_frequency' => 'once_in_a_day',
		'email_subject' => 'New post published',
		'email_body' => 'Hi, new post has been published. Read now',
	);

}