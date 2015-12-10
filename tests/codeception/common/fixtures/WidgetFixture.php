<?php
/**
 * @file      WidgetFixture.php
 * @date      12/6/2015
 * @time      5:43 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

class WidgetFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Widget';
}