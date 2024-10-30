<?php

function maaq__user_deleted_event($id, $reassign, $user)
{
    $parameters = [
        "username" => $user->user_login,
    ];

    do_action('maaq_send_event', '1dZ8GqhrkjwmMCJfD8NY1', $parameters);
}
add_action('deleted_user', 'maaq__user_deleted_event', 10, 3);
