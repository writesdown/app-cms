<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'post_id' => '1',
    'author' => $faker->name,
    'email' => $faker->email,
    'url' => $faker->url,
    'ip' => $faker->ipv4,
    'date' => $faker->date('Y-m-d H:i:s'),
    'content' => $faker->text,
    'status' => 'approved',
    'agent' => $faker->userAgent,
    'parent' => '0',
];
