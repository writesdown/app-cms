<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MediaPopupAsset is used to register asset files on 'media popup' page.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MediaPopupAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'backend\assets\AppAsset',
        'yii\jui\JuiAsset',
        'dosamigos\fileupload\FileUploadUIAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->css = ['css/media.popup.css'];
            $this->js = ['js/media.popup.js'];
        } else {
            $this->css = ['css/min/media.popup.css'];
            $this->js = ['js/media.popup.js'];
        }
    }
}
