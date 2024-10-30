<?php

function maaq__send_user_password_reset_endpoint($request)
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

    $reset_key = get_password_reset_key($user);

    if (is_wp_error($reset_key)) {
        return new WP_Error(
            'key_generation_failed',
            __('Failed to generate the password reset key...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $reset_url = esc_url_raw(add_query_arg(
        [
            'action' => 'rp',
            'key' => $reset_key,
            'login' => rawurlencode($user->user_login),
        ],
        wp_login_url()
    ));

    $subject = __('Password reset request', 'maaq-website-manager');
    /* translators: %s: reset url */
    $message = sprintf(__('Click the following link to reset your password: %s', 'maaq-website-manager'), $reset_url);

    $sent = wp_mail($user->user_email, $subject, $message);

    if (!$sent) {
        return new WP_Error(
            'email_failed',
            __('Failed to send the password reset email...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/(?P<userId>[\d]+)/send-password-reset', [
        'methods' => 'POST',
        'callback' => 'maaq__send_user_password_reset_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'EDIT_USERS');
        },
        'show_in_index' => false,
    ]);
});
