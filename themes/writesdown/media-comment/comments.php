<?php
/**
 * @link      http://www.writesdown.com/
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use common\models\Option;
use frontend\widgets\comment\MediaComment;
use yii\helpers\Html;

/* @var $comment common\models\MediaComment */
/* @var $media common\models\Media */

?>
<div id="comment-view">

    <?php if ($media->media_comment_count): ?>
        <h2 class="comment-title">
            <?= Yii::t('writesdown', '{comment_count} {comment_word} on {media_title}', [
                'comment_count' => $media->media_comment_count,
                'comment_word'  => $media->media_comment_count > 1 ? 'Replies' : 'Reply',
                'media_title'   => $media->media_title,
            ]) ?>

        </h2>

        <?= MediaComment::widget(['model' => $media, 'id' => 'comments']) ?>

    <?php endif ?>

    <?php if ($media->media_comment_status == 'open'): ?>
        <?php $dateInterval = date_diff(new DateTime($media->media_date), new DateTime('now')) ?>
        <?php if (Option::get('comment_registration') && Yii::$app->user->isGuest): ?>
            <h3>
                <?= Yii::t('writesdown', 'You must login to leave a reply, ') ?>

                <?= Html::a(Yii::t('writesdown', 'Login'), Yii::$app->urlManagerBack->createUrl(['site/login'])) ?>

            </h3>
        <?php elseif (Option::get('close_comments_for_old_posts')
            && $dateInterval->d >= Option::get('close_comments_days_old')
        ): ?>
            <h3><?= Yii::t('writesdown', 'Comments are closed') ?></h3>;
        <?php else: ?>
            <?= $this->render('_form', [
                'model' => $comment,
                'media' => $media,
            ]) ?>
        <?php endif ?>
    <?php elseif ($media->media_comment_count && $media->media_comment_status === 'close'): ?>
        <h3><?= Yii::t('writesdown', 'Comments are closed') ?></h3>;
    <?php endif ?>

</div>
