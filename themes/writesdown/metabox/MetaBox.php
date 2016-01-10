<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace themes\writesdown\metabox;

use Yii;
use yii\base\Object;

/**
 * Class MetaBox
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MetaBox extends Object
{
    /**
     * @var \common\models\Post
     */
    public $model;

    /**
     * @var \yii\widgets\ActiveForm
     */
    public $form;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->renderBox();
    }

    public function renderBox()
    {
        echo Yii::$app->view->renderFile(__DIR__ . '/views/_metabox.php', [
            'model' => $this->model,
            'form'  => $this->form
        ]);
    }
}
