<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\Taxonomy */

$this->title = Yii::t('writesdown', 'Update Taxonomy: {name}', ['name' => $model->singular_name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->singular_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>
<div class="taxonomy-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
