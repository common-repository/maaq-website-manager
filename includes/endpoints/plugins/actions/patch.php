<?php

function maaq__plugins_actions_endpoint($request)
{
    $action = sanitize_text_field($request['action']);
    $plugin = sanitize_text_field($request['plugin']);

    require_once(ABSPATH . 'wp-admin/includes/plugin.php');
    require_once(ABSPATH . 'wp-admin/includes/update.php');
    require_once(ABSPATH . 'wp-admin/includes/class-wp-upgrader.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/misc.php');

    switch ($action) {
        case 'activate':
            return maaq__activate_plugin($plugin);
        case 'deactivate':
            return maaq__deactivate_plugin($plugin);
        case 'update':
            return maaq__update_plugin($plugin);
        case 'delete':
            return maaq__delete_plugin($plugin);
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
    register_rest_route(get_option('maaq_secret_path'), 'plugins/actions', [
        'methods' => 'PATCH',
        'callback' => 'maaq__plugins_actions_endpoint',
        'permission_callback' => 'maaq__server_permission_callback',
        'show_in_index' => false,
    ]);
});

function maaq__activate_plugin($plugin)
{
    if (is_plugin_active($plugin)) {
        return new WP_Error(
            'bad_request',
            __('Plugin is already activated...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    activate_plugin($plugin);

    if (!is_plugin_active($plugin)) {
        return new WP_Error(
            'bad_request',
            __('Plugin activation failed...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

function maaq__deactivate_plugin($plugin)
{
    if (!is_plugin_active($plugin)) {
        return new WP_Error(
            'bad_request',
            __('Plugin is already deactivated...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    deactivate_plugins($plugin);

    if (is_plugin_active($plugin)) {
        return new WP_Error(
            'bad_request',
            __('Plugin deactivation failed...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}

function maaq__update_plugin($plugin)
{
    $plugin_info = get_plugin_data(WP_PLUGIN_DIR . '/' . $plugin);

    $was_active = is_plugin_active($plugin);

    $plugin_updates = get_plugin_updates();

    if (!isset($plugin_updates[$plugin])) {
        return new WP_Error(
            'bad_request',
            __('No update available...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $new_version = $plugin_updates[$plugin]->update->new_version;

    if (version_compare($plugin_info['Version'], $new_version, '>=')) {
        return new WP_Error(
            'bad_request',
            __('No update available or error in obtaining version...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $upgrader = new Plugin_Upgrader();
    $update = $upgrader->upgrade($plugin);

    if (is_wp_error($update)) {
        return new WP_Error(
            'bad_request',
            __('Something went wrong with updating...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    if ($was_active) {
        activate_plugin($plugin, '', false, true);

        if (!is_plugin_active($plugin)) {
            return new WP_Error(
                'bad_request',
                __('Plugin has been updated but deactivated...', 'maaq-website-manager'),
                [
                    'status' => 400
                ]
            );
        }
    }

    return wp_send_json([], 201);
}

function maaq__delete_plugin($plugin)
{
    if (is_plugin_active($plugin)) {
        return new WP_Error(
            'bad_request',
            __('Plugin is active. Please first deactivate it...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    $deleted = delete_plugins([$plugin]);

    if (is_wp_error($deleted)) {
        return new WP_Error(
            'bad_request',
            __('Failed to delete the plugin...', 'maaq-website-manager'),
            [
                'status' => 400
            ]
        );
    }

    return wp_send_json([], 201);
}
