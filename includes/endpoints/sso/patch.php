<?php

function maaq__change_sso_user_id_endpoint($request)
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

    update_option('maaq_sso_user_id', $user_id);

    $parameters = [
        'username' => $user->user_login
    ];

    do_action('maaq_send_event', 'Q9hmR95qAFy7WigUxb7aA', $parameters);

    return wp_send_json([], 201);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'sso', [
        'methods' => 'PATCH',
        'callback' => 'maaq__change_sso_user_id_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'MANAGE_SSO_OPTIONS');
        },
        'show_in_index' => false,
    ]);
});
