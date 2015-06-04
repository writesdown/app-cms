<?php
/**
 * @file    update.php.
 * @date    6/4/2015
 * @time    6:33 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $taxonomies [] */
/* @var $model common\models\PostType */
/* @var $taxonomy common\models\Taxonomy */

$this->title = Yii::t('writesdown', 'Update Post Type: {post_type_name} ', ['post_type_name' => $model->post_type_sn]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->post_type_sn, 'url' => ['view', 'id' => $model->id]];
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
            'model'     => $model,
            'taxonomy'   => $taxonomy,
            'taxonomies' => $taxonomies
        ]) ?>

    </div>

</div>
