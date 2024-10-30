<?php

function maaq__has_permission($jwt, $permission)
{
    $body = [
        'permission' => $permission,
        'secretToken' => get_option('maaq_secret_token'),
    ];

    $parameters = http_build_query($body);

    $request_url = MAAQ_API_URL . "/websites/permissions/verify?$parameters";

    $response = wp_remote_get($request_url, [
        'headers' => [
            'Authorization' => "Bearer $jwt",
            'Accept' => 'application/json',
        ],
    ]);

    $response_code = wp_remote_retrieve_response_code($response);

    if (is_wp_error($response) || $response_code !== 200) {
        return new WP_Error(
            'bad_request',
            __('Something went wrong with checking the permissions of the user...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $response_body = wp_remote_retrieve_body($response);
    $data = json_decode($response_body);

    if (!isset($data->hasPermission) || $data->hasPermission !== true) {
        return new WP_Error(
            'forbidden',
            __('The user does not have the correct permissions for this action...', 'maaq-website-manager'),
            [
                'status' => 403
            ]
        );
    }
}
