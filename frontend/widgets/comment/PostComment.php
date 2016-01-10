<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\widgets\comment;

use common\models\PostComment as Comment;
use yii\data\Pagination;

/**
 * Class PostComment
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class PostComment extends BaseComment
{
    /**
     * Set comment and pagination.
     * Select all comments from database and create pagination.
     * Get child of current comment.
     */
    protected function setComments()
    {
        /* @var $models \common\models\BaseComment */
        $comments = [];

        $query = Comment::find()
            ->select([
                'id',
                'comment_author',
                'comment_author_email',
                'comment_author_url',
                'comment_date',
                'comment_content',
            ])
            ->andWhere(['comment_parent' => 0])
            ->andWhere(['comment_post_id' => $this->model->id])
            ->andWhere(['comment_approved' => 'approved'])
            ->orderBy(['id' => $this->commentOrder]);

        $countQuery = clone $query;

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => $this->pageSize,
        ]);

        $this->pages = $pages;

        $models = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        foreach ($models as $model) {
            $comments[$model->id] = $model;
            $comments[$model->id]['child'] = $this->getChildren($model->id);
        }

        $this->comments = $comments;
    }

    /**
     * Get comment children based on comment ID.
     *
     * @param int $id
     *
     * @return array|null
     */
    protected function getChildren($id)
    {
        /* @var $models \common\models\PostComment[] */
        $comments = [];
        $models = Comment::find()
            ->select([
                'id',
                'comment_author',
                'comment_author_email',
                'comment_author_url',
                'comment_date',
                'comment_content',
            ])
            ->andWhere(['comment_parent' => $id])
            ->andWhere(['comment_post_id' => $this->model->id])
            ->andWhere(['comment_approved' => 'approved'])
            ->orderBy(['id' => $this->commentOrder])
            ->all();

        if (empty($models)) {
            $comments = null;
        } else {
            foreach ($models as $model) {
                $comments[$model->id] = $model;
                $comments[$model->id]['child'] = $this->getChildren($model->id);
            }
        }

        return $comments;
    }
}
