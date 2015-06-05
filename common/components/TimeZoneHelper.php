<?php
/**
 * @file    TimeZoneHelper.php.
 * @date    6/4/2015
 * @time    4:06 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\components;

/**
 * Timezones list with GMT offset
 *
 * @package common\components
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
        foreach (timezone_identifiers_list() as $key => $zone) {
            date_default_timezone_set($zone);
            $timezone[$zone] = $zone . ' UTC/GMT ' . date('P', $timestamp);
        }
        return $timezone;
    }
}
