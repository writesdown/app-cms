<?php

namespace tests\codeception\common\fixtures;

/**
 * @file      PostTypeTaxonomyFixture.php
 * @date      12/6/2015
 * @time      5:50 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */

use yii\test\ActiveFixture;

/**
 * Class PostTypeTaxonomyFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */

class PostTypeTaxonomyFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\PostTypeTaxonomy';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_post_type_taxonomy.php';
}