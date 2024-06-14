<?php //User Meta Callbacks

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
                    <?=$aben_notification ? 'checked' : ''?>
                    />Check to get new post notifications by email <br />
            </td>
        </tr>
    </table>

    <?php

}

function aben_update_user_meta($user)
{

    $aben_notification = get_user_meta($user->ID, 'aben_notification', true);

    echo "<script>alert('Loaded')</script>";

}