<?php

namespace ETSimpleCrm\ValueObjects;

/**
 * Class Form
 * @package ETSimpleCrm\ValueObjects
 */
class Form
{
    /**
     * Fields that should be required in the form
     * @var array
     */
    public const REQUIRED_FIELDS = ['name', 'phone', 'email', 'budget'];

    // Keep all properties lowercase to match with the shortcode attributes

    // Values

    public $name = '';
    public $phone = '';
    public $email = '';
    public $budget = 0;
    public $message = '';

    // Rows & cols
    public $message_rows = '10';
    public $message_cols = '30';

    // Labels

    public $label_name;
    public $label_phone;
    public $label_email;
    public $label_budget;
    public $label_message;
    public $label_button;

    // Max length
    public $maxlength_name;
    public $maxlength_phone;
    public $maxlength_email;
    public $maxlength_budget;
    public $maxlength_message;

    // Can't be overridden by shortcode attributes
    public $time = '';

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->label_name = esc_html__('Name', 'et-simple-crm');
        $this->label_phone = esc_html__('Phone Number', 'et-simple-crm');
        $this->label_email = esc_html__('Email Address', 'et-simple-crm');
        $this->label_budget = esc_html__('Desired Budget in USD', 'et-simple-crm');
        $this->label_message = esc_html__('Message', 'et-simple-crm');
        $this->label_button = esc_html__('Send', 'et-simple-crm');
    }
}
