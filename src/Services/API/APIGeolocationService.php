<?php

namespace ETSimpleCrm\Services\API;

use ETSimpleCrm\Contracts\APIServiceInterface;
use ETSimpleCrm\Traits\SingletonTrait;
use ETSimpleCrm\ValueObjects\APIResponse;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class APIWorldTimeService
 * @package ETSimpleCrm\Services\API
 * @see https://ipapi.co/#api
 */
class APIGeolocationService implements APIServiceInterface
{
    use SingletonTrait;

    /**
     * Url base for the API calls
     */
    private const URL = 'https://ipapi.co';

    /**
     * APIGeolocationService constructor.
     */
    private function __construct()
    {
    }

    /**
     * Return timestamp as a current time in the APIResponse VO
     * @inheritDoc
     */
    public function get()
    {
        $apiUrl = sprintf(
            '%s/%s/json/',
            self::URL,
            $this->getUserIP()
        );

        $response = wp_remote_get(
            $apiUrl,
            [
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        if (is_wp_error($response)) {
            throw new \Exception(
                $response->get_error_message(),
                is_int($response->get_error_code())
                    ? $response->get_error_code() : 500 // Prevent string returned on curl error instead of code
            );
        }

        $code = wp_remote_retrieve_response_code($response);

        if (200 !== $code) {
            throw new \Exception(
                esc_html__(
                    'Something went wrong while getting the data from the World time API.',
                    'et-simple-crm'
                ),
                $code
            );
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);

        // IP address not found. Get local WP time
        if (isset($data['error']) && $data['error']) {
            throw new \Exception(
                esc_html__(
                    'Some error occurred while fetching the timezone or IP unknown.',
                    'et-simple-crm'
                ),
                404
            );
        }

        if (empty($data) || ! is_array($data) || ! isset($data['timezone'])) {
            throw new \Exception(
                esc_html__('Response json error!', 'et-simple-crm'),
                400
            );
        }

        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone($data['timezone']));

        return new APIResponse(
            ['dateTime' => $dateTime],
            200
        );
    }

    /**
     * Get current user IP
     * @return string
     */
    private function getUserIP()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }
}
