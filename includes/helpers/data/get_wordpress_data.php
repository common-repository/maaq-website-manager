<?php

function maaq__get_wordpress_data()
{
    if (!function_exists('get_bloginfo')) {
        require_once ABSPATH . 'wp-admin/includes/general-template.php';
    }
    $current_version = get_bloginfo('version');

    if (!function_exists('wp_version_check') || !function_exists('get_core_updates')) {
        require_once ABSPATH . 'wp-admin/includes/update.php';
    }
    wp_version_check();
    $core_updates = get_core_updates();

    $new_version = null;

    if (!empty($core_updates)) {
        $latest_update = reset($core_updates); // Move the pointer to the first element

        if (version_compare($current_version, $latest_update->current, '<')) {
            $new_version = $latest_update->current;
        }
    }

    return [
        "version" => $current_version,
        "newVersion" => $new_version ?? '',
    ];
}
