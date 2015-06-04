<?php
/**
 * @file      AppAssetIe9.php.
 * @date      6/4/2015
 * @time      3:41 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\View;
use yii\web\AssetBundle;

/**
 * Register html5shiv.js and respond.min.js when browser is Internet Explorer 9
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class AppAssetIe9 extends AssetBundle
{
    /**
     * @var array
     */
    public $js = [
        '//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
        '//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js'
    ];
    /**
     * @var array
     */
    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position'  => View::POS_HEAD
    ];
}