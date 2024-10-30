<?php

function maaq__plugin_activated_event($plugin_file)
{
    $plugin_name = maaq__get_plugin_name($plugin_file);

    $parameters = [
        "plugin" => $plugin_file,
        "pluginName" => $plugin_name,
    ];

    do_action('maaq_send_event', '05yHDURPWxmecqooKi7yo', $parameters);
}
add_action('activated_plugin', 'maaq__plugin_activated_event', 10, 1);
