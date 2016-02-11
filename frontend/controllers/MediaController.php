<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace frontend\controllers;

use common\models\Media;
use common\models\MediaComment as Comment;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Class MediaController
 *
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
 */
class MediaController extends Controller
{
    /**
     * @param integer|null $id Media ID
     * @param string|null $slug Media slug
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null, $slug = null)
    {
        $render = 'view';

        $comment = new Comment();

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($slug) {
            $model = $this->findModelBySlug($slug);
        } else {
            throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            if (!$comment->parent) {
                $model->comment_count++;
            }
            if ($model->save()) {
                $this->refresh();
            }
        }

        if ($model->password && $model->password !== Yii::$app->request->post('password')) {
            return $this->render('protected', ['media' => $model]);
        }

        if (is_file($this->view->theme->basePath . '/media/view-'
            . substr($model->mime_type, 0, strpos($model->mime_type, '/', 1)) . '.php')
        ) {
            $render = 'view-' . substr($model->mime_type, 0, strpos($model->mime_type, '/', 1));
        }

        return $this->render($render, [
            'media' => $model,
            'metadata' => $model->getMeta('metadata'),
            'comment' => $comment,
        ]);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id Media ID
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $slug Media slug
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($slug)
    {
        if (($model = Media::findOne(['slug' => $slug])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
