<?php

function maaq__create_sso_token($expiration)
{
    $new_sso_token = wp_generate_password(wp_rand(32, 38), false);

    set_transient('maaq__sso_token_' . $new_sso_token, $new_sso_token, $expiration);

    return $new_sso_token;
}
