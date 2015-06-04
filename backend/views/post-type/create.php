<?php
/**
 * @file      create.php.
 * @date      6/4/2015
 * @time      6:33 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\PostType */
/* @var $taxonomy common\models\Taxonomy */
/* @var $taxonomies [] */

$this->title = Yii::t('writesdown', 'Add New Post Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-8 post-type-create">

        <?= $this->render('_form', [
            'model' => $model,
        ]) ?>

    </div>

    <div class="col-md-4">

        <?= $this->render('_post-type-taxonomy', [
            'model'      => $model,
            'taxonomy'   => $taxonomy,
            'taxonomies' => $taxonomies
        ]) ?>

    </div>

</div>
