<?php

namespace ETSimpleCrm\ValueObjects;

/**
 * Class APIResponse represents API response
 * @package ETSimpleCrm\ValueObjects
 */
class APIResponse
{
    /**
     * @var object
     */
    private $data;

    /**
     * Response code
     * @var string|int
     */
    private $code;

    /**
     * APIResponse constructor.
     * @param array $data
     * @param string|int $code
     */
    public function __construct(array $data, $code)
    {
        $this->data = $data;
        $this->code = $code;
    }

    /**
     * Return data from response as array
     * @return object
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return response status code
     * @return string|int
     */
    public function getCode()
    {
        return $this->code;
    }
}
