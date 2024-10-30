<?php

function maaq__summary_endpoint()
{
    $user_counts = [];

    $counts = count_users();
    if (isset($counts['avail_roles']) && is_array($counts['avail_roles'])) {
        foreach ($counts['avail_roles'] as $role => $count) {
            if ($role !== 'none') {
                $user_counts[$role] = $count;
            }
        }
    }

    $data = [
        'counts' => [
            'users' => $user_counts,
        ],
    ];

    return wp_send_json($data, 200);
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'summary', [
        'methods' => 'GET',
        'callback' => 'maaq__summary_endpoint',
        'permission_callback' => function ($request) {
            return maaq__jwt_permission_callback($request, 'READ');
        },
        'show_in_index' => false,
    ]);
});
