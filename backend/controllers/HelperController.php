<?php
/**
 * @file      HelperController.php.
 * @date      6/4/2015
 * @time      3:47 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use yii\web\Controller;
use codezeen\yii2\tinymce\TinyMceCompressorAction;

/**
 * Class HelperController,
 * Helper controller that can be used by other controllers or extensions.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class HelperController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'tiny-mce-compressor' => [
                'class' => TinyMceCompressorAction::className(),
            ],
        ];
    }
}