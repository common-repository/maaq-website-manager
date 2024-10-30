<?php

// Automatically runs this file when the user uninstalls this plugin.
// No need to use the register_uninstall_hook hook :).

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Remove all the option keys.
$option_keys = [
    // Secrets.
    'maaq_secret_token',
    'maaq_secret_path',
    // Sync.
    'maaq_sync_saved_updates_json',
    // Single sign-on.
    'maaq_sso_user_id',
    // On activation redirect.
    'maaq_do_on_activation_redirect',
];
foreach ($option_keys as $option_key) {
    delete_option($option_key);
}
