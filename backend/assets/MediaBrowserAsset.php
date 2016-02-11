<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MediaBrowserAsset is used to register asset files on 'media browser' page.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaBrowserAsset extends AssetBundle
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
            $this->css = ['css/media.browser.css'];
            $this->js = ['js/media.browser.js'];
        } else {
            $this->css = ['css/min/media.browser.css'];
            $this->js = ['js/min/media.browser.js'];
        }
    }
}
