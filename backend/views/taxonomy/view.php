<?php
/**
 * @file    view.php.
 * @date    6/4/2015
 * @time    11:59 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Taxonomy */
/* @var $searchModel common\models\Taxonomy */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $term common\models\Term */

$this->title = Yii::t('writesdown', 'View Taxonomy: {taxonomy_name}', ['taxonomy_name' => $model->taxonomy_sn]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="taxonomy-view">

    <?php Pjax::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $this->render('/term/_form', ['model' => $term, 'taxonomy' => $model]); ?>
        </div>
        <div class="col-md-8">
            <?= $this->render('/term/index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'taxonomy' => $model]); ?>
        </div>
    </div>

    <?php Pjax::end(); ?>

</div>