<?php

function maaq__sync_endpoint()
{
    $wordpress = maaq__get_wordpress_data();
    $plugins = maaq__get_plugins_data();
    $themes = maaq__get_themes_data();

    $data = [
        "wordPress" => $wordpress,
        "plugins" => $plugins,
        "themes" => $themes,
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'syncs', [
        'methods' => 'GET',
        'callback' => 'maaq__sync_endpoint',
        'permission_callback' => 'maaq__server_permission_callback',
        'show_in_index' => false,
    ]);
});
