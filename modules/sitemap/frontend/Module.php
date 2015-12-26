<?php

namespace modules\sitemap\frontend;

use yii\base\Application;
use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface
{
    public $controllerNamespace = 'modules\sitemap\frontend\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     *
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $app->getUrlManager()->addRules([
            [
                'pattern' => $this->id,
                'route'   => $this->id . '/default/index',
                'suffix'  => '.xml',
            ],
            [
                'pattern' => $this->id . '/<type:[\w-@]+>/<slug:[\w-@]+>-<page:\d+>',
                'route'   => $this->id . '/default/view',
                'suffix'  => '.xml',
            ],
            [
                'pattern' => $this->id . '/<type:[\w-@]+>/<slug:[\w-@]+>',
                'route'   => $this->id . '/default/view',
                'suffix'  => '.xml',
            ],
            $this->id => '/site/not-found',
            $this->id . '/default/' => '/site/not-found',
            $this->id . '/default/<alias:index|view>/' => '/site/not-found',
        ], false);
    }
}
