<?php

namespace TidesToday\TideTimes;

use GuzzleHttp\Client;
use TidesToday\TideTimes\Factory\TideTimesFactory;
use TidesToday\TideTimes\WidgetInput\SelectWidgetInput;
use TidesToday\TideTimes\WidgetInput\TextFieldWidgetInput;

/**
 * Class Widget
 * @package TidesToday\TideTimes
 */
class Widget extends \WP_Widget
{
    /** @var Client $client */
    private $client;

    const CACHE_KEY = 'tides-today-locations';

    /**
     * Widget constructor.
     */
    public function __construct()
    {
        $this->client = new Client();

        parent::__construct('tides_today', __('Tide Times', Plugin::TEXTDOMAIN), [
            'classname' => 'tides_today',
            'description' => __('Display tide times for over 700 UK and Ireland locations.', Plugin::TEXTDOMAIN),
        ]);
    }

    /**
     * Shows the widget.
     *
     * @param $attr
     * @param $instance
     */
    public function widget($attr, $instance)
    {
        $instance = shortcode_atts([
            'title' => __('Tide Times', Plugin::TEXTDOMAIN),
            'location' => 'llandudno', // We've got to start somewhere,
            'content' => '',
            'days' => TideTimes::DEFAULT_DAYS,
            'map' => TideTimes::DEFAULT_MAPS
        ], $instance);

        $widget = TideTimesFactory::buildTideTimes($instance, $instance['content'], $attr['widget_id']);

        echo $widget->compile();
    }

    /**
     * Gets the response string from the endpoint data.
     *
     * @param $response
     * @return string
     */
    private function getDataFromApiResponse($response)
    {
        $data = json_decode($response);
        $response = [];

        foreach ($data->data as $region) {
            foreach ($region->locations as $location) {
                $response[$region->name][$location->slug] = $location->name;
            }
        }

        return $response;
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getLocations()
    {
        $response = wp_cache_get(self::CACHE_KEY);

        if (false === $response) {
            $response = $this->client->request('GET', Plugin::ENDPOINT)->getBody()->getContents();
            wp_cache_set(self::CACHE_KEY, $response);
        }

        return $this->getDataFromApiResponse($response);
    }

    /**
     * Displays the form on the widget admin WP page.
     *
     * @param $instance
     */
    public function form($instance)
    {
        // Set default values
        $instance = wp_parse_args((array)$instance, array(
            'title' => __('Tide Times', Plugin::TEXTDOMAIN),
            'days' => TideTimes::DEFAULT_DAYS,
            'map' => TideTimes::DEFAULT_MAPS,
            'content' => '',
            'location' => '',
        ));

        $titleField = new TextFieldWidgetInput(
            $this->get_field_id('title'),
            $this->get_field_name('title'),
            esc_attr($instance['title']),
            __('Title', Plugin::TEXTDOMAIN),
            __('Enter a title to display', Plugin::TEXTDOMAIN),
            esc_attr__('Tide times for...', Plugin::TEXTDOMAIN)
        );
        $contentField = new TextFieldWidgetInput(
            $this->get_field_id('content'),
            $this->get_field_name('content'),
            esc_attr($instance['content']),
            __('Description', Plugin::TEXTDOMAIN),
            __('Enter a description to display', Plugin::TEXTDOMAIN)
        );
        $daysField = new SelectWidgetInput(
            $this->get_field_id('days'),
            $this->get_field_name('days'),
            $instance['days'],
            __('Days to show', Plugin::TEXTDOMAIN),
            __('Choose how many days to show', Plugin::TEXTDOMAIN),
            [
                1 => __('1 day', Plugin::TEXTDOMAIN),
                2 => __('2 days', Plugin::TEXTDOMAIN),
                3 => __('3 days', Plugin::TEXTDOMAIN)
            ]
        );
        $mapField = new SelectWidgetInput(
            $this->get_field_id('map'),
            $this->get_field_name('map'),
            $instance['map'],
            __('Display location map?', Plugin::TEXTDOMAIN),
            __('Choose to show a map or not', Plugin::TEXTDOMAIN),
            [
                'true' => __('Yes', Plugin::TEXTDOMAIN),
                'false' => __('No', Plugin::TEXTDOMAIN)
            ]
        );
        $locationField = new SelectWidgetInput(
            $this->get_field_id('location'),
            $this->get_field_name('location'),
            $instance['location'],
            __('Locations', Plugin::TEXTDOMAIN),
            __('Choose which location to show tides for', Plugin::TEXTDOMAIN),
            $this->getLocations()
        );

        $titleField->render();
        $contentField->render();
        $daysField->render();
        $mapField->render();
        $locationField->render();
    }

    /**
     * @param $newInstance
     * @param $oldInstance
     * @return mixed
     */
    public function update($newInstance, $oldInstance)
    {
        $instance = $oldInstance;

        $instance['title'] = false === empty($newInstance['title']) ? strip_tags($newInstance['title']) : '';
        $instance['days'] = false === empty($newInstance['days']) ? strip_tags($newInstance['days']) : '';
        $instance['map'] = false === empty($newInstance['map']) ? strip_tags($newInstance['map']) : '';
        $instance['content'] = false === empty($newInstance['content']) ? strip_tags($newInstance['content']) : '';
        $instance['location'] = false === empty($newInstance['location']) ? strip_tags($newInstance['location']) : '';

        return $instance;
    }
}
