<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MediaModalAsset is used to register asset files on media modal widget.
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.3.0
 */
class MediaModalAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'backend\assets\AppAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->css = ['css/media.modal.css'];
            $this->js = ['js/media.modal.js'];
        } else {
            $this->css = ['css/min/media.modal.css'];
            $this->js = ['js/min/media.modal.js'];
        }
    }
}
