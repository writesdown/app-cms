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

$metaBox = isset(Yii::$app->params['metaBox']) ? Yii::$app->params['metaBox'] : [];

foreach($metaBox as $postTypeName => $configs){
    if($postType->post_type_name === $postTypeName){
        foreach ($configs as $config) {
            $config = ArrayHelper::merge($config, [
                'model'     => $model,
                'form'      => $form,
            ]);
            Yii::createObject($config);
        }
    }
}
