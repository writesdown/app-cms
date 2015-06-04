<?php
/**
 * @file      update.php.
 * @date      6/4/2015
 * @time      11:52 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\Option */

$this->title = Yii::t('writesdown', 'Update Setting: {option_name}', ['option_name' => $model->option_name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Setting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>

<div class="option-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>