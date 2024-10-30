<?php

function maaq__plugin_installed_event($upgrader, $hook_extra)
{
    if (!isset($hook_extra['type']) || $hook_extra['type'] != 'plugin') {
        return;
    }

    if (!isset($hook_extra['action']) || $hook_extra['action'] != 'install' || $upgrader->bulk) {
        return;
    }

    if (is_wp_error($upgrader->skin->result)) {
        return;
    }

    $plugin_data = $upgrader->new_plugin_data;
    if (!$plugin_data) {
        return;
    }

    $plugin_file = $upgrader->plugin_info();

    $parameters = [
        "plugin" => $plugin_file,
        "pluginName" => $plugin_data['Name'],
        "version" => $plugin_data['Version'],
        "newVersion" => '',
        "isActive" => is_plugin_active($plugin_file),
    ];

    do_action('maaq_send_event', 'us7ZrUl05dT7I2zSGyvgl', $parameters);
}
add_action('upgrader_process_complete', 'maaq__plugin_installed_event', 10, 2);
