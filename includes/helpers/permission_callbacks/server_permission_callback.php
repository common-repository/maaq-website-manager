<?php

function maaq__server_permission_callback($request)
{
    $secret_token = maaq__extract_bearer_token($request);

    if (!$secret_token) {
        return false;
    }

    $server_secret_token = get_option('maaq_secret_token');

    if ($secret_token != $server_secret_token) {
        return false;
    }

    return true;
}
