<?php

namespace ETSimpleCrm\Contracts;

use ETSimpleCrm\ValueObjects\APIResponse;

/**
 * Interface APIServiceInterface
 * @package ETSimpleCrm\Contracts
 */
interface APIServiceInterface
{
    /**
     * Preform get request and return data or throw an Exception
     * @throws \Exception
     * @return APIResponse
     */
    public function get();
}
