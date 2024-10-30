<?php

function maaq__extract_bearer_token($request)
{
    // Check if there is a bearer token.
    $authorization_header = $request->get_header('authorization');

    if (!$authorization_header) {
        return new WP_Error(
            'bad_request',
            __('No Authorization header provided...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    // Check if the Authorization header starts with "Bearer ".
    if (strpos($authorization_header, 'Bearer ') !== 0) {
        return new WP_Error(
            'bad_request',
            __("It doesn't look like there is a Bearer token here...", 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    // Extract and return the token part (excluding "Bearer ").
    $token = sanitize_text_field(wp_unslash(substr($authorization_header, 7)));

    if (empty($token)) {
        return new WP_Error(
            'bad_request',
            __('There was no Bearer token found...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return $token;
}
