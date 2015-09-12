<?php
/**
 * @file      TextWidget.php
 * @date      9/5/2015
 * @time      2:35 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace widgets\text;

use yii\helpers\Html;
use common\components\BaseWidget;

class Text extends BaseWidget
{
    /**
     * @var string
     */
    public $text;

    /**
     * @inheritdoc
     */
    public function init()
    {
        echo $this->beforeWidget;
        if($this->title){
            echo $this->beforeTitle . $this->title . $this->afterTitle;
        }
        echo Html::tag('div', $this->text, [
            'class' => 'widget-text'
        ]);
        echo $this->afterWidget;
    }
}