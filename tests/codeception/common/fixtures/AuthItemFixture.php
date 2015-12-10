<?php
/**
 * @file      AuthItemFixture.php
 * @date      12/6/2015
 * @time      6:03 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class AuthItemFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class AuthItemFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%auth_item}}';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_auth_item.php';
}