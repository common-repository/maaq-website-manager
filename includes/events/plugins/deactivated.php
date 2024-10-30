<?php

function maaq__plugin_deactivated_event($plugin_file)
{
    $plugin_name = maaq__get_plugin_name($plugin_file);

    $parameters = [
        "plugin" => $plugin_file,
        "pluginName" => $plugin_name,
    ];

    do_action('maaq_send_event', 'nVRcKl0Xiv0dfO7niqpQ5', $parameters);
}
add_action('deactivated_plugin', 'maaq__plugin_deactivated_event', 10, 1);
