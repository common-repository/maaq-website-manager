<?php

function maaq__verify_sso_token($sso_token)
{
    $token = get_transient('maaq__sso_token_' . $sso_token);

    if (!$token || $token != $sso_token) {
        return false;
    }

    return true;
}
