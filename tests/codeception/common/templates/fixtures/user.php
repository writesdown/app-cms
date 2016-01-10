<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$security = Yii::$app->getSecurity();

return [
    'username'             => $faker->userName,
    'email'                => $faker->email,
    'full_name'            => $faker->name,
    'display_name'         => $faker->name,
    'password_hash'        => $security->generatePasswordHash('password_' . $index),
    'password_reset_token' => $security->generateRandomString() . '_' . time(),
    'auth_key'             => $security->generateRandomString(),
    'created_at'           => time(),
    'updated_at'           => time(),
    'login_at'             => time(),
];
