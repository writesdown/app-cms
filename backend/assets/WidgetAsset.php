<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * WidgetAsset is used to register file assets on 'widget' page.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class WidgetAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'yii\jui\JuiAsset',
        'backend\assets\AppAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->js = ['js/widget.js'];
        } else {
            $this->js = ['js/min/widget.js'];
        }
    }
}
