<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace common\components;

/**
 * List of timezones with GMT offset.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class TimeZoneHelper
{
    /**
     * List of timezone as array.
     *
     * @return array
     */
    public static function listTimeZone()
    {
        $timezone = [];
        $timestamp = time();

        foreach (timezone_identifiers_list() as $zone) {
            date_default_timezone_set($zone);
            $timezone[$zone] = $zone . ' UTC/GMT ' . date('P', $timestamp);
        }

        return $timezone;
    }
}
