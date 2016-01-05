<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MenuAsset is used to register file assets on 'menu' page.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/menu.css',
    ];
    public $js = [
        'js/menu.js',
    ];
    public $depends = [
        'backend\assets\AppAsset',
    ];
}
