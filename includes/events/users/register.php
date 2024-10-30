<?php

function maaq__user_register_event($user_id, $user_data)
{
    $parameters = [
        "username" => $user_data['user_login'],
    ];

    do_action('maaq_send_event', 'sM2-1WTRB5zNBN9s4LNYC', $parameters);
}
add_action('user_register', 'maaq__user_register_event', 10, 2);
