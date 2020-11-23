<?php

namespace TidesToday\TideTimes;

use TidesToday\TideTimes\Factory\TideTimesFactory;

/**
 * Class Shortcode
 * @package TidesToday\TideTimes
 */
class Shortcode
{
    const SHORTCODE = 'tide_times';

    /**
     * Shortcode constructor.
     */
    public function __construct()
    {
        add_shortcode(self::SHORTCODE, [$this, 'render']);
    }

    /**
     * @param array $attr
     * @param string|null $content
     * @return string
     */
    public function render($attr = [], $content = null)
    {
        $attr = shortcode_atts([
            'title' => __('Tide times', Plugin::TEXTDOMAIN),
            'location' => 'llandudno', // We've got to start somewhere,
            'days' => TideTimes::DEFAULT_DAYS,
            'map' => TideTimes::DEFAULT_MAPS
        ], $attr);

        $shortcode = TideTimesFactory::buildTideTimes($attr, $content);

        return $shortcode->compile();
    }
}
