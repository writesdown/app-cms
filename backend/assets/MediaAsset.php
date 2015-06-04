<?php
/**
 * @file      MediaAsset.php.
 * @date      6/4/2015
 * @time      3:41 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;


use yii\web\AssetBundle;

/**
 * Asset bundle for media.
 *
 * @package backend\assets
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class MediaAsset extends AssetBundle
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
        'js/media.js'
    ];
    /**
     * @var array
     */
    public $depends = [
        'backend\assets\AppAsset',
        'dosamigos\fileupload\FileUploadUIAsset'
    ];
} 