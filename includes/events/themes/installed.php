<?php

function maaq__theme_installed_event($upgrader, $hook_extra)
{
    if (!isset($hook_extra['type']) || $hook_extra['type'] != 'theme') {
        return;
    }

    if (!isset($hook_extra['action']) || $hook_extra['action'] != 'install') {
        return;
    }

    if (is_wp_error($upgrader->skin->result)) {
        return;
    }

    $theme_data = $upgrader->new_theme_data;
    if (!$theme_data) {
        return;
    }

    $stylesheet = $upgrader->result['destination_name'];
    if (!$stylesheet) {
        return;
    }

    $active_stylesheet = get_option('stylesheet');

    $parameters = [
        "stylesheet" => $stylesheet,
        "themeName" => $theme_data['Name'],
        "version" => $theme_data['Version'],
        "newVersion" => '',
        "isActive" => $stylesheet == $active_stylesheet,
    ];

    do_action('maaq_send_event', '-J3ESUd02xqE3fLk5PFhc', $parameters);
}
add_action('upgrader_process_complete', 'maaq__theme_installed_event', 10, 2);
