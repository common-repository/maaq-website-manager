<?php

function maaq__generate_sso_endpoint()
{
    $user_id = get_option('maaq_sso_user_id');

    if (!$user_id) {
        $data = [
            'token' => null,
        ];

        return wp_send_json($data, 200);
    }

    // Token expires after 5 minutes.
    $expiration = 300;

    $sso_token = maaq__create_sso_token($expiration);

    $data = [
        'token' => $sso_token,
        'expiresAt' => time() + $expiration
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'sso', [
        'methods' => 'GET',
        'callback' => 'maaq__generate_sso_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'READ_SSO');
        },
        'show_in_index' => false,
    ]);
});
