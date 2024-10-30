<?php

function maaq__on_activation()
{
    // Check if options don't exist yet.
    if (empty(get_option('maaq_secret_token')) || empty(get_option('maaq_secret_path'))) {
        // Generate and insert all the random values.
        do_action('maaq_generate_secrets');
    }

    if (empty(get_option('maaq_sso_user_id'))) {
        $current_user_id = get_current_user_id();
        update_option('maaq_sso_user_id',  $current_user_id);
    }

    as_schedule_recurring_action(time(), 1800, 'maaq_sync_updates', [], '', true);

    add_option('maaq_do_on_activation_redirect', true);
}
