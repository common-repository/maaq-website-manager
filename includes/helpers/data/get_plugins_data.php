<?php

function maaq__get_plugins_data()
{
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $all_plugins = get_plugins();

    if (empty($all_plugins)) {
        return [];
    }

    $active_plugins = get_option('active_plugins');

    if (!function_exists('wp_update_plugins') || !function_exists('get_plugin_updates')) {
        require_once ABSPATH . 'wp-admin/includes/update.php';
    }
    wp_update_plugins();
    $plugin_updates = get_plugin_updates();

    $parsed_plugins = [];

    foreach ($all_plugins as $plugin_file => $plugin_data) {
        $new_version = null;

        if (!empty($plugin_updates[$plugin_file]->update->new_version)) {
            $new_version = $plugin_updates[$plugin_file]->update->new_version;
        }

        $is_active = in_array($plugin_file, $active_plugins);

        $parsed_plugins[] = [
            "plugin" => $plugin_file,
            "name" => $plugin_data['Name'],
            "version" => $plugin_data['Version'],
            "newVersion" => $new_version ?? '',
            "isActive" => $is_active,
        ];
    }

    return $parsed_plugins;
}
