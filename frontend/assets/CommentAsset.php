<?php
/**
 * @file    CommentAsset.php.
 * @date    6/4/2015
 * @time    12:13 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class CommentAsset
 *
 * @package frontend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 */
class CommentAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot';
    /**
     * @var string
     */
    public $baseUrl = '@web';
    /**
     * @var array
     */
    public $js = [
        'js/comment.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}