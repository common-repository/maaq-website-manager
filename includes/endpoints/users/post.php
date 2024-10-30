<?php

function maaq__create_user_endpoint($request)
{
    $body = json_decode($request->get_body(), true);

    $email = sanitize_email($body['email']);

    if (!is_email($email)) {
        return new WP_Error(
            'missing_fields',
            __('Email is a required field...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    if (email_exists($email)) {
        return new WP_Error(
            'email_exists',
            __('User with this email already exists...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $login = sanitize_text_field($body['login'] ?? $email);
    $first_name = sanitize_text_field($body['firstName'] ?? null);
    $last_name = sanitize_text_field($body['lastName'] ?? null);
    $role = sanitize_text_field($body['userRole'] ?? get_option('default_role'));

    $user_data = [
        'user_email' => $email,
        'user_login' => $login,
        'user_pass' => wp_hash_password(wp_generate_password(16, true, true)),
        'first_name' => $first_name,
        'last_name' => $last_name,
        'role' => $role
    ];

    $user_id = wp_insert_user($user_data);

    if (is_wp_error($user_id)) {
        return new WP_Error(
            'bad_request',
            __('Failed to create a new user...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $new_user_notification = filter_var($body['newUserNotification'] ?? true, FILTER_VALIDATE_BOOLEAN);
    if ($new_user_notification) {
        wp_send_new_user_notifications($user_id, 'user');
    }

    return wp_send_json(
        [
            'user_id' => $user_id
        ],
        201
    );
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users', [
        'methods' => 'POST',
        'callback' => 'maaq__create_user_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'EDIT_USERS');
        },
        'show_in_index' => false,
    ]);
});
