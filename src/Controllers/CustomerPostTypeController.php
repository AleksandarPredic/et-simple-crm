<?php

namespace ETSimpleCrm\Controllers;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use ETSimpleCrm\Contracts\ControllerInterface;
use ETSimpleCrm\Contracts\CustomPostTypeInterface;
use ETSimpleCrm\Helpers\Config;
use ETSimpleCrm\Traits\SingletonTrait;

/**
 * Class CustomerPostTypeController responsible for registering custom post type
 * @package ETSimpleCrm\Controllers
 */
class CustomerPostTypeController implements ControllerInterface, CustomPostTypeInterface
{
    use SingletonTrait;

    /**
     * Slug for rewrite rules. Used as prefix for all redirection links
     * @var string
     */
    private $rewriteslug;

    /**
     * Post type slug
     * @var string
     */
    private $slug;

    /**
     * CustomerPostTypeController constructor.
     */
    private function __construct()
    {
        $config = Config::getInstance();
        $this->rewriteslug = $config->getCustomerPostTypeRewriteSlug();
        $this->slug = $config->getCustomerPostTypeId();
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        add_action('init', [$this, 'register']);
    }

    /**
     * Register custom post type
     */
    public function register()
    {
        $args = [
            'labels' => $this->registerLabels(),
            'description' => esc_html__('Customers CRM', 'et-simple-crm'),
            'public' => false,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_in_rest' => false,
            'publicly_queryable' => false,
            'exclude_from_search' => false,
            'show_ui' => true,
            'query_var' => true,
            'rewrite' => [ 'slug' => $this->rewriteslug],
            'capability_type' => 'page',
            // allow only to admin
            'capabilities' => [
                'publish_posts' => 'manage_options',
                'edit_posts' => 'manage_options',
                'edit_others_posts' => 'manage_options',
                'delete_posts' => 'manage_options',
                'delete_others_posts' => 'manage_options',
                'read_private_posts' => 'manage_options',
                'edit_post' => 'manage_options',
                'delete_post' => 'manage_options',
                'read_post' => 'manage_options',
            ],
            'has_archive' => false,
            'hierarchical' => false,
            'menu_position' => 16,
            'supports' => [ 'title', 'editor', 'custom-fields' ],
            'taxonomies' => [],
            'menu_icon' => 'dashicons-groups'
        ];

        register_post_type($this->slug, $args);
    }

    /**
     * Register post type labels
     * @return array
     * Build Labels
     */
    private function registerLabels()
    {
        return [
            'name' => esc_html_x('Customers', 'post type general name', 'et-simple-crm'),
            'singular_name' => esc_html_x('Customers', 'post type singular name', 'et-simple-crm'),
            'menu_name' => esc_html_x('Customers', 'admin menu', 'et-simple-crm'),
            'name_admin_bar' => esc_html_x('Customers', 'add new on admin bar', 'et-simple-crm'),
            'add_new' => esc_html_x('Add New', 'Customer post type', 'et-simple-crm'),
            'add_new_item' => esc_html__('Add New Customer', 'et-simple-crm'),
            'new_item' => esc_html__('New Customer', 'et-simple-crm'),
            'edit_item' => esc_html__('Edit Customer', 'et-simple-crm'),
            'view_item' => esc_html__('View Customer', 'et-simple-crm'),
            'all_items' => esc_html__('All Customers', 'et-simple-crm'),
            'search_items' => esc_html__('Search Customers', 'et-simple-crm'),
            'parent_item_colon' => esc_html__('Parent Customers:', 'et-simple-crm'),
            'not_found' => esc_html__('No Customers found.', 'et-simple-crm'),
            'not_found_in_trash' => esc_html__('No Customers found in Trash.', 'et-simple-crm')
        ];
    }
}
