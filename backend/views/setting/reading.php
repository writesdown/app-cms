<?php
/**
 * @file      reading.php.
 * @date      6/4/2015
 * @time      11:51 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use dosamigos\selectize\SelectizeAsset;

/* MODEL */
use common\models\PostType;
use common\models\Post;

/* @var $this yii\web\View */
/* @var $model object */
/* @var $form yii\widgets\ActiveForm */
/* @var $group string */

$this->title = Yii::t('writesdown', '{group} Settings', ['group' => ucwords($group)]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('writesdown', 'Settings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

SelectizeAsset::register($this);
?>

    <div class="options-form">

        <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal']]); ?>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Front page displays'), null, ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::radioList('Option[show_on_front][option_value]', $model->show_on_front->option_value, [
                    'posts' => Yii::t('writesdown', 'Latest posts'),
                ], [
                    'separator' => '<br />',
                    'class'     => 'radio'
                ]); ?>
                <?= Html::label(Yii::t('writesdown', 'Front page: '), 'option-front_page'); ?>
                <?= Html::dropDownList('Option[front_post_type][option_value]', $model->front_post_type->option_value,
                    ArrayHelper::merge(['all' => 'All'], ArrayHelper::map(PostType::find()->all(), 'post_type_name', 'post_type_sn')), ['class' => 'form-control']);
                ?>
            </div>

        </div>

        <div class="form-group">
            <div class="col-sm-7 col-sm-push-2">
                <?= Html::radioList('Option[show_on_front][option_value]', $model->show_on_front->option_value, [
                    'page' => Yii::t('writesdown', 'Static page'),
                ], [
                    'separator' => '<br />',
                    'class'     => 'radio'
                ]); ?>
                <?= Html::label(Yii::t('writesdown', 'Front page: '), 'option-front_page'); ?>
                <?= Html::dropDownList('Option[front_page][option_value]', $model->front_page->option_value,
                    Post::findOne($model->front_page->option_value) ?
                        ArrayHelper::map(Post::find()->select(['id', 'post_title'])
                            ->where(['id' => $model->front_page->option_value])
                            ->all(), 'id', 'post_title')
                        : [], ['class' => 'search-post', 'disabled']
                ); ?>

                <?= Html::label(Yii::t('writesdown', 'Posts page: '), 'option-posts_page'); ?>
                <?= Html::dropDownList('Option[posts_page][option_value]', $model->posts_page->option_value,
                    Post::findOne($model->posts_page->option_value) ?
                        ArrayHelper::map(Post::find()->select(['id', 'post_title'])
                            ->where(['id' => $model->posts_page->option_value])
                            ->all(), 'id', 'post_title')
                        : [], ['class' => 'search-post', 'disabled']
                ); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Posts per page'), 'option-post_per_page', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::input('number', 'Option[posts_per_page][option_value]', $model->posts_per_page->option_value, ['id' => 'option-post_per_page', 'min' => 1, 'step' => 1]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Posts per RSS'), 'option-post_per_rss', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <?= Html::input('number', 'Option[posts_per_rss][option_value]', $model->posts_per_rss->option_value, ['id' => 'option-post_per_rss', 'min' => 1, 'step' => 1]); ?>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'For each article in a feed, show'), null, ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <div class="radio">
                    <?= Html::radioList('Option[rss_use_excerpt][option_value]', $model->rss_use_excerpt->option_value, [
                        0 => 'Full text',
                        1 => 'Summary',
                    ], ['separator' => '<br />']); ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <?= Html::label(Yii::t('writesdown', 'Search Engine Visibility'), 'option-site_indexing', ['class' => 'col-sm-2 control-label']); ?>
            <div class="col-sm-7">
                <div class="checkbox">
                    <?= Html::label(Html::checkbox('Option[disable_site_indexing][option_value]', $model->disable_site_indexing->option_value, ['id' => 'option-site_indexing', 'uncheck' => 0]) . Yii::t('writesdown', 'Do not allow search engines to index the site')); ?>
                </div>
                <p class="description"><?= Yii::t('writesdown', "It's up to the search engines to honor this request.") ?></p>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <?= Html::submitButton(Yii::t('writesdown', 'Save'), ['class' => 'btn btn-flat btn-success']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php $this->registerJs('
(function($){
    $(document).ready(function(){
        $(".search-post").selectize({"valueField":"id","labelField":"post_title","searchField":"post_title","load":function (query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: "' . Url::to(['post/ajax-search']) . '",
                type: "POST",
                dataType: "json",
                data: {
                    post_title: query,
                    _csrf: yii.getCsrfToken()
                },
                error: function() {
                    callback();
                },
                success: function(response) {
                    callback(response);
                }
            });
        }});
    });
})(jQuery);
');