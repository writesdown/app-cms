<?php
/**
 * @file      AppAsset.php.
 * @date      6/4/2015
 * @time      3:40 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for backend.
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class AppAsset extends AssetBundle
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
    public $css = [
        'css/site.css',
    ];
    /**
     * @var array
     */
    public $js = [
        'js/site.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'codezeen\yii2\adminlte\AdminLteAsset',
        'backend\assets\AppAssetIe9'
    ];
}
