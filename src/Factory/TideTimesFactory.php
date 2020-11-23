<?php

namespace TidesToday\TideTimes\Factory;

use TidesToday\TideTimes\Exception\InvalidAttributeException;
use TidesToday\TideTimes\TideTimes;

/**
 * Class TideTimesFactory
 * @package TidesToday\TideTimes\Factory
 */
class TideTimesFactory
{
    /**
     * Builds tide times.
     *
     * @param array $attr
     * @param string $content
     * @param string|null $id
     * @return TideTimes
     * @throws InvalidAttributeException
     */
    public static function buildTideTimes($attr = [], $content = '', $id = null)
    {
        if (false === is_array($attr)) {
            throw new InvalidAttributeException(
                'The value passed to ' . __CLASS__ . ' ' . __FUNCTION__ . ' is not an array'
            );
        }

        foreach (['title', 'location', 'days', 'map'] as $field) {
            if (false === isset($attr[$field])) {
                throw new InvalidAttributeException(
                    'The field ' . $field . ' does not exist in ' . __CLASS__ . ' ' . __FUNCTION__
                );
            }
        }
        return new TideTimes(
            $attr['title'],
            $content,
            $attr['location'],
            $attr['days'],
            $id,
            self::ensureBool($attr['map'])
        );
    }

    /**
     * Converts string to bool. String 'false' and '0' equates to bool true so this fixes that.
     *
     * @param string $input
     * @return bool
     */
    private static function ensureBool($input = '')
    {
        if ('0' === $input || 'false' === $input) {
            return false;
        }

        return (bool)$input;
    }
}
