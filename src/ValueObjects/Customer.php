<?php

namespace ETSimpleCrm\ValueObjects;

/**
 * Class Customer
 * @package ETSimpleCrm\ValueObjects
 */
class Customer
{
    /**
     * Customer name
     * @var string
     */
    public $name;

    /**
     * Customer phone
     * @var string
     */
    public $phone;

    /**
     * Customer email
     * @var string
     */
    public $email;

    /**
     * Customer budget
     * @var int
     */
    public $budget;

    /**
     * Customer message
     * @var string
     */
    public $message;

    /**
     * Customer time
     * @var string
     */
    public $time;

    /**
     * Customer constructor.
     * @param string $name
     * @param string $phone
     * @param string $email
     * @param int $budget
     * @param string $message
     * @param string $time
     */
    public function __construct($name, $phone, $email, $budget, $message, $time)
    {
        $this->name = sanitize_text_field($name);
        $this->phone = sanitize_text_field($phone);
        $this->email = sanitize_text_field($email);
        $this->budget = intval($budget);
        $this->message = wp_kses_post($message);
        $this->time = sanitize_text_field($time);
    }
}
