<?php

function maaq__delete_user_endpoint($request)
{
    $user_id = intval($request['userId']);

    if ($user_id == get_option('maaq_sso_user_id')) {
        return new WP_Error(
            'bad_request',
            __('This user is being used for single sign-on...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

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
                __('Cannot delete the last administrator...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
        }
    }

    require_once(ABSPATH . 'wp-admin/includes/user.php');
    $deleted = wp_delete_user($user_id);

    if (!$deleted) {
        return new WP_Error(
            'bad_request',
            __('Failed to delete the user...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'users/(?P<userId>[\d]+)', [
        'methods' => 'DELETE',
        'callback' => 'maaq__delete_user_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'EDIT_USERS');
        },
        'show_in_index' => false,
    ]);
});
