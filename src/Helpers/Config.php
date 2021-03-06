<?php

namespace ETSimpleCrm\Helpers;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use ETSimpleCrm\Traits\SingletonTrait;

/**
 * Class Config
 * @package ETSimpleCrm\Helpers
 */
class Config
{
    use SingletonTrait;

    /**
     * Plugin short slug
     * @var string
     */
    private const PLUGIN_SHORT_SLUG = 'etscrm';

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
     * Return the plugin slug with underscore instead of dash between words
     * @return string
     */
    public function getPluginSlugWithUnderscore()
    {
        return $this->replaceDashWithUnderscore($this->getPluginSlug());
    }

    /**
     * Return plugin short slug
     * @return string
     */
    public function getPluginShortSlug()
    {
        return self::PLUGIN_SHORT_SLUG;
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
     * Return if the debug is on of off
     * @return bool
     */
    public function getDebugLogState()
    {
        return defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;
    }

    // Custom taxonomies and post types

    /**
     * Return Customer post type id
     * @return string
     */
    public function getCustomerPostTypeId()
    {
        return sprintf('%s-customer', self::PLUGIN_SHORT_SLUG);
    }

    /**
     * Get customer post type rewrite slug
     * @return string
     */
    public function getCustomerPostTypeRewriteSlug()
    {
        return apply_filters(
            sprintf(
                '%s_%s_post_type_rewrite_slug',
                $this->getPluginSlugWithUnderscore(),
                $this->replaceDashWithUnderscore($this->getCustomerPostTypeId())
            ),
            'customer'
        );
    }

    /**
     * Return Customer tags taxonomy id
     * @return string
     */
    public function getCustomerTagsTaxonomyId()
    {
        return sprintf('%s-customer-tags', self::PLUGIN_SHORT_SLUG);
    }

    /**
     * Get customer tags taxonomy rewrite slug
     * @return string
     */
    public function getCustomerTagsTaxonomyRewriteSlug()
    {
        return apply_filters(
            sprintf(
                '%s_%s_taxonomy_rewrite_slug',
                $this->getPluginSlugWithUnderscore(),
                $this->replaceDashWithUnderscore($this->getCustomerTagsTaxonomyId())
            ),
            'customer-tags'
        );
    }

    /**
     * Return Customer categories taxonomy id
     * @return string
     */
    public function getCustomerCategoriesTaxonomyId()
    {
        return sprintf('%s-customer-categories', self::PLUGIN_SHORT_SLUG);
    }

    /**
     * Get customer categories taxonomy rewrite slug
     * @return string
     */
    public function getCustomerCategoriesTaxonomyRewriteSlug()
    {
        return apply_filters(
            sprintf(
                '%s_%s_taxonomy_rewrite_slug',
                $this->getPluginSlugWithUnderscore(),
                $this->replaceDashWithUnderscore($this->getCustomerTagsTaxonomyId())
            ),
            'customer-categories'
        );
    }

    // Other private methods

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

    /**
     * Replace dash with underscore in string
     * @param string $string
     * @return string
     */
    private function replaceDashWithUnderscore($string)
    {
        return str_replace('-', '_', $string);
    }
}
