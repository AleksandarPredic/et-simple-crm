<?php

namespace ETSimpleCrm\Controllers;

use ETSimpleCrm\Contracts\APIServiceInterface;
use ETSimpleCrm\Contracts\ControllerInterface;
use ETSimpleCrm\Helpers\Config;
use ETSimpleCrm\Services\Factories\APIGeolocationServiceFactory;
use ETSimpleCrm\Traits\SingletonTrait;
use ETSimpleCrm\ValueObjects\Form;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * Class ShortcodeController responsible for the shortcode functionality
 * @package ETSimpleCrm\Controllers
 */
class ShortcodeController implements ControllerInterface
{
    use SingletonTrait;

    /**
     * Shortcode tag
     */
    public const TAG = 'et-simple-crm-form';

    /**
     * Ajax action hook on form submit
     * @var string
     */
    private $ajaxSubmitActionHook;

    /**
     * Ajax action hook to get time on frontend
     * @var string
     */
    private $ajaxTimeActionHook;

    /**
     * Form nonce name
     * @var string
     */
    private $nonceName;

    /**
     * Plugin folder slug
     * @var string
     */
    private $pluginSlug;

    /**
     * Assets url
     * @var string
     */
    private $assetsUrl;

    /**
     * Plugin version
     * @var string
     */
    private $pluginVersion;

    /**
     * Time API
     * @var APIServiceInterface
     */
    private $timeApi;

    /**
     * ShortcodeController constructor.
     */
    public function __construct()
    {
        $config = Config::getInstance();
        $this->pluginSlug = $config->getPluginSlug();
        $this->assetsUrl = $config->getAssetsUrl();
        $this->pluginVersion = $config->getPluginVersion();
        $nonceName = str_replace('-', '_', $this->pluginSlug);
        $this->ajaxSubmitActionHook = sprintf('%s_formSubmit', $nonceName);
        $this->ajaxTimeActionHook = sprintf('%s_getCurrentTimeFromAPI', $nonceName);
        $this->nonceName = sprintf('%s_formSubmit', $nonceName);
        $this->timeApi = APIGeolocationServiceFactory::make();
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        add_shortcode(self::TAG, [ $this, 'render' ]);
        add_action('wp_enqueue_scripts', [ $this, 'scripts']);
        add_action(sprintf('wp_ajax_%s', $this->ajaxSubmitActionHook), [$this, 'formSubmit']);
        add_action(sprintf('wp_ajax_nopriv_%s', $this->ajaxSubmitActionHook), [$this, 'formSubmit']);
        add_action(sprintf('wp_ajax_%s', $this->ajaxTimeActionHook), [$this, 'getCurrentTimeFromAPI']);
        add_action(sprintf('wp_ajax_nopriv_%s', $this->ajaxTimeActionHook), [$this, 'getCurrentTimeFromAPI']);
    }

    /**
     * Render shortcode
     * @param array $atts
     * @param string $content
     * @return string
     */
    public function render($atts, $content)
    {
        $form = new Form();
        $form->message = $content;
        $form = $this->overrideAtts($atts, $form);

        $maxLengthNotice = sprintf(
            '<small>%s</small>',
            esc_html__('Maximum characters allowed: %s', 'et-simple-crm')
        );

        // TODO: add captcha

        // TODO: Fetch current date via 3rd party API
        return sprintf(
            '<div class="et-simple-crm-form">
                <form class="et-simple-crm-form__form">
                    <input class="et-simple-crm-form__time" name="time" type="hidden" value="" />
                    <input name="action" type="hidden" value="%14$s" />
                    %15$s
                    <p>
                        <label>%1$s</label>
                        <input name="name" type="text" value="%2$s"%16$s />
                        %17$s
                    </p>
                    <p>
                        <label>%3$s</label>
                        <input name="phone" type="tel" value="%4$s"%18$s />
                        %19$s
                    </p>
                    <p>
                        <label>%5$s</label>
                        <input name="email" type="email" value="%6$s"%20$s />
                        %21$s
                    </p>
                    <p>
                        <label>%7$s</label>
                        <input name="budget" type="number" value="%8$s"%22$s />
                        %23$s
                    </p>
                    <p>
                        <label>%9$s</label>
                        <textarea name="message" id="" cols="%11$s" rows="%12$s"%24$s>%10$s</textarea>
                        %25$s
                    </p>
                    <p class="et-simple-crm-form__message"></p>
                    <div class="et-simple-crm-form__submit">
                        <button type="submit">%13$s</button>
                        <div class="et-simple-crm-loader"><div></div><div></div><div></div><div></div></div>
                    </p>
                </form>
            </div>',
            esc_html($form->label_name), // %1$s
            sanitize_text_field($form->name), // %2$s
            esc_html($form->label_phone), // %3$s
            sanitize_text_field($form->phone), // %4$s
            esc_html($form->label_email), // %5$s
            sanitize_text_field($form->email), // %6$s
            esc_html($form->label_budget), // %7$s
            intval($form->budget), // %8$s
            esc_html($form->label_message), // %9$s
            wp_kses_post($form->message), // %10$s
            esc_attr($form->message_rows), // %11$s
            esc_attr($form->message_cols), // %12$s
            esc_html($form->label_button), // %13$s
            $this->ajaxSubmitActionHook, // %14$s
            wp_nonce_field($this->ajaxSubmitActionHook, $this->nonceName, false, false), // %15$s
            ! empty($form->maxlength_name) ? sprintf(' maxlength="%s"', $form->maxlength_name) : '', // %16$s
            ! empty($form->maxlength_name) ? sprintf($maxLengthNotice, $form->maxlength_name) : '', // %17$s
            ! empty($form->maxlength_phone) ? sprintf(' maxlength="%s"', $form->maxlength_phone) : '', // %18$s
            ! empty($form->maxlength_phone) ? sprintf($maxLengthNotice, $form->maxlength_phone) : '', // %19$s
            ! empty($form->maxlength_email) ? sprintf(' maxlength="%s"', $form->maxlength_email) : '', // %20$s
            ! empty($form->maxlength_email) ? sprintf($maxLengthNotice, $form->maxlength_email) : '', // %21$s
            ! empty($form->maxlength_budget) ? sprintf(' min="0" max="%s"', $form->maxlength_budget) : '', // %22$s
            ! empty($form->maxlength_budget) ? sprintf($maxLengthNotice, $form->maxlength_budget) : '', // %23$s
            ! empty($form->maxlength_message) ? sprintf(' maxlength="%s"', $form->maxlength_message) : '', // %24$s
            ! empty($form->maxlength_message) ? sprintf($maxLengthNotice, $form->maxlength_message) : '' // %25$s
        );
    }

