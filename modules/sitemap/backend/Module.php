<?php

namespace modules\sitemap\backend;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'modules\sitemap\backend\controllers';

    public function init()
    {
        parent::init();

        if (!isset(Yii::$app->i18n->translations['sitemap'])) {
            Yii::$app->i18n->translations['sitemap'] = [
                'class'          => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => Yii::$app->language,
                'basePath'       => __DIR__ . '/../messages'
            ];
        }
    }
}
