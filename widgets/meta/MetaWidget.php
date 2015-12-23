<?php

/**
 * @file      MetaWidget.php
 * @date      9/5/2015
 * @time      3:10 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace widgets\meta;

use Yii;
use yii\widgets\Menu;
use common\components\BaseWidget;

class MetaWidget extends BaseWidget
{
    /**
     * @var string The text to be displayed by the widget.
     */
    public $text;

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->beforeWidget;
        if ($this->title) {
            echo $this->beforeTitle . $this->title . $this->afterTitle;
        }
        echo Menu::widget([
            'items' => [
                [
                    'label'   => 'Site Admin',
                    'url'     => Yii::$app->urlManagerBack->baseUrl,
                    'visible' => !Yii::$app->user->isGuest
                ],
                [
                    'label'   => 'Login',
                    'url'     => Yii::$app->urlManagerBack->createUrl(['/site/login']),
                    'visible' => Yii::$app->user->isGuest
                ],
                [
                    'label'    => 'Logout',
                    'url'      => Yii::$app->urlManager->createUrl(['/site/logout']),
                    'visible'  => !Yii::$app->user->isGuest,
                    'template' => '<a href="{url}" data-method="post">{label}</a>'
                ],
                [
                    'label' => 'WritesDown.com',
                    'url'   => 'http://www.writesdown.com/',
                ]
            ],
        ]);
        echo $this->afterWidget;
    }
}