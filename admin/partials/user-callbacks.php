<?php // User Meta Callbacks

if (!defined('ABSPATH')) {

    exit;

}

$user = new WP_User;

function aben_show_user_meta($user)
{
    $aben_notification = get_user_meta($user->ID, 'aben_notification', true);
    ?>

<h2>Auto Bulk Email Notification</h2>
    <table class="form-table">
        <tr>
            <th><label for="aben_notification">Enable Email Notification</label></th>
            <td>
                <input type="checkbox"
                    name="aben_notification"
                    id="aben_notification"
                    value="<?=$aben_notification == true ? '1' : '0'?>"
                    <?=$aben_notification == true ? 'checked' : ''?>
                    />Check to get new post notifications by email <br />
            </td>
        </tr>
    </table>

    <?php

}

function aben_update_user_meta($user)
{
    if (!current_user_can('edit_user', $user->ID)) {
        return false;
    }

    $aben_notification = get_user_meta($user->ID, 'aben_notification', true);

    // if (isset($_POST['aben_notification'])) {
    //     // echo '<script>alert("isset")</script>';
    //     update_user_meta($user->ID, 'aben_notification', true);

    // }

}