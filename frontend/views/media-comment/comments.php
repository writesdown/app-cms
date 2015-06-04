<?php
/**
 * @file      comments.php.
 * @date      6/4/2015
 * @time      11:22 PM
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
    <?php
    if ($media->media_comment_count) {
        echo '<h2 class="comment-title">';
        echo Yii::t('writesdown', '{comment_count} {comment_word} on {media_title}', [
            'comment_count' => $media->media_comment_count,
            'comment_word'  => $media->media_comment_count > 1 ? 'Replies' : 'Reply',
            'media_title'   => $media->media_title
        ]);
        echo '</h3>';
        echo MediaComment::widget([
            'model' => $media,
            'id'    => 'comments'
        ]);
    }

    if ($media->media_comment_status == 'open') {
        $dateInterval = date_diff(new DateTime($media->media_date), new DateTime('now'));
        if (Option::get('comment_registration') && Yii::$app->user->isGuest) {
            echo '<h3>' . Yii::t('writesdown', 'You must login to leave a reply, ') . Html::a(Yii::t('writesdown', 'Login'), Yii::$app->urlManagerBack->createUrl(['site/login'])) . '</h3>';
        } elseif (Option::get('close_comments_for_old_medias') && $dateInterval->d >= Option::get('close_comments_days_old')) {
            echo '<h3>' . Yii::t('writesdown', 'Comments are closed') . '</h3>';
        } else {
            echo $this->render('_form', [
                'model' => $comment,
                'media' => $media
            ]);
        }
    } else if ($media->media_comment_count && $media->media_comment_status === 'close') {
        echo '<h3>' . Yii::t('writesdown', 'Comments are closed') . '</h3>';
    }
    ?>
</div>