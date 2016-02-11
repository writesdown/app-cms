<?php
/**
 * @link http://www.writesdown.com/
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $postType common\models\PostType */
?>
<?php foreach ($postType->taxonomies as $taxonomy): ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $taxonomy->plural_name ?></h3>

            <div class="box-tools pull-right">
                <a href="#" data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
            </div>
        </div>
        <div class="box-body">

            <?php if ($taxonomy->hierarchical): ?>
                <?= Html::checkboxList(
                    'termIds',
                    ArrayHelper::getColumn($model->terms, 'id'),
                    ArrayHelper::map($taxonomy->getTerms()->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'),
                    [
                        'class' => $model->isNewRecord
                            ? 'checkbox'
                            : 'checkbox term-relationship taxonomy-hierarchical',
                        'data-url' => $model->isNewRecord
                            ? null
                            : Url::to(['/term-relationship/ajax-change-hierarchical']),
                        'data-post_id' => $model->isNewRecord ? null : $model->id,
                        'separator' => '<br />',
                    ]
                ) ?>

            <?php else: ?>
                <?= Html::dropDownList(
                    'termIds',
                    ArrayHelper::getColumn(
                        $model->getTerms()->select(['id'])->where(['taxonomy_id' => $taxonomy->id])->all(),
                        'id'
                    ),
                    ArrayHelper::map(
                        $model->getTerms()->select(['id', 'name'])->where(['taxonomy_id' => $taxonomy->id])->all(),
                        'id',
                        'name'
                    ),
                    [
                        'class' => 'form-control term-relationship taxonomy-not-hierarchical',
                        'multiple' => 'multiple',
                        'data' => ['taxonomy_id' => $taxonomy->id, ],
                    ]
                ) ?>

            <?php endif ?>

        </div>
        <div class="box-footer">

            <?php if ($taxonomy->hierarchical): ?>
                <?php if (Yii::$app->user->can('editor')): ?>
                    <div class="input-group" data-url="<?= Url::to(['/term/ajax-create-hierarchical']) ?>">
                        <?= Html::textInput('Term[name]', '', [
                            'class' => 'form-control term taxonomy-not-hierarchical ajax-create-term',
                            'placeholder' => Yii::t(
                                'writesdown', '{taxonomyName} name',
                                ['taxonomyName' => $taxonomy->singular_name]
                            ),
                        ]) ?>

                        <?= Html::hiddenInput('Term[taxonomy_id]', $taxonomy->id) ?>

                        <?= Html::hiddenInput('_csrf', Yii::$app->request->csrfToken) ?>

                        <?= !$model->isNewRecord ? Html::hiddenInput('TermRelationship[post_id]', $model->id) : '' ?>

                        <div class="input-group-btn">
                            <?= Html::button(
                                '<i class="fa fa-plus"></i>',
                                ['class' => 'btn btn-flat btn-primary term taxonomy-not-hierarchical ajax-create-term']
                            ) ?>

                        </div>
                    </div>
                <?php else: ?>
                    <?= Yii::t(
                        'writesdown', 'Choose the {taxonomyName} above',
                        ['taxonomyName' => $taxonomy->singular_name]
                    ) ?>

                <?php endif ?>
            <?php else: ?>
                <?= Yii::t(
                    'writesdown', 'Add {taxonomyName} via text box above',
                    ['taxonomyName' => $taxonomy->singular_name]
                ) ?>

            <?php endif ?>

        </div>
    </div>
<?php endforeach ?>
<?= $this->render('_form-term-js', ['model' => $model]) ?>
