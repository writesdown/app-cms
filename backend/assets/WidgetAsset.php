<?php
/**
 * @file      WidgetAsset.php
 * @date      9/5/2015
 * @time      4:56 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */


namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Register asset for widget page
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class WidgetAsset extends AssetBundle
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
        'js/widget.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'yii\jui\JuiAsset',
        'backend\assets\AppAsset',
    ];
}