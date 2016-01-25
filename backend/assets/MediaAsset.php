<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MediaAsset is used to register file assets on 'create media' page.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MediaAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'backend\assets\AppAsset',
        'dosamigos\fileupload\FileUploadUIAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js = ['js/media.js'];
        } else {
            $this->js = ['js/min/media.js'];
        }
    }
}
