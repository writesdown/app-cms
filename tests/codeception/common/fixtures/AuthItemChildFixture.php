<?php
/**
 * @file      AuthItemChildFixture.php
 * @date      12/6/2015
 * @time      6:02 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class AuthItemChildFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class AuthItemChildFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%auth_item_child}}';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_auth_item_child.php';
}