<?php

function maaq__plugin_deleted_event($plugin_file, $deleted)
{
    if (!$deleted) {
        return;
    }

    $plugin_name = maaq__get_plugin_name($plugin_file);

    $parameters = [
        "plugin" => $plugin_file,
        "pluginName" => $plugin_name,
    ];

    do_action('maaq_send_event', 'i3Szte-tJ4s6AHFN7JQ56', $parameters);
}
add_action('deleted_plugin', 'maaq__plugin_deleted_event', 10, 2);
