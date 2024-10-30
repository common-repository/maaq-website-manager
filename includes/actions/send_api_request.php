<?php

function maaq__send_api_request($endpoint, $method, $data)
{
    if ($method != 'POST' && $method != 'PUT' && $method != 'PATCH') {
        return new WP_Error(
            'method_not_supported',
            __('This send api request method is not supported...', 'maaq-website-manager'),
            [
                'status' => 405
            ]
        );
    }

    $token = [
        'secretToken' => get_option('maaq_secret_token'),
    ];

    $body = array_merge($data, $token);

    wp_remote_post(MAAQ_API_URL . $endpoint, [
        'method' => $method,
        'body' => wp_json_encode($body),
        'headers' => [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ],
    ]);
}
add_action('maaq_send_api_request', 'maaq__send_api_request', 10, 3);
