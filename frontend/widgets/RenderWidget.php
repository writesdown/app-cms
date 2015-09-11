<?php
/**
 * @file      Widget.php
 * @date      9/5/2015
 * @time      1:30 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\widgets;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/* MODELS */
use common\models\Widget;

class RenderWidget extends \yii\base\Widget
{
    /**
     * @var array Loaded activated widgets
     */
    private $_widget = [];

    /**
     * @var array Default configuration of widgets
     */
    private $defaultConfig = [
        'beforeTitle'  => '',
        'afterTitle'   => '',
        'beforeWidget' => '',
        'afterWidget'  => '',
    ];

    /**
     * @var array configuration of widget
     */
    public $config;

    /**
     * @var string Location of activated widgets
     */
    public $location;

    /**
     * @inheritdoc
     */
    public function init()
    {
        /**
         * @var $activeWidgets \common\models\Widget
         */
        $activeWidgets = Widget::find()
            ->where(['widget_location'=> $this->location])
            ->orderBy(['widget_order' => SORT_ASC])
            ->all();

        if ($activeWidgets) {
            foreach ($activeWidgets as $activeWidget) {
                $this->_widget[] = ArrayHelper::merge($this->defaultConfig, $this->config, $activeWidget->getConfig());
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        foreach ($this->_widget as $widget) {
            try{
                Yii::createObject($widget);
            }catch (Exception $e){}
        }
    }
}