<?php
/**
 * @file    view.php.
 * @date    6/4/2015
 * @time    6:34 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PostType */

$this->title = Yii::t('writesdown', 'View Post Type: {post_type_name}', ['post_type_name' => $model->post_type_sn]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Post Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-type-view">

    <p>
        <?= Html::a(Yii::t('writesdown', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-flat btn-primary']) ?>
        <?= Html::a(Yii::t('writesdown', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-flat btn-danger',
            'data'  => [
                'confirm' => Yii::t('writesdown', 'Are you sure you want to delete this item?'),
                'method'  => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            'id',
            'post_type_name',
            'post_type_slug',
            'post_type_description:ntext',
            [
                'attribute' => 'post_type_icon',
                'value'     => Html::tag('i', '', ['class' => $model->post_type_icon]),
                'format'    => 'raw',
            ],
            'post_type_sn',
            'post_type_pn',
            'post_type_smb:boolean',
            'post_type_permission',
        ],
    ]) ?>

</div>

<div class="taxonomy-view">
    <?php if($taxonomies = $model->taxonomies){ ?>
        <h3><?= Yii::t('writesdown', 'Taxonomies'); ?></h3>
        <?php foreach ($taxonomies as $taxonomy){ ?>
            <?= Html::a($taxonomy->taxonomy_name, [
                '/taxonomy/view/',
                'id' => $taxonomy->id
            ], [
                'class' => 'btn btn-xs btn-warning btn-flat'
            ]); ?>
        <?php } ?>
    <?php } ?>
</div>
