<?php

function maaq__get_plugin_name($plugin_file)
{
    $plugin_path = MAAQ_PLUGIN_DIR_PATH . $plugin_file;
    $plugin_data = get_plugin_data($plugin_path, false, false);

    if ($plugin_data && isset($plugin_data['Name'])) {
        $plugin_name = $plugin_data['Name'];
    }

    return $plugin_name ?? $plugin_file;
}
