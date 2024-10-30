<?php

/**
Plugin Name: Maaq - Manage your WordPress websites
Plugin URI: https://maaq.app/
Description: An easy way to manage, monitor & maintain your WordPress websites! This plugin is needed to get the Maaq app working.
Version: 0.0.6
Requires at least: 6.0
Requires PHP: 8.0
Author: Maaq
Author URI: https://maaq.app
License: GPL-3.0
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: maaq-website-manager
 **/

if (!defined('WPINC')) {
    die;
}

// Define all global variables.
if (!defined('MAAQ_PLUGIN_VERSION')) {
    $plugin_data = get_file_data(__FILE__, ['Version' => 'Version'], false);
    $plugin_version = $plugin_data['Version'];
    define('MAAQ_PLUGIN_VERSION', $plugin_version);
}
if (!defined('MAAQ_PLUGIN_DIR_PATH')) {
    define('MAAQ_PLUGIN_DIR_PATH', plugin_dir_path(__DIR__));
}
if (!defined('MAAQ_PLUGIN_FILE_PATH')) {
    define('MAAQ_PLUGIN_FILE_PATH', plugin_dir_path(__FILE__));
}
if (!defined('MAAQ_PLUGIN_BASENAME')) {
    define('MAAQ_PLUGIN_BASENAME', plugin_basename(__FILE__));
}
if (!defined('MAAQ_API_URL')) {
    define('MAAQ_API_URL', 'https://api.maaq.app');
}

// Runs on plugin activation.
function maaq__activation()
{
    require_once 'activation.php';
    maaq__on_activation();
}
register_activation_hook(__FILE__, 'maaq__activation');

// Runs on plugin deactivation.
function maaq__deactivation()
{
    require_once 'deactivation.php';
    maaq__on_deactivation();
}
register_deactivation_hook(__FILE__, 'maaq__deactivation');

// Include the Action Scheduler.
require_once MAAQ_PLUGIN_FILE_PATH . 'vendor/woocommerce/action-scheduler/action-scheduler.php';
// Include the Composer autoloader.
require_once(MAAQ_PLUGIN_FILE_PATH . 'vendor/autoload.php');

// Recursively include all PHP files in a directory and its subdirectories.
function maaq__include_plugin_files_recursive($folder)
{
    // Get the list of items in the folder.
    $items = scandir($folder);

    foreach ($items as $item) {
        // Ignore special directory references.
        if ($item === '.' || $item === '..') {
            continue;
        }

        // Construct the full path to the current item.
        $item_path = $folder . '/' . $item;

        if (is_dir($item_path)) {
            // If it's a directory, recursively include files in the subdirectory.
            maaq__include_plugin_files_recursive($item_path);
        } elseif (is_file($item_path) && pathinfo($item_path, PATHINFO_EXTENSION) === 'php') {
            // If it's a PHP file, include it.
            require_once($item_path);
        }
    }
}

// Include all PHP files in the includes folder and its subfolders.
$includes_dir = MAAQ_PLUGIN_FILE_PATH . 'includes/';
maaq__include_plugin_files_recursive($includes_dir);
