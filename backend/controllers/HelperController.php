<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use codezeen\yii2\tinymce\TinyMceCompressorAction;
use yii\web\Controller;

/**
 * Class HelperController, can be used by other controllers or extensions.
 * For example if you want to use TinyMCE compressor on your widget or module.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
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
