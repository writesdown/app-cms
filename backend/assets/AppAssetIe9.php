<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Register html5shiv.js and respond.min.js when browser is Internet Explorer 9.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class AppAssetIe9 extends AssetBundle
{
    public $js = [
        '//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js',
        '//oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js',
    ];
    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position'  => View::POS_HEAD,
    ];
}
