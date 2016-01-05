<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use common\models\Option;
use common\models\Term;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class TermController
 *
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class TermController extends Controller
{
    /**
     * Displays a single Term model.
     *
     * @param integer $id
     * @param null    $term_slug
     *
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionView($id = null, $term_slug = null)
    {
        $render = 'view';

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($term_slug) {
            $model = $this->findModelBySlug($term_slug);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $model->getPosts()->andWhere(['post_status' => 'publish'])->orderBy(['id' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize'   => Option::get('posts_per_page'),
        ]);
        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        if (is_file($this->view->theme->basePath . '/term/view-' . $model->taxonomy->taxonomy_slug . '.php')) {
            $render = 'view-' . $model->taxonomy->taxonomy_slug;
        }

        return $this->render($render, [
            'posts' => $posts,
            'pages' => $pages,
            'term'  => $model,
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Term the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Term::findOne(['id' => $id]);

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $term_slug
     *
     * @throws \yii\web\NotFoundHttpException
     * @internal param string $post_slug
     *
     * @return Term the loaded model
     */
    protected function findModelBySlug($term_slug)
    {
        $model = Term::findOne(['term_slug' => $term_slug]);

        if ($model) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
