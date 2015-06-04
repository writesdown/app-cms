<?php
/**
 * @file      MediaPopupAsset.php.
 * @date      6/4/2015
 * @time      3:43 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Asset bundle for popup media.
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaPopupAsset extends AssetBundle
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
        'css/media.popup.css'
    ];
    /**
     * @var array
     */
    public $js = [
        'js/media.popup.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'backend\assets\AppAsset',
        'yii\jui\JuiAsset',
        'dosamigos\fileupload\FileUploadUIAsset'
    ];
} 