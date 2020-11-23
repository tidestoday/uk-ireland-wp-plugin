<?php

namespace TidesToday\TideTimes\Polyfill;

/**
 * Class Php55Polyfill
 * @package TidesToday\TideTimes\Polyfill
 */
class Php55Polyfill {
}

/**
 * @since PHP 5.5.0
 */
if (false === function_exists('curl_reset'))
{
    function curl_reset(&$ch)
    {
        $ch = curl_init();
    }
}