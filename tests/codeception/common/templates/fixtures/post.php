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
    'author' => $faker->numberBetween(1, 6),
    'type' => $faker->numberBetween(1, 2),
    'title' => $faker->title,
    'excerpt' => $faker->text,
    'content' => $faker->text,
    'date' => $faker->date('Y-m-d H:i:s'),
    'modified' => $faker->date('Y-m-d H:i:s'),
    'status' => 'publish',
    'slug' => $faker->slug,
    'comment_status' => 'open',
    'comment_count' => '0',
];
