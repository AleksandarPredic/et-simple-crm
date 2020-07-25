<?php

namespace ETSimpleCrm\Helpers;

use ETSimpleCrm\Traits\SingletonTrait;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class LoggerHelper
 * @package ETSimpleCrm\Helpers
 */
class LoggerHelper
{
    use SingletonTrait;

    /**
     * Logger instance
     * @var Logger
     */
    private $logger;

    /**
     * If WP debug is on
     * @var bool
     */
    private $debugLogState;

    /**
     * Plugin slug
     * @var string
     */
    private $pluginSlug;

    /**
     * LoggerHelper constructor.
     */
    public function __construct()
    {
        $config = Config::getInstance();
        $this->pluginSlug = $config->getPluginSlug();
        $this->logger = new Logger($this->pluginSlug);
        $this->debugLogState = $config->getDebugLogState();
    }

    /**
     * Log messages
     * @param string $message
     * @param int $type
     * @throws \Exception
     */
    public function log($message, $type)
    {
        if (! $this->debugLogState) {
            return;
        }

        $upload_dir = wp_upload_dir();
        $logPath = sprintf(
            '%s/%s/log.log',
            $upload_dir['basedir'],
            $this->pluginSlug
        );

        try {
            $this->logger->pushHandler(new StreamHandler($logPath, $type));

            switch ($type) {
                case $this->typeError():
                    $this->logger->error($message);

                    break;

                case $this->typeWarning():
                    $this->logger->warning($message);

                    break;

                case $this->typeInfo():
                    $this->logger->info($message);

                    break;

                default:
                    $this->logger->debug($message);
            }
        } catch (\Exception $error) {
        }
    }

    /**
     * Log exceptions.
     * @param \Exception $exception Error message
     * @param null|int $type Default type is error
     */
    public function logException(\Exception $exception, $type = null)
    {
        if (! $this->debugLogState) {
            return;
        }

        try {
            $this->log(
                sprintf(
                    'Message: %s. Code: %s. Trace: %s',
                    $exception->getMessage(),
                    $exception->getCode(),
                    $exception->getTraceAsString()
                ),
                $type ? $type : $this->typeError()
            );
        } catch (\Exception $e) {
        }
    }

    /**
     * Return type error
     * @return int
     */
    public function typeError()
    {
        return Logger::ERROR;
    }

    /**
     * Return type warning
     * @return int
     */
    public function typeWarning()
    {
        return Logger::WARNING;
    }

    /**
     * Return type info
     * @return int
     */
    public function typeInfo()
    {
        return Logger::INFO;
    }
}
