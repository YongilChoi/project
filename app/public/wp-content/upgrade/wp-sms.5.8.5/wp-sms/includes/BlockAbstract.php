<?php

namespace WP_SMS\Blocks;

use WP_Block;
use WP_SMS\Helper;

class BlockAbstract
{
    /**
     * Whether block name
     *
     * @var $blockName
     */
    protected $blockName;

    /**
     * Widget class name
     *
     * @var $widgetClassName
     */
    protected $widgetClassName;

    /**
     * Front-end script
     *
     * @var bool $script
     */
    protected $script = false;

    /**
     * Block blockVersion
     *
     * @var $blockVersion
     */
    protected $blockVersion;

    /**
     * Register block type
     */
    public function registerBlockType()
    {
        $blockPath = Helper::getAssetPath("assets/blocks/{$this->blockName}");

        register_block_type($blockPath, array(
            'render_callback' => [$this, 'renderCallback'],
        ));
    }

    /**
     * @param $attributes
     * @param $content
     * @param WP_Block $block
     * @return mixed
     */
    public function renderCallback($attributes, $content, WP_Block $block)
    {
        /*
         * Generate an unique ID for blocks
         */
        $unique_id = uniqid();
        $block_id = "{$this->blockName}-block-" . $unique_id;

        /**
         * Enqueue the script and data
         */
        if ($this->script) {
            wp_enqueue_script("wp-sms-blocks-subscribe", Helper::getPluginAssetUrl($this->script), ['jquery'], $this->blockVersion, true);
        }

        if ($this->blockName == "Subscribe") {
            wp_localize_script("wp-sms-blocks-subscribe", 'wpsms_ajax_object', array(
                'rest_endpoint_url' => get_rest_url(null, 'wpsms/v1/newsletter'),
                'unknown_error'     => __('Unknown Error! Check your connection and try again.', 'wp-sms'),
                'loading_text'      => __('Loading...', 'wp-sms'),
                'subscribe_text'    => __('Subscribe', 'wp-sms'),
                'activation_text'   => __('Activation', 'wp-sms'),
            ));
        }

        wp_localize_script(
            "wp-sms-blocks-{$this->blockName}",
            'pluginAssetsUrl',
            [
                'imagesFolder' => Helper::getPluginAssetUrl("images/"),
            ]
        );

        /**
         * Render the output - With a unique ID
         */
        $attributes['block_id'] = $block_id;
        return $this->output($attributes);
    }
}
