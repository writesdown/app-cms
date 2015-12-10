<?php
/**
 * @file      OptionFixture.php
 * @date      12/6/2015
 * @time      6:08 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class OptionFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class OptionFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\Option';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_option.php';
}