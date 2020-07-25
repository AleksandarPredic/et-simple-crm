<?php

namespace ETSimpleCrm\Contracts;

use ETSimpleCrm\ValueObjects\Customer;

/**
 * Interface CustomerModelInterface
 * @package ETSimpleCrm\Contracts
 */
interface CustomerModelInterface
{
    /**
     * Get post meta for the post type
     * @param int $postId
     * @return Customer
     */
    public function get($postId);

    /**
     * Save new or update existing post
     * @param Customer $customer
     * @param int|null $postId
     * @throws \Exception
     * @return int The post ID on success.
     */
    public function save(Customer $customer, $postId = null);

    /**
     * Check if the email exists in the database
     * @param string $email
     * @return bool
     */
    public function checkIfEmailExists($email);
}
