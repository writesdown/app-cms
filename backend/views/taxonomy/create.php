<?php
/**
 * @file      create.php.
 * @date      6/4/2015
 * @time      11:58 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

/* @var $this yii\web\View */
/* @var $model common\models\Taxonomy */

$this->title = Yii::t('writesdown', 'Add New Taxonomy');
$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Taxonomies'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="taxonomy-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
