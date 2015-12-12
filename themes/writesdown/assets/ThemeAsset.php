<?php
/**
 * @file        ThemeAsset.php
 * @date        1/6/2015
 * @tile        6:46 PM
 * @author      Agiel K. Saputra
 * @copyright   Agiel K. Saputra
 * @license     BSD License
 * @version     1.0
 * @since       1.0
 */

namespace themes\writesdown\assets;

use yii\web\AssetBundle;

/**
 * Class ThemeAsset
 * @package themes\writesdown\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@themes/writesdown/files';

    /**
     * @var array
     */
    public $css = [
        'css/site.css'
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
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'rmrevin\yii\fontawesome\AssetBundle'
    ];
}
