<?php

namespace ETSimpleCrm\Helpers;

use ETSimpleCrm\Traits\SingletonTrait;

/**
 * Class Config
 * @package ETSimpleCrm\Helpers
 */
class Config
{
    use SingletonTrait;

    /**
     * Holds plugin data array:
     * 'Name' (string) Name of the plugin. Should be unique.
     * 'Title' (string) Title of the plugin and link to the plugin's site (if set).
     * 'Description' (string) Plugin description.
     * 'Author' (string) Author's name.
     * 'AuthorURI' (string) Author's website address (if set).
     * 'Version' string) Plugin version.
     * 'TextDomain' (string) Plugin textdomain.
     * 'DomainPath' (string) Plugins relative directory path to .mo files.
     * 'Network' (bool) Whether the plugin can only be activated network-wide.
     * 'RequiresWP' (string) Minimum required version of WordPress.
     * 'RequiresPHP' (string) Minimum required version of PHP.
     * @var array
     */
    private $pluginData;

    /**
     * Config constructor.
     */
    private function __construct()
    {
        $this->pluginData = $this->getPluginData();
    }

    /**
     * Return the plugin slug
     * @return string
     */
    public function getPluginSlug()
    {
        return $this->pluginData['TextDomain'];
    }

    /**
     * Return assets directory path without the last backslash
     * @return string
     */
    public function getAssetsUrl()
    {
        return plugin_dir_url(ET_SIMPLE_CRM_FILE) . 'assets';
    }

    /**
     * Return plugin version
     * @return string
     */
    public function getPluginVersion()
    {
        return $this->pluginData['Version'];
    }

    /**
     * Return the plugin data from the main plugin file header
     * @return array
     */
    private function getPluginData()
    {
        if (! function_exists('get_plugin_data')) {
            require_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }

        return get_plugin_data(ET_SIMPLE_CRM_FILE);
    }
}
