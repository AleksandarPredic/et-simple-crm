<?php
/**
 * Plugin Name:       Simple CRM
 * Plugin URI:        https://github.com/AleksandarPredic/et-simple-crm
 * Description:       A simple CRM system that will collect customer data and build their profiles.
 * Version:           0.0.1
 * Requires at least: 5.4
 * Requires PHP:      7.2
 * Author:            Aleksandar Predic
 * Author URI:        https://github.com/AleksandarPredic/et-simple-crm
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       et-simple-crm
 * Domain Path:       /languages
 */

use ETSimpleCrm\Controllers\PluginActivationController;
use ETSimpleCrm\Controllers\PluginDeactivationController;
use ETSimpleCrm\ETSimpleCrm;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

require plugin_dir_path(__FILE__) . 'vendor/autoload.php';

define("ET_SIMPLE_CRM_DIR", plugin_dir_path(__FILE__));
define("ET_SIMPLE_CRM_FILE", __FILE__);

/**
 * Register activation hook
 */
register_activation_hook(__FILE__, [PluginActivationController::getInstance(), 'init']);
register_deactivation_hook(__FILE__, [PluginDeactivationController::getInstance(), 'init']);

add_action('plugins_loaded', function () {
    ETSimpleCrm::getInstance()->setInstances();
}, 20);
