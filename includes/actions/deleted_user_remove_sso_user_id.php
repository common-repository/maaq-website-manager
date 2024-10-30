<?php

function maaq__deleted_user_remove_sso_user_id($user_id)
{
    $sso_user_id = get_option('maaq_sso_user_id');

    if ($user_id == $sso_user_id) {
        delete_option('maaq_sso_user_id');
    }
}
add_action('deleted_user', 'maaq__deleted_user_remove_sso_user_id');
