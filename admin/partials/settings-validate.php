<?php //Validation Callbacks 

if( ! defined( 'ABSPATH' ) ) {

    exit;

}


function aben_callback_validate_options( $input ) {

    if( isset($input['post_type']) ) {

        $input['post_type'] = sanitize_text_field( $input['post_type'] );

    }
    
    if( isset($input['user_roles']) ) {

        $input['user_roles'] = sanitize_text_field( $input['user_roles'] );

    }
    
    if( isset($input['email_frequency']) ) {

        $input['email_frequency'] = sanitize_text_field( $input['email_frequency'] );

    }
    
    if( isset($input['email_subject']) ) {

        $input['email_subject'] = sanitize_text_field( $input['email_subject'] );

    }

    if( isset($input['email_body']) ) {

        $input['email_body'] = wp_kses_post( $input['email_body'] );

    }
    
    return $input;

}