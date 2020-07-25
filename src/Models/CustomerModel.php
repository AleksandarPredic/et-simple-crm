<?php

namespace ETSimpleCrm\Models;

// TODO: Make interface
use ETSimpleCrm\Contracts\CustomerModelInterface;
use ETSimpleCrm\Helpers\Config;
use ETSimpleCrm\ValueObjects\Customer;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class CustomerModel
 * @package ETSimpleCrm\Models
 */
class CustomerModel implements CustomerModelInterface
{
    /**
     * Post type customer
     * @var string
     */
    private $postType;

    /**
     * CustomerModel constructor.
     */
    public function __construct()
    {
        $config = Config::getInstance();
        $this->postType = $config->getCustomerPostTypeId();
    }

    /**
     * @inheritDoc
     */
    public function get($postId)
    {
        // TODO: Not needed at the moment as we don't show this post type anywhere
    }

    /**
     * @inheritDoc
     */
    public function save(Customer $customer, $postId = null)
    {
        $meta = ['phone', 'email', 'budget', 'time'];
        $postMeta = [];
        foreach ($meta as $field) {
            if (! property_exists($customer, $field)) {
                continue;
            }

            $postMeta[$field] = $customer->$field;
        }

        $postarr = [
            'post_status' => 'private',
            'post_type' => $this->postType,
            'post_title' => $customer->name,
            'post_content' => $customer->message,
            'meta_input' => $postMeta,
        ];

        if ($postId) {
            $postarr['ID'] = intval($postId);
        }

        $result = wp_insert_post($postarr, true);

        if (is_wp_error($result)) {
            throw new \Exception(
                $result->get_error_message(),
                is_int($result->get_error_code())
                    ? $result->get_error_code() : 500 // Prevent string returned in some cases
            );
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function checkIfEmailExists($email)
    {
        // Check if there is such customer with the same email
        $query = new \WP_Query(
            [
                'post_status' => 'any',
                'posts_per_page' => 1,
                'fields' => 'ids',
                'post_type' => $this->postType,
                'meta_key' => 'email',
                'meta_value' => $email,
                'meta_compare' => '=',
            ]
        );

        if ($query->have_posts()) {
            return true;
        }

        return false;
    }
}
