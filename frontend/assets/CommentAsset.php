<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * CommentAsset is used to register file assets that need reply link work.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class CommentAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'yii\web\YiiAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js = ['js/comment.js'];
        } else {
            $this->js = ['js/min/comment.js'];
        }
    }
}
