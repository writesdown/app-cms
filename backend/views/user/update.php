<?php
/**
 * @file      update.php.
 * @date      6/4/2015
 * @time      12:07 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('writesdown', 'Update User: {username}', ['username' => $model->username]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
