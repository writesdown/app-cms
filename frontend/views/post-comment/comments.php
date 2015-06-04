<?php
/**
 * @file      comments.php.
 * @date      6/4/2015
 * @time      11:25 PM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

use yii\helpers\Html;
use frontend\widgets\comment\PostComment;

/* MODEL */
use common\models\Option;

/* @var $post common\models\Post */
/* @var $comment common\models\MediaComment */
?>
<div id="comment-view">
    <?php
    if ($post->post_comment_count) {
        echo '<h2 class="comment-title">';
        echo Yii::t('writesdown', '{comment_count} {comment_word} on {post_title}', [
            'comment_count' => $post->post_comment_count,
            'comment_word'  => $post->post_comment_count > 1 ? 'Replies' : 'Reply',
            'post_title'    => $post->post_title
        ]);
        echo '</h3>';
        echo PostComment::widget([
            'model' => $post,
            'id'    => 'comments'
        ]);
    }

    if ($post->post_comment_status == 'open') {
        $dateInterval = date_diff(new DateTime($post->post_date), new DateTime('now'));
        if (Option::get('comment_registration') && Yii::$app->user->isGuest) {
            echo '<h3>' . Yii::t('writesdown', 'You must login to leave a reply, ') . Html::a(Yii::t('writesdown', 'Login'), Yii::$app->urlManagerBack->createUrl(['site/login'])) . '</h3>';
        } elseif (Option::get('close_comments_for_old_posts') && $dateInterval->d >= Option::get('close_comments_days_old')) {
            echo '<h3>' . Yii::t('writesdown', 'Comments are closed') . '</h3>';
        } else {
            echo $this->render('_form', [
                'model' => $comment,
                'post'  => $post
            ]);
        }
    } else if ($post->post_comment_count && $post->post_comment_status === 'close') {
        echo '<h3>' . Yii::t('writesdown', 'Comments are closed') . '</h3>';
    }
    ?>
</div>