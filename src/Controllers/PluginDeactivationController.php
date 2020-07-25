<?php

namespace ETSimpleCrm\Controllers;

use ETSimpleCrm\Contracts\ControllerInterface;
use ETSimpleCrm\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class PluginDeactivationController
 * @package ETSimpleCrm\Controllers
 */
class PluginDeactivationController implements ControllerInterface
{
    use SingletonTrait;

    /**
     * Logic for plugin uninstall
     */
    public function init()
    {
        // TODO: Still to do, but not until we decide if we are going to keep the data on plugin uninstall
        flush_rewrite_rules();
    }
}
