<?php

class Aben_Events
{
    private $slug;
    private $logger;
    public $options;

    public function __construct()
    {
        $this->slug    = 'aben-events';
        $this->options = $this->get_options();
        $this->logger  = new Aben_Email_Logs();
        
        // Hook to handle the form submission
        add_action('admin_post_aben_send_event_emails_action', [$this, 'handle_send_email_form']);
    }

    public function display_email_form()
    {
        $this->email_form();
    }

    public function display_template_settings_form()
    {
        $this->template_settings_form();
    }

    public function display_template()
    {
        $this->template();
    }

    public function handle_send_email_form()
    {
        if (!isset($_POST['aben_send_event_emails_nonce']) ||
            !wp_verify_nonce($_POST['aben_send_event_emails_nonce'], 'aben_send_event_emails_action')) {
            wp_die('Security check failed.');
        }
        $this->send_email();
        //Redirect with a success message
        wp_redirect(add_query_arg('message', 'emails_sent_successfully', admin_url('admin.php?page=aben-events')));
        exit;
    }


    private function get_options()
    {
        return get_option('aben_event_options');
    }

    private function email_form()
    {
        require_once ABEN_PLUGIN_PATH . 'admin/partials/event/forms/email-form.php';
    }

    private function template_settings_form()
    {
        require_once ABEN_PLUGIN_PATH . 'admin/partials/event/forms/template-form.php';
    }

    private function template()
    {
        require_once ABEN_PLUGIN_PATH . 'admin/partials/event/templates/dummy.php';
    }

    private function build_email() {
        ob_start();
        require_once ABEN_PLUGIN_PATH . 'admin/partials/event/templates/dummy.php';
        return ob_get_clean();
}

    private function personalized_email($user_email) {
        $email_content = $this->build_email();
        $user = get_user_by('email', $user_email);
        $user_display_name = ucfirst($user->display_name);
        $user_display_name = explode(' ', $user_display_name);
        $user_firstname = $user_display_name[0];
        $personalized_email = str_replace('{{USERNAME}}', $user_firstname, $email_content);
        return $personalized_email;
}

    public function send_email()
    {
        $users                = get_users(['role' => $this->options['role']]); // Change it to $this->options['role'] before going live
        $email_subject        = $this->options['email_subject'];
        $custom_smtp_settings = boolval(aben_get_smtp_settings()['use_smtp']);

        //Return if no users found to the matching role
        if (empty($users)) {
            error_log('No users found for the role ');
            return;
        }

        //Loop through the users and send email
        if ($custom_smtp_settings) {
            foreach ($users as $user) {
                $email_body = $this->personalized_email($user->user_email);
                aben_send_smtp_email($user->user_email, $email_subject, $email_body);
                //Needs to Add email logging also
                error_log('Event sent via Custom SMTP');
            }
        } else {
            foreach ($users as $user) {
                $email_body = $this->personalized_email($user->user_email);
                aben_send_own_smtp_email($user->user_email, $email_subject, $email_body);
                //Needs to Add email logging also
                error_log('Event sent via Default SMTP');

            }
        }
        return true;
    }
}

// Instantiate the global instance
global $aben_events;
$aben_events = new Aben_Events();

add_action('plugins_loaded', function(){
    global $aben_events;
    // $aben_events->send_email();
});