<?php

namespace TidesToday\TideTimes;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class TideTimes
 * @package TidesToday\TideTimes
 */
class TideTimes
{
    /** @var string $id */
    public $id;

    /** @var Client $client */
    public $client;

    /** @var string $title */
    public $title;

    /** @var string $content */
    public $content;

    /** @var int $days */
    private $days;

    /** @var bool $map */
    private $map;

    /** @var string $location */
    public $location;

    const ID_PREFIX = 'tide-times-';

    const DEFAULT_DAYS = 3;

    const DEFAULT_MAPS = true;
    
    const TEST_MODE = 'TIDES_TODAY_TEST_MODE';

    /**
     * TideTimes constructor.
     * @param string $title
     * @param string $content
     * @param string $location
     * @param $days
     * @param null $id
     * @param $map
     */
    public function __construct(
        $title = '',
        $content = '',
        $location = '',
        $days = self::DEFAULT_DAYS,
        $id = null,
        $map = self::DEFAULT_MAPS
    ) {
        $this->title = $title;
        $this->days = $days;
        $this->map = $map;
        $this->content = $content;
        $this->location = $location;
        $this->id = empty($id) ? uniqid(self::ID_PREFIX) : $id;
        $this->client = new Client();
    }

    /**
     * Gets the endpoint for a given location.
     *
     * @return string
     */
    private function getEndpoint()
    {
        return Plugin::ENDPOINT . $this->location . '/?' . http_build_query([
                'days' => $this->days,
                'map' => $this->map
            ]);
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
        return $data->data;
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getTideFromApi()
    {
        try {
            $response = $this->client->request('GET', $this->getEndpoint());

            return $this->getDataFromApiResponse($response->getBody()->getContents());
        } catch (GuzzleException $exception) {
            return
                '<p class="tides-today-404">' .
                ($this->isDiagMode() ? $exception->getMessage() : '') .
                sprintf(__("Location '%s' was not found", Plugin::TEXTDOMAIN), $this->location) .
                '</p>';
        }
    }

    /**
     * @return bool
     */
    private function isDiagMode()
    {
        return defined(self::TEST_MODE);
    }

    /**
     * Returns compiled string for the tide time.
     *
     * @return string
     */
    public function compile()
    {
        return
            "<div id='{$this->id}' class='tide-times tide-times-container'>
                <h3 class='tide-times-title'>{$this->title}</h3>
                <p class='tide-times-content'>{$this->content}</p>
                {$this->getTideFromApi()}
                <hr class='tide-times-divider'>
            </div>";
    }
}
