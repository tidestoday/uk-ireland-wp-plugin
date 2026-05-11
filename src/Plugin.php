<?php

namespace TidesToday\TideTimes;

use TidesToday\TideTimes\Polyfill\Php55Polyfill;

/**
 * Class Plugin
 * @package TidesToday\TideTimes
 */
class Plugin
{
    const TEXTDOMAIN = 'tides-today';

    const ENDPOINT = 'https://api.tidestoday.io/widgets-api/wordpress/locations/';

    /** @var string $pluginBase */
    private $pluginBase;

    /**
     * Plugin constructor.
     * @param $pluginBase
     */
    public function __construct($pluginBase)
    {
        $this
            ->registerPolyfills()
            ->setPluginBase($pluginBase)
            ->registerShortcode();

        add_action('widgets_init', [$this, 'registerWidget']);
        add_action('plugins_loaded', [$this, 'initiateLocalisation']);
        add_action('admin_notices', [$this, 'displayEndOfLifeNotice']);
    }

    /**
     * Loads PHP previous version polyfills.
     *
     * @return $this
     */
    private function registerPolyfills()
    {
        new Php55Polyfill();
        return $this;
    }

    /**
     * Initiates localisation.
     */
    public function initiateLocalisation()
    {
        load_plugin_textdomain(self::TEXTDOMAIN, false, $this->getPluginBase(DIRECTORY_SEPARATOR . 'languages'));
    }

    /**
     * Registers the shortcode.
     *
     * @return $this
     */
    private function registerShortcode()
    {
        new Shortcode();
        return $this;
    }

    /**
     * Registers the widget.
     */
    public function registerWidget()
    {
        register_widget('TidesToday\TideTimes\Widget');
    }

    /**
     * Displays the end-of-life notice in the WordPress admin.
     */
    public function displayEndOfLifeNotice()
    {
        ?>
        <div class="notice notice-warning">
            <p>
                <?php
                printf(
                    esc_html__('The Tides Today UK and Ireland plugin will be shut down May 1st 2029. Please migrate to the %s plugin', self::TEXTDOMAIN),
                    '<a href="' . esc_url('https://wordpress.org/plugins/tides-today-tides-and-weather/') . '">' . esc_html__('Tides Today Tides and Weather', self::TEXTDOMAIN) . '</a>'
                );
                ?>
            </p>
        </div>
        <?php
    }

    /**
     * @param string $path
     * @return string
     */
    public function getPluginBase($path = '')
    {
        return $this->pluginBase . $path;
    }

    /**
     * @param $pluginBase
     * @return $this
     */
    public function setPluginBase($pluginBase)
    {
        $this->pluginBase = $pluginBase;
        return $this;
    }
}
