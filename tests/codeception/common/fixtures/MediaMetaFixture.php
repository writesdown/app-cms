<?php
/**
 * @file      MediaMetaFixture.php
 * @date      12/6/2015
 * @time      5:59 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 Agiel K. Saputra
 * @license   http://www.writesdown.com/license/
 */


namespace tests\codeception\common\fixtures;


use yii\test\ActiveFixture;

/**
 * Class MediaMetaFixture
 *
 * @package tests\codeception\common\fixtures
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.2
 */
class MediaMetaFixture extends ActiveFixture
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\MediaMeta';

    /**
     * @var string
     */
    public $dataFile = '@tests/codeception/common/fixtures/data/init_media_meta.php';

}