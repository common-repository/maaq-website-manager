<?php

function maaq__get_themes_data()
{
    if (!function_exists('wp_get_themes')) {
        require_once ABSPATH . 'wp-admin/includes/theme.php';
    }
    $all_themes = wp_get_themes();

    if (empty($all_themes)) {
        return [];
    }

    $active_stylesheet = get_option('stylesheet');

    if (!function_exists('wp_update_themes') || !function_exists('get_theme_updates')) {
        require_once ABSPATH . 'wp-admin/includes/update.php';
    }
    wp_update_themes();
    $theme_updates = get_theme_updates();

    $parsed_themes = [];

    foreach ($all_themes as $stylesheet => $theme_data) {
        $new_version = null;

        if (!empty($theme_updates[$stylesheet]->update['new_version'])) {
            $new_version = $theme_updates[$stylesheet]->update['new_version'];
        }

        $is_active = $stylesheet === $active_stylesheet;

        $parsed_themes[] = [
            "stylesheet" => $stylesheet,
            "name" => $theme_data->Name,
            "version" => $theme_data->Version,
            "newVersion" => $new_version ?? '',
            "isActive" => $is_active,
        ];
    }

    return $parsed_themes;
}
