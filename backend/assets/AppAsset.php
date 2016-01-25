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
    public $depends = [
        'codezeen\yii2\adminlte\AdminLteAsset',
        'backend\assets\AppAssetIe9',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->css = ['css/site.css'];
            $this->js = ['js/site.js'];
        } else {
            $this->css = ['css/min/site.css'];
            $this->js = ['js/min/site.js'];
        }
    }
}
