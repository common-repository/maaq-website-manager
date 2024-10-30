<?php

function maaq__verify_sso_endpoint($request)
{
    $sso_token = sanitize_text_field($request['token']);
    maaq__delete_sso_token($sso_token);

    $user_id = get_option('maaq_sso_user_id');

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

    wp_clear_auth_cookie();
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    $parameters = [
        'username' => $user->user_login
    ];

    do_action('maaq_send_event', 'DMNrckK4tTQy3vfqshXhl', $parameters);

    wp_safe_redirect(admin_url());

    return wp_send_json([], 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'sso/(?P<token>[^/]+)', [
        'methods' => 'GET',
        'callback' => 'maaq__verify_sso_endpoint',
        'permission_callback' => function ($request) {
            $sso_token = sanitize_text_field($request['token']);

            return maaq__verify_sso_token($sso_token);
        },
        'show_in_index' => false,
    ]);
});
