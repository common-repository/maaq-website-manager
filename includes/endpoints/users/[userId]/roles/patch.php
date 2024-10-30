<?php

function maaq__change_user_role_endpoint($request)
{
    $user_id = intval($request['userId']);

    $body = json_decode($request->get_body(), true);
    $new_role = sanitize_text_field($body['newRole']);

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

    if (in_array('administrator', $user->roles, true)) {
        $administrators = get_users(['role' => 'administrator']);

        if (count($administrators) === 1) {
            return new WP_Error(
                'bad_request',
                __('Cannot demote the last administrator...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
        }
    }

    $updated = wp_update_user([
        'ID' => $user_id,
        'role' => $new_role
    ]);

    if (is_wp_error($updated)) {
        return new WP_Error(
            'update_failed',
            __('Failed to update the user role...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/(?P<userId>[\d]+)/roles', [
        'methods' => 'PATCH',
        'callback' => 'maaq__change_user_role_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'EDIT_USERS');
        },
        'show_in_index' => false,
    ]);
});
