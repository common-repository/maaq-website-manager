<?php

function maaq__users_endpoint($request)
{
    $limit = 20;

    $search = isset($request['search']) ? sanitize_text_field($request['search']) : '';

    // We'd call it cursor instead of offset here so the app implementation is easier.
    $cursor = isset($request['cursor']) ? intval($request['cursor']) : 0;

    $args = [
        'number' => $limit + 1,
    ];

    if ($search) {
        $args['search'] = '*' . sanitize_text_field($search) . '*';
    }

    if ($cursor) {
        $args['offset'] = $cursor;
    }

    $users = get_users($args);

    $parsed_users = [];
    foreach ($users as $user) {
        array_push($parsed_users, [
            "id" => $user->data->ID,
            "username" => $user->data->user_login,
            "email" => $user->data->user_email,
            "roles" => $user->roles,
        ]);
    }

    $new_cursor = null;
    if (count($parsed_users) === $limit + 1) {
        array_pop($parsed_users);
        $new_cursor = isset($cursor) ? ($cursor + $limit) : null;
    }

    // Return the result along with the next cursor.
    $data = [
        'users' => $parsed_users,
        'cursor' => $new_cursor,
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users', [
        'methods' => 'GET',
        'callback' => 'maaq__users_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'READ_USERS');
        },
        'show_in_index' => false,
    ]);
});
