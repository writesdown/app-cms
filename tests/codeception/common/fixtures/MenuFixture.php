<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class MenuFixture
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MenuFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\Menu';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_menu.php';
}
