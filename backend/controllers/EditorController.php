<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use codezeen\yii2\tinymce\TinyMceCompressorAction;
use yii\web\Controller;

/**
 * Class EditorController, used by editor.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.3.0
 */
class EditorController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'compressor' => [
                'class' => TinyMceCompressorAction::className(),
            ],
        ];
    }
}
