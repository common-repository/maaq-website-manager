<?php

function maaq__user_endpoint($request)
{
    $user_id = intval($request['userId']);

    $user = get_user_by('id', $user_id);

    if (!$user) {
        return new WP_Error(
            'bad_request',
            __('User could not be found...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);

    $isActiveSso = $user->data->ID == get_option('maaq_sso_user_id');

    $data = [
        "user" => [
            "id" => $user->data->ID,
            "username" => $user->data->user_login,
            "firstName" => $first_name,
            "lastName" => $last_name,
            "email" => $user->data->user_email,
            "isActiveSso" => $isActiveSso,
            "roles" => $user->roles,
        ]
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/(?P<userId>[\d]+)', [
        'methods' => 'GET',
        'callback' => 'maaq__user_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'READ_USERS');
        },
        'show_in_index' => false,
    ]);
});
