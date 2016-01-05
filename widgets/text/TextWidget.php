<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace widgets\text;

use yii\helpers\Html;
use common\components\BaseWidget;

/**
 * Class TextWidget
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.1
 */
class TextWidget extends BaseWidget
{
    /**
     * @var string
     */
    public $text = '';

    /**
     * @inheritdoc
     */
    public function run()
    {
        echo $this->beforeWidget;

        if ($this->title) {
            echo $this->beforeTitle . $this->title . $this->afterTitle;
        }

        echo Html::tag('div', $this->text, [
            'class' => 'widget-text'
        ]);
        echo $this->afterWidget;
    }
}
