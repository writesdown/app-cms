<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * AppAsset is used to register asset files on backend application.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'js/site.js',
    ];
    public $depends = [
        'codezeen\yii2\adminlte\AdminLteAsset',
        'backend\assets\AppAssetIe9',
    ];
}
