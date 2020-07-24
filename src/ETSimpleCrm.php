<?php

namespace ETSimpleCrm;

use ETSimpleCrm\Controllers\ShortcodeController;
use ETSimpleCrm\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class ETSimpleCrm
 * @package ETSimpleCrm
 */
class ETSimpleCrm
{
    use SingletonTrait;

    /**
     * ETSimpleCrm constructor.
     */
    private function __construct()
    {
    }

    /**
     * Set plugin required functionality
     */
    public function setInstances()
    {
        // Global
        ShortcodeController::getInstance()->init();

        // Admin
        if (is_admin()) {
        }

        // Frontend

        if (wp_doing_ajax()) {
            return;
        }

        // Frontend not ajax
    }
}
