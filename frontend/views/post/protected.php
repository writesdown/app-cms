<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $post common\models\Post */
/* @var $comment common\models\PostComment */
/* @var $category common\models\Term */

$this->title = Html::encode($post->post_title . ' - ' . Option::get('sitetitle'));
$this->params['breadcrumbs'][] = [
    'label' => Html::encode($post->postType->post_type_sn),
    'url'   => ['/post/index', 'id' => $post->postType->id],
];
$category = $post->getTerms()->innerJoinWith(['taxonomy'])->andWhere(['taxonomy_slug' => 'category'])->one();

if ($category) {
    $this->params['breadcrumbs'][] = ['label' => Html::encode($category->term_name), 'url' => $category->url];
}

$this->params['breadcrumbs'][] = Html::encode($post->post_title);
?>

<div class="single post-protected">
    <article class="hentry">
        <header class="entry-header page-header">
            <h1 class="entry-title"><?= Html::encode($post->post_title) ?></h1>

        </header>
        <div class="entry-content">
            <?php $form = ActiveForm::begin() ?>

            <p>
                <?= Yii::t(
                    'writesdown',
                    '{post_title} is protected, please submit the right password to view the {post_type}.',
                    [
                        'post_title' => Html::encode($post->post_title),
                        'post_type'  => Html::encode($post->postType->post_type_sn),
                    ]
                ) ?>

            </p>
            <div class="form-group field-post-post_password required">
                <?= Html::label(
                    Yii::t('writesdown', 'Password'),
                    'post-post_password',
                    ['class' => 'control-label']
                ) ?>

                <?= Html::passwordInput('password', null, [
                    'class' => 'form-control',
                    'id'    => 'post-post_password',
                ]) ?>

            </div>

            <div class="form-group">
                <?= Html::submitButton(
                    Yii::t('writesdown', 'Submit Password'),
                    ['class' => 'btn btn-flat btn-primary']
                ) ?>

            </div>

            <?php ActiveForm::end() ?>
        </div>
    </article>
</div>
