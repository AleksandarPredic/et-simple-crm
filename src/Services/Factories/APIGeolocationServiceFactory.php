<?php

namespace ETSimpleCrm\Services\Factories;

use ETSimpleCrm\Contracts\APIServiceInterface;
use ETSimpleCrm\Services\API\APIGeolocationService;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class APIWorldTimeServiceFactory
 * @package ETSimpleCrm\Services\Factories
 */
class APIGeolocationServiceFactory
{
    /**
     * Return class instance
     *
     * @return APIServiceInterface
     */
    public static function make()
    {
        return APIGeolocationService::getInstance();
    }
}
