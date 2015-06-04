<?php
/**
 * @file      _form.php.
 * @date      6/4/2015
 * @time      11:25 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* MODEL */
use common\models\Option;


/* @var $post common\models\Post */
/* @var $model common\models\PostComment */

?>

<div id="respond" class="post-comment-form">

    <h3 class="reply-title">
        <?= Yii::t('writesdown', 'Leave a Reply'); ?>
    </h3>

    <?php
    if (!Yii::$app->user->isGuest) {
        echo Html::tag('p', Yii::t('writesdown', 'Login as {username}, {logout}{cancel-reply}', [
            'username'     => '<strong>' . Yii::$app->user->identity->username . '</strong>',
            'logout'       => Html::a(Yii::t('writesdown', '<strong>Sign Out</strong>'), ['/site/logout'], ['data-method' => 'post']),
            'cancel-reply' => Html::a('<strong>' . Yii::t('writesdown', ', Cancel Reply') . '</strong>', '#', [
                'id'    => 'cancel-reply',
                'class' => 'cancel-reply',
                'style' => 'display:none;'
            ])
        ]));
    } else {
        echo Html::tag('p', Html::a('<strong>' . Yii::t('writesdown', 'Cancel Reply') . '</strong>', '#', [
            'id'    => 'cancel-reply',
            'class' => 'cancel-reply',
            'style' => 'display:none;'
        ]));
    }
    ?>

    <?php $form = ActiveForm::begin(); ?>

    <?php if (Yii::$app->user->isGuest && Option::get('require_name_email')) { ?>

        <?= $form->field($model, 'comment_author')->textInput() ?>

        <?= $form->field($model, 'comment_author_email')->textInput(['maxlength' => 100]) ?>

        <?= $form->field($model, 'comment_author_url')->textInput(['maxlength' => 255]) ?>

    <?php } ?>

    <?= Html::activeHiddenInput($model, 'comment_parent', ['value' => 0, 'class' => 'comment-parent-field']); ?>

    <?= Html::activeHiddenInput($model, 'comment_post_id', ['value' => $post->id]); ?>

    <?= $form->field($model, 'comment_content')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('writesdown', 'Submit'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>