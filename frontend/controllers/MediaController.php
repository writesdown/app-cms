<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
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
 * @package frontend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class MediaController extends Controller
{
    /**
     * @param integer|null $id
     * @param string|null  $media_slug
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($id = null, $media_slug = null)
    {
        $render = 'view';

        $comment = new Comment();

        if ($id) {
            $model = $this->findModel($id);
        } elseif ($media_slug) {
            $model = $this->findModelBySlug($media_slug);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if ($comment->load(Yii::$app->request->post()) && $comment->save()) {
            if (!$comment->comment_parent) {
                $model->media_comment_count++;
            }
            if ($model->save()) {
                $this->refresh();
            }
        }

        if ($model->media_password && $model->media_password !== Yii::$app->request->post('password')) {
            return $this->render('protected', ['media' => $model]);
        }

        if (is_file($this->view->theme->basePath . '/media/view-'
            . substr($model->media_mime_type, 0, strpos($model->media_mime_type, '/', 1)) . '.php')
        ) {
            $render = 'view-' . substr($model->media_mime_type, 0, strpos($model->media_mime_type, '/', 1));
        }

        return $this->render($render, [
            'media'    => $model,
            'metadata' => $model->getMeta('metadata'),
            'comment'  => $comment,
        ]);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $media_slug
     *
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($media_slug)
    {
        if (($model = Media::findOne(['media_slug' => $media_slug])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
