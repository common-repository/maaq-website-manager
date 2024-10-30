<?php

function maaq__on_activation_redirect()
{
    $redirect = get_option('maaq_do_on_activation_redirect', false);

    if ($redirect) {
        delete_option('maaq_do_on_activation_redirect');

        if (!isset($_GET['activate-multi'])) {
            $maaq_settings_url = admin_url('options-general.php?page=maaq');

            wp_redirect($maaq_settings_url);
            exit;
        }
    }
}
add_action('admin_init', 'maaq__on_activation_redirect');
