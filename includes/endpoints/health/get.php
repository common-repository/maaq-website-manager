<?php

function maaq__health_endpoint()
{
    $data = [
        'healthy' => true,
    ];
    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'health', [
        'methods' => 'GET',
        'callback' => 'maaq__health_endpoint',
        'permission_callback' => '__return_true',
        'show_in_index' => false,
    ]);
});
