<?php
/**
 * @file      media_meta.php
 * @date      12/6/2015
 * @time      5:34 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'media_id'   => '1',
    'meta_name'  => $faker->slug(1),
    'meta_value' => $faker->text
];