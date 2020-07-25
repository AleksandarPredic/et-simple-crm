<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 */

namespace ETSimpleCrm\I18n;

use ETSimpleCrm\Helpers\Config;
use ETSimpleCrm\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (! defined('ABSPATH')) {
    exit('Direct script access denied.');
}

/**
 * Class I18n
 * @package ETSimpleCrm\I18n
 */
class I18n
{
    use SingletonTrait;

    /**
     * Plugin text domain specified for this plugin.
     * @var string
     */
    private $text_domain;

    /**
     * Plugin basename.
     * @var string
     */
    private $plugin_basename;

    /**
     * Localization constructor.
     */
    private function __construct()
    {
        $this->text_domain = Config::getInstance()->getPluginSlug();
        $this->plugin_basename = basename(ET_SIMPLE_CRM_DIR);
        add_action('init', [$this, 'loadPluginTextdomain']);
    }

    /**
     * Load the plugin text domain for translation.
     */
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            $this->text_domain,
            $this->plugin_basename . '/languages',
            $this->plugin_basename . '/languages'
        );
    }
}
