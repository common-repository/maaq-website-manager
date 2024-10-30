<?php

function maaq__jwt_permission_callback($request, $permission)
{
    $jwt = maaq__extract_bearer_token($request);

    if (!$jwt || is_wp_error($jwt)) {
        return false;
    }

    $has_permission = maaq__has_permission($jwt, $permission);

    if (is_wp_error($has_permission)) {
        return false;
    }

    return true;
}
