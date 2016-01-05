<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace modules\sitemap\backend;

use Yii;

/**
 * Class Module
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     */
    public $controllerNamespace = 'modules\sitemap\backend\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['sitemap'])) {
            Yii::$app->i18n->translations['sitemap'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => Yii::$app->language,
                'basePath'       => __DIR__ . '/../messages',
            ];
        }
    }
}
