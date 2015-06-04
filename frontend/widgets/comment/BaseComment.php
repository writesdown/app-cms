<?php
/**
 * @file    BaseComment.php.
 * @date    6/4/2015
 * @time    11:16 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\widgets\comment;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use cebe\gravatar\Gravatar;

/* MODEL */
use common\models\Option;

/**
 * Class BaseComment
 *
 * @package frontend\widgets\comment
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
abstract class BaseComment extends Widget
{
    /**
     * @var string
     */
    public $tag = 'ul';
    /**
     * @var
     */
    public $model;
    /**
     * @var
     */
    public $enableThreadComments;
    /**
     * @var
     */
    public $maxDepth;
    /**
     * @var
     */
    public $commentOrder;
    /**
     * @var int
     */
    public $avatarSize = 48;
    /**
     * @var array
     */
    public $options = ['class' => 'comments'];
    /**
     * @var array
     */
    public $childOptions = ['class' => 'child'];
    /**
     * @var array
     */
    public $itemOptions = ['class' => 'media'];

    /**
     * @var
     */
    protected $pageSize;
    /**
     * @var
     */
    protected $pages;
    /**
     * @var
     */
    protected $comments;
    /**
     * @var
     */
    protected $tagItem;

    /**
     * @inheritdoc
     */
    public function init()
    {
        switch ($this->tag) {
            case 'div':
                $this->tagItem = 'div';
                break;
            case 'ul':
            case 'li':
                $this->tagItem = 'li';
                break;
        }

        if (!$this->pageSize)
            $this->pageSize = Option::get('comments_per_page');

        if (!$this->maxDepth)
            $this->maxDepth = Option::get('thread_comments_depth');

        if (!$this->commentOrder)
            $this->commentOrder = Option::get('comment_order') === 'asc' ? SORT_ASC : SORT_DESC;

        if (!$this->enableThreadComments)
            $this->enableThreadComments = Option::get('thread_comments');

        Html::addCssClass($this->itemOptions, 'comment');
        $this->setComments();
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->comments) {
            Pjax::begin();

            echo Html::beginTag($this->tag, ArrayHelper::merge(['id' => $this->id], $this->options));
            $this->renderComments($this->comments);
            echo Html::endTag($this->tag, $this->options);
            echo Html::beginTag('nav', ['class' => 'comment-pagination']);
            echo LinkPager::widget([
                'pagination'           => $this->pages,
                'activePageCssClass'   => 'active',
                'disabledPageCssClass' => 'disabled',
                'options'              => [
                    'class' => 'pagination'
                ]
            ]);
            echo Html::endTag('nav');
            Pjax::end();
        }
    }

    /**
     * @param     $comment
     * @param int $depth
     *
     * @throws \Exception
     */
    protected function displayComment($comment, $depth = 0)
    {
        echo Html::beginTag('div', ['id' => 'comment-' . $comment->id, 'class' => $comment->child ? 'parent depth-' . $depth : 'depth-' . $depth]);
        ?>

        <?php
        if (Option::get('show_avatars')) {
            ?>
            <div class="media-left avatar">
                <?= Gravatar::widget([
                    'email'        => $comment->comment_author_email,
                    'options'      => [
                        'alt'    => $comment->comment_author,
                        'class'  => 'avatar',
                        'width'  => $this->avatarSize,
                        'height' => $this->avatarSize,
                    ],
                    'defaultImage' => Option::get('avatar_default'),
                    'rating'       => Option::get('avatar_rating'),
                    'size'         => $this->avatarSize
                ]); ?>
            </div>
        <?php
        }
        ?>
        <div class="media-body comment-body">
            <p class="meta">
                <strong class="author vcard">
                    <span class="fn">
                        <?= $comment->comment_author ? $comment->comment_author : \Yii::t('writesdown', 'Anonymous'); ?>
                    </span>
                </strong>
                -
                <time class="date published" datetime="<?= \Yii::$app->formatter->asDatetime($comment->comment_date) ?>">
                    <?= \Yii::$app->formatter->asDate($comment->comment_date); ?>
                </time>
                <?php
                if ($depth < $this->maxDepth && $this->enableThreadComments) {
                    echo Html::a(\Yii::t('writesdown', 'Reply'), '#', ['class' => 'comment-reply-link', 'data-id' => $comment->id]);
                }
                ?>
            </p>
            <div class="comment-content">
                <?= $comment->comment_content; ?>
            </div>
        </div>
        <?php
        echo Html::endTag('div');
    }

    /**
     * @param     $comments
     * @param int $depth
     */
    protected function renderComments($comments, $depth = 0)
    {
        foreach ($comments as $comment) {
            echo Html::beginTag($this->tagItem, $this->itemOptions);
            $this->displayComment($comment, $depth);
            if ($comment->child) {
                $depth++;
                if ($depth <= $this->maxDepth && $this->enableThreadComments) {
                    echo Html::beginTag($this->tag, $this->childOptions);
                    $this->renderComments($comment->child, $depth);
                    echo Html::endTag($this->tag);
                }
                $depth--;
            }

            echo Html::endTag($this->tagItem);
        }
    }

    /**
     * Get comment children.
     */
    protected function getChildren(){}

    /**
     * Set comment model and pagination.
     */
    protected function setComments(){}
}