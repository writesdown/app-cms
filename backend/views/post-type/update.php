<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $taxonomies [] */
/* @var $model common\models\PostType */
/* @var $taxonomy common\models\Taxonomy */

$this->title = Yii::t('writesdown', 'Update Post Type: {name} ', ['name' => $model->singular_name]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->singular_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('writesdown', 'Update');
?>

<div class="row">
    <div class="col-md-8 post-type-update">
        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>
    </div>
    <div class="col-md-4">
        <?= $this->render('_post-type-taxonomy', [
            'model' => $model,
            'taxonomy' => $taxonomy,
            'taxonomies' => $taxonomies,
        ]) ?>
    </div>
</div>
