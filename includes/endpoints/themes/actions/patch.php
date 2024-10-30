<?php

function maaq__themes_actions_endpoint($request)
{
    $action = sanitize_text_field($request['action']);
    $stylesheet = sanitize_text_field($request['stylesheet']);

    require_once(ABSPATH . 'wp-admin/includes/theme.php');
    require_once(ABSPATH . 'wp-admin/includes/update.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/misc.php');

    switch ($action) {
        case 'switch':
            return maaq__switch_theme($stylesheet);
        case 'update':
            return maaq__update_theme($stylesheet);
        case 'delete':
            return maaq__delete_theme($stylesheet);
        default:
            return new WP_Error(
                'bad_request',
                __('Invalid action...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
    }
}

add_action('rest_api_init', function () {
    register_rest_route(get_option('maaq_secret_path'), 'themes/actions', [
        'methods' => 'PATCH',
        'callback' => 'maaq__themes_actions_endpoint',
        'permission_callback' => 'maaq__server_permission_callback',
        'show_in_index' => false,
    ]);
});

function maaq__switch_theme($stylesheet)
{
    $theme = wp_get_theme($stylesheet);

    if (!$theme->exists()) {
        return new WP_Error(
            'bad_request',
            __('Theme does not exist...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    if ($theme->get_stylesheet() == get_option('stylesheet')) {
        return new WP_Error(
            'bad_request',
            __('Theme is already activated...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    switch_theme($stylesheet);

    if ($theme->get_stylesheet() != get_option('stylesheet')) {
        return new WP_Error(
            'bad_request',
            __('Theme activation failed...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

function maaq__update_theme($stylesheet)
{
    $theme_info = wp_get_theme($stylesheet);

    $theme_updates = get_theme_updates();

    if (!isset($theme_updates[$stylesheet])) {
        return new WP_Error(
            'bad_request',
            __('No update available...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $new_version = $theme_updates[$stylesheet]->update['new_version'];

    if (version_compare($theme_info->get('Version'), $new_version, '>=')) {
        return new WP_Error(
            'bad_request',
            __('No update available or error in obtaining version...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $upgrader = new Theme_Upgrader();
    $update = $upgrader->upgrade($stylesheet);

    if (is_wp_error($update)) {
        return new WP_Error(
            'bad_request',
            __('Something went wrong with updating...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

function maaq__delete_theme($stylesheet)
{
    $theme = wp_get_theme($stylesheet);

    if (!$theme->exists()) {
        return new WP_Error(
            'bad_request',
            __('Theme does not exist...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    if ($theme->get_stylesheet() == get_option('stylesheet')) {
        return new WP_Error(
            'bad_request',
            __('Theme is active. Please first deactivate it...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $deleted = delete_theme($stylesheet);

    if (!$deleted) {
        return new WP_Error(
            'bad_request',
            __('Failed to delete the theme...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}
