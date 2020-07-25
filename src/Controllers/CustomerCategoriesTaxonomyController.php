<?php

namespace ETSimpleCrm\Controllers;

use ETSimpleCrm\Contracts\ControllerInterface;
use ETSimpleCrm\Contracts\CustomPostTypeInterface;
use ETSimpleCrm\Helpers\Config;
use ETSimpleCrm\Traits\SingletonTrait;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class CustomerCategoriesTaxonomyController
 * @package ETSimpleCrm\Controllers
 */
class CustomerCategoriesTaxonomyController implements ControllerInterface, CustomPostTypeInterface
{
    use SingletonTrait;

    /**
     * Slug for rewrite rules. Used as prefix for all redirection links
     * @var string
     */
    private $rewriteslug;

    /**
     * Taxonomy slug
     * @var string
     */
    private $slug;

    /**
     * Post type id to attach the taxonomy
     * @var string
     */
    private $postType;

    /**
     * CustomerCategoriesTaxonomyController constructor.
     */
    private function __construct()
    {
        $config = Config::getInstance();
        $this->rewriteslug = $config->getCustomerCategoriesTaxonomyRewriteSlug();
        $this->slug = $config->getCustomerCategoriesTaxonomyId();
        $this->postType = $config->getCustomerPostTypeId();
    }

    /**
     * Add hooks
     */
    public function init()
    {
        add_action('init', [$this, 'register']);
    }

    /**
     * Register taxonomies
     */
    public function register()
    {
        // Add new taxonomy, NOT hierarchical (like categories)
        $labels = [
            'name' => esc_html_x('Customer categories', 'taxonomy general name', 'et-simple-crm'),
            'singular_name' => esc_html_x('Customer category', 'taxonomy singular name', 'et-simple-crm'),
            'search_items' => esc_html__('Search Customer categories', 'et-simple-crm'),
            'popular_items' => esc_html__('Popular Customer categories', 'et-simple-crm'),
            'all_items' => esc_html__('All Customer categories', 'et-simple-crm'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Customer category', 'et-simple-crm'),
            'update_item' => esc_html__('Update Customer category', 'et-simple-crm'),
            'add_new_item' => esc_html__('Add New Customer category', 'et-simple-crm'),
            'new_item_name' => esc_html__('New Customer category Name', 'et-simple-crm'),
            'separate_items_with_commas' => esc_html__('Separate Customer categories with commas', 'et-simple-crm'),
            'add_or_remove_items' => esc_html__('Add or remove Customer categories', 'et-simple-crm'),
            'choose_from_most_used' => esc_html__('Choose from the most used Customer categories', 'et-simple-crm'),
            'not_found' => esc_html__('No Customer categories found.', 'et-simple-crm'),
            'menu_name' => esc_html__('Customer categories', 'et-simple-crm'),
        ];

        $args = [
            'hierarchical' => false,
            'labels' => $labels,
            'show_in_menu' => true,
            'show_in_nav_menus' => false,
            'show_ui' => true,
            'show_in_quick_edit' => true,
            'show_admin_column' => true,
            // allow only to admin
            'capabilities' => [
                'manage_terms' => 'manage_options',
                'edit_terms' => 'manage_options',
                'delete_terms' => 'manage_options',
                'assign_terms' => 'manage_options',
            ],
            'publicly_queryable' => false,
            'query_var' => true,
            'rewrite' => [ 'slug' => $this->rewriteslug ],
        ];

        register_taxonomy($this->slug, $this->postType, $args);
    }
}
