<?php

function maaq__theme_updated_event($upgrader, $hook_extra)
{
    if (!isset($hook_extra['type']) || $hook_extra['type'] != 'theme') {
        return;
    }

    if (!isset($hook_extra['action']) || $hook_extra['action'] != 'update') {
        return;
    }

    if (is_wp_error($upgrader->skin->result)) {
        return;
    }

    $themes_stylesheets = [];
    if (isset($hook_extra['bulk']) && $hook_extra['bulk']) {
        $themes_stylesheets = (array) $hook_extra['themes'];
    } else {
        array_push($themes_stylesheets, $hook_extra['themes']);
    }

    foreach ($themes_stylesheets as $theme_stylesheet) {
        $theme = wp_get_theme($theme_stylesheet);

        if (!is_a($theme, 'WP_Theme')) {
            continue;
        }

        $theme_version = $theme->get('Version');

        if (!$theme_version) {
            continue;
        }

        $theme_name = $theme->get('Name');

        $parameters = [
            "stylesheet" => $theme_stylesheet,
            "themeName" => $theme_name,
            "newVersion" => $theme_version,
        ];

        do_action('maaq_send_event', 'F5TAgjuun8AiLwfS_pac1', $parameters);
    }
}
add_action('upgrader_process_complete', 'maaq__theme_updated_event', 10, 2);
