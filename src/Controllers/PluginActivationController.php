<?php

namespace ETSimpleCrm\Controllers;

use ETSimpleCrm\Contracts\ControllerInterface;
use ETSimpleCrm\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class PluginActivation responsible for operations on plugin activation
 * @package ETSimpleCrm\Controllers
 */
class PluginActivationController implements ControllerInterface
{
    use SingletonTrait;

    /**
     * Logic for the plugin activation
     */
    public function init()
    {
        /**
         * Flush rewrite rules for custom post types
         */
        CustomerPostTypeController::getInstance()->register();
        CustomerTagsTaxonomyController::getInstance()->register();
        CustomerCategoriesTaxonomyController::getInstance()->register();
        flush_rewrite_rules();
    }
}
