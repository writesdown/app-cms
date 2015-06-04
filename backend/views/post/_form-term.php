<?php
/**
 * @file    _form-term.php.
 * @date    6/4/2015
 * @time    6:14 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use dosamigos\selectize\SelectizeAsset;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $postType common\models\PostType */
/* @var $taxonomy common\models\Taxonomy */

SelectizeAsset::register($this);

foreach ($postType->taxonomies as $taxonomy) {
    ?>
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><?= $taxonomy->taxonomy_pn; ?></h3>

            <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            </div>
        </div>

        <div class="box-body">

            <?php
            if ($taxonomy->taxonomy_hierarchical) {
                echo Html::checkboxList('termIds', ArrayHelper::getColumn($model->terms, 'id'), ArrayHelper::map($taxonomy->terms, 'id', 'term_name'), [
                    'class'       => $model->isNewRecord ? 'checkbox' : 'update-taxonomy-hierarchical checkbox',
                    'data-url'    => $model->isNewRecord ? null : Url::to(['/term-relationship/ajax-change-hierarchical']),
                    'data-post_id'=> $model->isNewRecord ? null : $model->id,
                    'separator'   => '<br />',
                ]);
            } else {
                echo Html::dropDownList(
                    'termIds',
                    ArrayHelper::getColumn($model->getTerms()->select(['id'])->where(['taxonomy_id' => $taxonomy->id])->all(), 'id'),
                    ArrayHelper::map($model->getTerms()->select(['id', 'term_name'])->where(['taxonomy_id' => $taxonomy->id])->all(), 'id', 'term_name'),
                    [
                        'class'    => 'term-non-hierarchical form-control ' . $taxonomy->taxonomy_name,
                        'multiple' => 'multiple',
                        'data'     => [
                            'taxonomy_id' => $taxonomy->id
                        ]
                    ]
                );
            }
            ?>

        </div>
        <div class="box-footer">

            <?php if ($taxonomy->taxonomy_hierarchical) {

                if (Yii::$app->user->can('editor')) {
                    ?>

                    <div class="input-group" data-url="<?= Url::to(['/term/ajax-create-hierarchical']) ?>">

                        <?= Html::textInput('Term[term_name]', '', [
                            'class'       => 'posttermhierarchical-term_name form-control',
                            'placeholder' => Yii::t('writesdown', '{taxonomyName} name', ['taxonomyName' => $taxonomy->taxonomy_sn])
                        ]); ?>

                        <?= Html::hiddenInput('Term[taxonomy_id]', $taxonomy->id); ?>

                        <?= Html::hiddenInput('_csrf', Yii::$app->request->csrfToken); ?>

                        <?= !$model->isNewRecord ? Html::hiddenInput('TermRelationship[post_id]', $model->id) : ''; ?>

                        <div class="input-group-btn">
                            <?= Html::button(Html::tag('i', '', ['class' => 'fa fa-plus']), [
                                'class' => 'btn btn-flat btn-primary ajax-create-hierarchical-term',
                            ]); ?>
                        </div>

                    </div>

                <?php
                } else {
                    echo Yii::t('writesdown', 'Choose the {taxonomyName} above', ['taxonomyName' => $taxonomy->taxonomy_sn]);
                }

            } else {
                echo Yii::t('writesdown', 'Add {taxonomyName} via text box above', ['taxonomyName' => $taxonomy->taxonomy_sn]);
            }
            ?>

        </div>
    </div>
<?php
}
/* If model is not new record, the selectize add change term relationship via ajax */
$onItemRemove = null;
$onItemAdd = null;
if (!$model->isNewRecord) {
    $onItemRemove = '
    onItemRemove: function (value) {
        $.ajax({
            url: "' . Url::to(['/term-relationship/ajax-delete-non-hierarchical']) . '",
            data: {
                TermRelationship: {post_id: "' . $model->id . '", term_id: value},
                _csrf: yii.getCsrfToken()
            },
            type: "POST"
        });
    },';
    $onItemAdd = '
    onItemAdd: function (value) {
        $.ajax({
            url: "' . Url::to(['/term-relationship/ajax-create-non-hierarchical']) . '",
            data: {
                TermRelationship: {post_id: "' . $model->id . '", term_id: value},
                _csrf: yii.getCsrfToken()
            },
            type: "POST"
        });
    },';
}

/* Register script for term of non-hierarchical taxonomy */
$this->registerJs('
(function($){
    $(".term-non-hierarchical").each(function(){
        var _this = $(this);
        $(this).selectize({
            valueField: "id",
            labelField: "term_name",
            searchField: "term_name",
            delimiter: ",",
            plugins: ["remove_button"],
            create: function (input, callback) {
                var _thisselectize = this;
                $.ajax({
                    url: "' . Url::to(['/term/ajax-create-non-hierarchical']) . '",
                    data: { Term: { term_name: input, taxonomy_id: _this.data("taxonomy_id") }, _csrf: yii.getCsrfToken() },
                    dataType: "json",
                    type: "POST",
                    success: function (response) {
                       callback(response);
                    }
                });
            },
            ' . $onItemAdd . '
            ' . $onItemRemove . '
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: "' . Url::to(['/term/ajax-search']) . '",
                    type: "POST",
                    dataType: "json",
                    data: {
                        Term: {
                            term_name: query,
                            taxonomy_id: _this.data("taxonomy_id")
                        },
                        _csrf: yii.getCsrfToken()
                    },
                    error: function() {
                        callback();
                    },
                    success: function(response) {
                        callback(response);
                    }
                });
            }
        });
    });
})(jQuery);
');