<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class ModuleFixture
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class ModuleFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\Module';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_module.php';
}