    /**
     * Hook on ajax action hook
     * Using $_POST
     */
    public function formSubmit()
    {
        check_admin_referer($this->ajaxSubmitActionHook, $this->nonceName);

        // TODO: Check if any of the fields is empty as all are required

        // TODO: Add new post type record
        wp_send_json_success(
            [
                'message' => esc_html__('Form sent successfully!', 'et-simple-crm')
            ]
        );
    }

    /**
     * Hook on ajax action hook
     * Using $_POST
     */
    public function getCurrentTimeFromAPI()
    {
        check_admin_referer($this->ajaxSubmitActionHook, $this->nonceName);

        // Format time as selected in WP settings
        $timeFormat = get_option('date_format') . ' ' . get_option('time_format');

        try {
            $response = $this->timeApi->get();
            $data = $response->getData();
            // @var \DateTime $dateTime
            $dateTime = $data['dateTime'];
            $time = $dateTime->format($timeFormat);
        } catch (\Exception $e) {
            // TODO: Add logger here

            // IP address not found or error. Get local WP time
            $dateTime = new \DateTime();
            $dateTime->setTimestamp(current_time('timestamp'));
            wp_send_json_success(
                [
                    'time' => sprintf(
                        esc_html__('WP local time: %s'),
                        $dateTime->format($timeFormat)
                    )
                ]
            );
        }

        wp_send_json_success(
            [
                'time' => $time
            ]
        );
    }

    /**
     * Enqueue plugin scripts
     */
    public function scripts()
    {
        if (! is_singular()) {
            return;
        }

        global $post;
        if (! is_a($post, 'WP_Post') || ! has_shortcode($post->post_content, self::TAG)) {
            return;
        }

        wp_enqueue_style(
            $this->pluginSlug,
            $this->assetsUrl . '/public/dist/css/public.min.css',
            [],
            $this->pluginVersion
        );

        wp_enqueue_script(
            $this->pluginSlug,
            $this->assetsUrl . '/public/dist/js/public.min.js',
            ['jquery'],
            $this->pluginVersion,
            true
        );

        wp_localize_script(
            $this->pluginSlug,
            'etSimpleCRMData',
            [
                'ajaxUrl' => admin_url('admin-ajax.php'),
                'errorMessage' => esc_html__('An error occurred during the form submission', 'et-simple-crm'),
                'timeAction' => $this->ajaxTimeActionHook

            ]
        );
    }

    /**
     * Override Form value object properties with shortcode params
     * @param array $atts Array of shortcode attributes
     * @param Form $form Value object ETSimpleCrm\ValueObjects\Form
     * @return Form
     */
    private function overrideAtts($atts, Form $form)
    {
        if (empty($atts) || ! is_array($atts)) {
            return $form;
        }

        foreach ($atts as $key => $attribute) {
            if (empty($attribute)) {
                continue;
            }

            if (property_exists($form, $key)) {
                $form->$key = $attribute;
            }
        }

        return $form;
    }
}
