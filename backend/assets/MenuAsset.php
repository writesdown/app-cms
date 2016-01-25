<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * MenuAsset is used to register file assets on 'menu' page.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MenuAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $depends = [
        'backend\assets\AppAsset',
    ];

    public function init()
    {
        if (YII_DEBUG) {
            $this->css = ['css/menu.css'];
            $this->js = ['js/menu.js'];
        } else {
            $this->css = ['css/min/menu.css'];
            $this->js = ['js/min/menu.js'];
        }
    }
}
