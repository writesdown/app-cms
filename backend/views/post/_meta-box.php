<?php
/**
 * @file      _meta-box.php
 * @date      8/25/2015
 * @time      10:19 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;

/* @var $model common\models\Post */
/* @var $postType common\models\PostType */
/* @var $form yii\widgets\ActiveForm */

$metaBox = isset(Yii::$app->params['postType'][$postType->post_type_name]['metaBox']) ?
    Yii::$app->params['postType'][$postType->post_type_name]['metaBox'] :
    [];

foreach($metaBox as $config){
    $config = ArrayHelper::merge($config, [
        'model'     => $model,
        'form'      => $form,
    ]);
    Yii::createObject($config);
}
