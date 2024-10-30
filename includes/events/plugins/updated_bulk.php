<?php

function maaq__plugin_updated_bulk_event($upgrader, $hook_extra)
{
    if (!isset($hook_extra['type']) || $hook_extra['type'] != 'plugin') {
        return;
    }

    if (!isset($hook_extra['bulk']) || !$hook_extra['bulk'] || !isset($hook_extra['action']) || $hook_extra['action'] != 'update') {
        return;
    }

    $plugins_updated = isset($hook_extra['plugins']) ? (array) $hook_extra['plugins'] : [];

    foreach ($plugins_updated as $plugin_file) {
        $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin_file, false, false);

        $parameters = [
            "plugin" => $plugin_file,
            "pluginName" => $plugin_data['Name'],
            "newVersion" => $plugin_data['Version']
        ];

        do_action('maaq_send_event', 'YaWQluky7lSsKp8RKXax5', $parameters);
    }
}
add_action('upgrader_process_complete', 'maaq__plugin_updated_bulk_event', 10, 2);
