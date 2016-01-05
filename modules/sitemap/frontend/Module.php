<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace modules\sitemap\frontend;

use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class Module
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class Module extends \yii\base\Module implements BootstrapInterface
{
    /**
     * @var string
     */
    public $controllerNamespace = 'modules\sitemap\frontend\controllers';

    /**
     * @inheritdoc
     */
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
            $this->id                                  => '/site/not-found',
            $this->id . '/default/'                    => '/site/not-found',
            $this->id . '/default/<alias:index|view>/' => '/site/not-found',
        ], false);
    }
}
