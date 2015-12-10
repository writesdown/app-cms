<?php
/**
 * @file      AuthRuleFixture.php
 * @date      12/6/2015
 * @time      6:01 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class AuthRuleFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class AuthRuleFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $tableName = '{{%auth_rule}}';
}