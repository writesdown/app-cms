<?php
/**
 * @file    MenuAsset.php.
 * @date    6/4/2015
 * @time    3:45 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for menu.
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MenuAsset extends AssetBundle{
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
        'css/menu.css'
    ];
    /**
     * @var array
     */
    public $js = [
        'js/menu.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'backend\assets\AppAsset',
    ];
}