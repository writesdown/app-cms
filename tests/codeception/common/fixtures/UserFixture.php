<?php

namespace tests\codeception\common\fixtures;

use yii\test\ActiveFixture;

/**
 * Class UserFixture
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class UserFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\User';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_user.php';
}
