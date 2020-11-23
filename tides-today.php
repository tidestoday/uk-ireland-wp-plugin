<?php
defined('ABSPATH') or die('You shall not pass!');

use \TidesToday\TideTimes\Plugin;

/*
Plugin Name: Tides Today UK and Ireland Tide Times
Plugin URI:  https://tides.today/three-day-forecast-plugin
Description: The Tides Today UK and Ireland Tide Times plugin provides 3 day tide forecasts for over 700 UK and Ireland locations.
Version:     1.3.3
Author:      Stephen Wright
Author URI:  https://www.stewright.me/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: tides-today
Domain Path: /languages
*/

require_once __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

$tideTimesPlugin = new Plugin(dirname(plugin_basename(__FILE__)));
