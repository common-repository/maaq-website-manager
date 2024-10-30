<?php

function maaq__user_role_changed_event($user_id, $role, $old_roles)
{
    // Check if the user has just been registered.
    if (empty($old_roles)) {
        return;
    }

    if (!is_array($old_roles)) {
        $old_roles = [];
    }

    $old_role = reset($old_roles);

    $user = get_user_by('id', $user_id);

    $parameters = [
        "username" => $user->user_login,
        "newRole" => $role,
        "oldRole" => $old_role,
    ];

    do_action('maaq_send_event', 'iL7JjeoWjtCQdI-XC8pS0', $parameters);
}
add_action('set_user_role', 'maaq__user_role_changed_event', 10, 3);
