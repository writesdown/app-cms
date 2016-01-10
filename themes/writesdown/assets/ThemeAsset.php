<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace themes\writesdown\assets;

use yii\web\AssetBundle;

/**
 * Register asset files for WritesDown default theme.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class ThemeAsset extends AssetBundle
{
    public $sourcePath = '@themes/writesdown/files';
    public $css = [
        'css/site.css',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle',
    ];
}
