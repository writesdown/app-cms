<?php
/**
 * @file      update.php.
 * @date      6/4/2015
 * @time      11:59 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\Taxonomy */

$this->title = Yii::t('writesdown', 'Update Taxonomy: {taxonomy_name}', ['taxonomy_name' => $model->taxonomy_sn]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->taxonomy_sn, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>
<div class="taxonomy-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
