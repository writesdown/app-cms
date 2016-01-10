<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\widgets;

use common\models\Widget;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

/**
 * Render active widget to frontend.
 *
 * The following example shows how to use RenderWidget:
 *
 * ~~~
 * RenderWidget::widget([
 *    'location' => 'sidebar',
 *    'config'   => [
 *        'beforeWidget' => '<div class="widget">',
 *        'afterWidget'  => '</div>',
 *        'beforeTitle'  => '<h4 class="widget-title">',
 *        'afterTitle'   => '</h4>',
 *   ]
 * ])
 * ~~~
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.2.0
 */
class RenderWidget extends \yii\base\Widget
{
    /**
     * @var array Configuration of widget.
     */
    public $config;

    /**
     * @var string Location of active widget.
     */
    public $location;

    /**
     * @var array Loaded activated widget.
     */
    private $_widget = [];

    /**
     * @var array Default configuration of widget.
     */
    private $_defaultConfig = [
        'beforeTitle'  => '',
        'afterTitle'   => '',
        'beforeWidget' => '',
        'afterWidget'  => '',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        /**
         * @var $activeWidgets \common\models\Widget
         */
        $activeWidgets = Widget::find()
            ->where(['widget_location' => $this->location])
            ->orderBy(['widget_order' => SORT_ASC])
            ->all();

        if ($activeWidgets) {
            foreach ($activeWidgets as $activeWidget) {
                $this->_widget[] = ArrayHelper::merge(
                    $this->_defaultConfig,
                    $this->config,
                    $activeWidget->getConfig(),
                    ['id' => $activeWidget->id]
                );
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        foreach ($this->_widget as $widget) {
            try {
                Yii::createObject($widget);
            } catch (Exception $e) {
            }
        }
    }
}
