<?php

function maaq__delete_sso_token($sso_token)
{
    delete_transient('maaq__sso_token_' . $sso_token);
}
