<?php

function maaq__user_roles_endpoint($request)
{
    $wp_roles = wp_roles();
    $roles = array_keys($wp_roles->roles);

    $data = [
        "roles" => $roles,
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/roles', [
        'methods' => 'GET',
        'callback' => 'maaq__user_roles_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'READ_USERS');
        },
        'show_in_index' => false,
    ]);
});
