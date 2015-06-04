<?php
/**
 * @file      reset-password.php.
 * @date      6/4/2015
 * @time      12:06 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title = Yii::t('writesdown', 'Reset Password');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Profile'), 'url' => ['profile']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <p><?= Yii::t('writesdown', 'Please fill out the following fields to reset password:'); ?></p>
    <?= $this->render('_reset-password', [
        'model' => $model,
    ]) ?>

</div>
