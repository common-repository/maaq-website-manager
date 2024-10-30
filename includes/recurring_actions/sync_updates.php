<?php

function maaq__sync_updates()
{
    if (!function_exists('wp_version_check') || !function_exists('wp_update_plugins') || !function_exists('wp_update_themes') || !function_exists('get_plugin_updates') || !function_exists('get_theme_updates') || !function_exists('get_core_updates')) {
        require_once ABSPATH . 'wp-admin/includes/update.php';
    }

    wp_version_check();
    wp_update_plugins();
    wp_update_themes();

    $core_updates = get_core_updates();
    $plugin_updates = get_plugin_updates();
    $theme_updates = get_theme_updates();

    $new_updates = [$plugin_updates, $theme_updates, $core_updates];
    $saved_updates = json_decode(get_option('maaq_sync_saved_updates_json'));

    if ($new_updates == $saved_updates) {
        return;
    }

    $parsed_plugins_updates = [];
    foreach ($plugin_updates as $plugin_file => $plugin_data) {
        if (isset($plugin_data->update) && isset($plugin_data->update->new_version)) {
            $saved_version = isset($saved_updates[0]->$plugin_file) ? $saved_updates[0]->$plugin_file->update->new_version : null;

            $new_version = $plugin_data->update->new_version;

            if ($saved_version !== $new_version) {
                $parsed_plugins_updates[] = [
                    "plugin" => $plugin_file,
                    "newVersion" => $new_version,
                ];
            }
        }
    }

    $parsed_themes_updates = [];
    foreach ($theme_updates as $stylesheet => $theme_data) {
        if (isset($theme_data->update) && isset($theme_data->update['new_version'])) {
            $saved_version = isset($saved_updates[1]->$stylesheet) ? $saved_updates[1]->$stylesheet->update->new_version : null;

            $new_version = $theme_data->update['new_version'];

            if ($saved_version !== $new_version) {
                $parsed_themes_updates[] = [
                    "stylesheet" => $stylesheet,
                    "newVersion" => $new_version,
                ];
            }
        }
    }

    $new_wordpress_version = null;
    if (!empty($core_updates)) {
        if (isset($saved_updates[2]) && is_array($saved_updates[2])) {
            $saved_latest_update = reset($saved_updates[2]);
            $saved_response = $saved_latest_update->response;
        }

        $latest_update = reset($core_updates);
        $new_response = $latest_update->response;

        if (($saved_response == 'latest' && $new_response == 'upgrade') || empty($saved_response) && $new_response == 'upgrade') {
            $new_wordpress_version = $latest_update->current;
        }
    }

    update_option('maaq_sync_saved_updates_json', wp_json_encode($new_updates));

    if (count($parsed_plugins_updates) == 0 && count($parsed_themes_updates) == 0 && empty($new_wordpress_version)) {
        return;
    }

    $args = [
        '/websites/syncs/updates',
        'PUT',
        [
            "wordPress" => [
                "newVersion" => $new_wordpress_version,
            ],
            "plugins" => $parsed_plugins_updates,
            "themes" => $parsed_themes_updates,
            "secretToken" => get_option('maaq_secret_token')
        ],
    ];

    as_enqueue_async_action('maaq_send_api_request', $args, 'maaq-website-manager');
}
add_action('maaq_sync_updates', 'maaq__sync_updates');
