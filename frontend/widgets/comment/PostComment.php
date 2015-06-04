<?php
/**
 * @file    PostComment.php.
 * @date    6/4/2015
 * @time    11:21 PM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\widgets\comment;

use yii\data\Pagination;
use common\models\PostComment as Comment;

/**
 * Class PostComment
 *
 * @package frontend\widgets\comment
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class PostComment extends BaseComment{

    /**
     * Set comment and pagination.
     * Select all comments from database and create pagination.
     * Get child of current comment.
     */
    protected function setComments(){

        $comments = [];

        $query = Comment::find()->select(['id', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_date', 'comment_content'])
            ->andWhere(['comment_parent' => 0])
            ->andWhere(['comment_post_id' => $this->model->id])
            ->andWhere(['comment_approved' => 'approved'])
            ->orderBy(['id' => $this->commentOrder]);

        $countQuery = clone $query;

        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => $this->pageSize
        ]);

        $this->pages = $pages;

        $models = $query->offset($pages->offset)
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
     * @param $id
     *
     * @return array|null
     */
    protected function getChildren($id){
        /* @var $models \common\models\PostComment[] */

        $comments = [];

        $models = Comment::find()->select(['id', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_date', 'comment_content'])
            ->andWhere(['comment_parent' => $id])
            ->andWhere(['comment_post_id' => $this->model->id])
            ->andWhere(['comment_approved' => 'approved'])
            ->orderBy(['id' => $this->commentOrder])
            ->all();

        if(empty($models)){
            $comments = null;
        } else{
            /* @var $model \common\models\PostComment */
            foreach ($models as $model) {
                $comments[$model->id] = $model;
                $comments[$model->id]['child'] = $this->getChildren($model->id);
            }
        }

        return $comments;
    }
}