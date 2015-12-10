<?php
/**
 * @file      MenuItemFixture.php
 * @date      12/6/2015
 * @time      5:58 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class MenuItemFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MenuItemFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\MenuItem';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_menu_item.php';
}