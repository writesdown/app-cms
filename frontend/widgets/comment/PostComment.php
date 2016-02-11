<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\widgets\comment;

use common\models\PostComment as Comment;
use yii\data\Pagination;

/**
 * Class PostComment
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
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
            ->select(['id', 'author', 'email', 'url', 'date', 'content'])
            ->andWhere(['parent' => 0, 'post_id' => $this->model->id, 'status' => 'approved'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->orderBy(['id' => $this->commentOrder]);

        $countQuery = clone $query;

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => $this->pageSize,
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
     * @return array|null
     */
    protected function getChildren($id)
    {
        /* @var $models \common\models\PostComment[] */
        $comments = [];
        $models = Comment::find()
            ->select(['id', 'author', 'email', 'url', 'date', 'content'])
            ->andWhere(['parent' => $id, 'post_id' => $this->model->id, 'status' => 'approved'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
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
