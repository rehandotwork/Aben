<?php
/**
 * Runs this code when user unsubscribe
 * for email notifications.
*/

if (!defined('ABSPATH')) {
    exit;
}

function aben_unsubscribe_user() {
    // Check if the 'aben-unsubscribe' query parameter exists in the URL
    if (isset($_GET['aben-unsubscribe'])) {
        // Sanitize the email address from the query URL
        $user_email = sanitize_email($_GET['aben-unsubscribe']);

        echo '<div class="unsubscribe-message">';
        
        // Check if the email address is valid
        if (is_email($user_email)) {
            // Get the user by their email address
            $user = get_user_by('email', $user_email);
            
            // Check if a valid user was found
            if ($user) {
                // Get the usermeta value for 'aben_notification'
                $aben_notification = get_user_meta($user->ID, 'aben_notification', true);
                
                // If the 'aben_notification' is 1 (subscribed), update it to 0 (unsubscribed)
                if ($aben_notification == 1) {
                    if (update_user_meta($user->ID, 'aben_notification', '0')) {
                        // Display a confirmation message
                        echo '<p>You have successfully unsubscribed!</p>';
                    } else {
                        // If updating the user meta fails
                        echo '<p>There was an issue updating your subscription status. Please try again later.</p>';
                    }
                } else {
                    // If the user is already unsubscribed
                    echo '<p>You have already unsubscribed.</p>';
                }
            } else {
                // If no user was found with the provided email
                echo '<p>No user found with that email address.</p>';
            }
        } else {
            // If the email is not valid
            echo '<p>Invalid email address format.</p>';
        } 

        echo '</div>';
    }
}
add_action('wp', 'aben_unsubscribe_user');

