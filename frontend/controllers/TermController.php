<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use common\models\Option;
use common\models\Term;
use Yii;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class TermController
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class TermController extends Controller
{
    /**
     * Displays a single Term model.
     *
     * @param integer $id Term ID
     * @param null $slug Term slug
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionView($id = null, $slug = null)
    {
        $render = 'view';

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($slug) {
            $model = $this->findModelBySlug($slug);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        $query = $model->getPosts()
            ->andWhere(['status' => 'publish'])
            ->andWhere(['<=', 'date', date('Y-m-d H:i:s')])
            ->orderBy(['date' => SORT_DESC]);
        $countQuery = clone $query;
        $pages = new Pagination([
            'totalCount' => $countQuery->count(),
            'pageSize' => Option::get('posts_per_page'),
        ]);
        $posts = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        if (is_file($this->view->theme->basePath . '/term/view-' . $model->taxonomy->name . '.php')) {
            $render = 'view-' . $model->taxonomy->name;
        }

        return $this->render($render, [
            'posts' => $posts,
            'pages' => $pages,
            'term' => $model,
        ]);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id Term ID
     * @return Term the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Term::findOne(['id' => $id]);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $slug Term Slug
     * @throws \yii\web\NotFoundHttpException
     * @internal param string $postslug
     * @return Term the loaded model
     */
    protected function findModelBySlug($slug)
    {
        $model = Term::findOne(['slug' => $slug]);

        if ($model) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
