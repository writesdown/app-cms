<?php
/**
 * @file    Json.php.
 * @date    6/4/2015
 * @time    3:55 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace common\components;

/**
 * Class Json extends \yii\helpers\Json.
 * Adding method to check whether a string is valid json or not.
 *
 * @package common\components
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class Json extends \yii\helpers\Json
{
    /**
     * Check for valid json.
     *
     * @param $string
     *
     * @return bool
     */
    public static function isJson($string)
    {
        // make sure provided input is of type string
        if (!is_string($string)) {
            return false;
        }

        // trim white spaces
        $string = trim($string);

        // get first character
        $firstChar = substr($string, 0, 1);

        // get last character
        $lastChar = substr($string, -1);

        // check if there is a first and last character
        if (!$firstChar || !$lastChar) {
            return false;
        }

        // make sure first character is either { or [
        if ($firstChar !== '{' && $firstChar !== '[') {
            return false;
        }

        // make sure last character is either } or ]
        if ($lastChar !== '}' && $lastChar !== ']') {
            return false;
        }

        // let's leave the rest to PHP.
        // try to decode string
        json_decode($string);

        // check if error occurred
        $isValid = json_last_error() === JSON_ERROR_NONE;

        return $isValid;
    }
} 