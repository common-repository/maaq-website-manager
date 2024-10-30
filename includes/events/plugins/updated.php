<?php

function maaq__plugin_updated_event($upgrader, $hook_extra)
{
    if (!isset($hook_extra['type']) || $hook_extra['type'] != 'plugin') {
        return;
    }

    if (!isset($hook_extra['action']) || $hook_extra['action'] != 'update' || $upgrader->bulk) {
        return;
    }

    $plugin_data = get_plugin_data(WP_PLUGIN_DIR . '/' . $hook_extra['plugin'], false, false);

    if (!$plugin_data) {
        return;
    }

    if (is_wp_error($upgrader->skin->result)) {
        return;
    }

    $plugin_file = $upgrader->plugin_info();

    $parameters = [
        "plugin" => $plugin_file,
        "pluginName" => $plugin_data['Name'],
        "newVersion" => $plugin_data['Version']
    ];

    do_action('maaq_send_event', 'YaWQluky7lSsKp8RKXax5', $parameters);
}
add_action('upgrader_process_complete', 'maaq__plugin_updated_event', 10, 2);
