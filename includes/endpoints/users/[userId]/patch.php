<?php

function maaq__patch_user_endpoint($request)
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

    $user_data = [
        'ID' => $user_id,
    ];

    $body = json_decode($request->get_body(), true);

    if (isset($body['email'])) {
        $user_data['user_email'] = strtolower(sanitize_email($body['email']));

        if (!is_email($user_data['user_email'])) {
            return new WP_Error(
                'invalid_email',
                __('This email is invalid...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
        }

        if (email_exists($user_data['user_email'])) {
            return new WP_Error(
                'email_exists',
                __('User with this email already exists...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
        }
    }

    if (isset($body['firstName'])) {
        $user_data['first_name'] = sanitize_text_field($body['firstName']);
    }

    if (isset($body['lastName'])) {
        $user_data['last_name'] = sanitize_text_field($body['lastName']);
    }

    $user_id = wp_update_user($user_data);

    if (is_wp_error($user_id)) {
        return new WP_Error(
            'user_update_failed',
            __('Failed to update user...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/(?P<userId>[\d]+)', [
        'methods' => 'PATCH',
        'callback' => 'maaq__patch_user_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'EDIT_USERS');
        },
        'show_in_index' => false,
    ]);
});
