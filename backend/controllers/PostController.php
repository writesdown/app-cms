<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\models\Option;
use common\models\Post;
use common\models\PostType;
use common\models\search\Post as PostSearch;
use common\models\Term;
use common\models\TermRelationship;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * PostController implements the CRUD actions for Post model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class PostController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'bulk-action', 'ajax-search'],
                        'allow'   => true,
                        'roles'   => ['subscriber'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'      => ['post'],
                    'bulk-action' => ['post'],
                    'ajax-search' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models on specific post type.
     * If there is user, the action will generate list of all Post models based on user.
     *
     * @param integer     $post_type
     * @param null|string $user
     *
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionIndex($post_type, $user = null)
    {
        $postType = $this->findPostType($post_type);
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $post_type, $user);

        if (!Yii::$app->user->can($postType->post_type_permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'postType'     => $postType,
            'user'         => $user,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @param integer $post_type post_type_id
     *
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionCreate($post_type)
    {
        $model = new Post();
        $postType = $this->findPostType($post_type);
        $model->post_comment_status = Option::get('default_comment_status');

        if (!Yii::$app->user->can($postType->post_type_permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->post_type = $postType->id;
            $model->post_date = Yii::$app->formatter->asDatetime($model->post_date, 'php:Y-m-d H:i:s');
            if ($model->save()) {
                if ($termIds = Yii::$app->request->post('termIds')) {
                    foreach ($termIds as $termId) {
                        $termRelationship = new TermRelationship();
                        $termRelationship->setAttributes([
                            'term_id' => $termId,
                            'post_id' => $model->id,
                        ]);
                        if ($termRelationship->save() && $term = $this->findTerm($termId)) {
                            $term->updateAttributes(['term_count' => $term->term_count++]);
                        }
                    }
                }
                if ($meta = Yii::$app->request->post('meta')) {
                    foreach ($meta as $meta_name => $meta_value) {
                        $model->setMeta($meta_name, $meta_value);
                    }
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', '{post_type} successfully saved.', [
                    'post_type' => $postType->post_type_sn,
                ]));

                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model'    => $model,
            'postType' => $postType,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $postType = $model->postType;

        if (!$model->postType || !Yii::$app->user->can($model->postType->post_type_permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        } elseif (!Yii::$app->user->can('editor') && Yii::$app->user->id !== $model->post_author) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        } elseif (!Yii::$app->user->can('author') && $model->post_status !== $model::POST_STATUS_REVIEW) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->post_date = Yii::$app->formatter->asDatetime($model->post_date, 'php:Y-m-d H:i:s');
            if ($model->save()) {
                if ($meta = Yii::$app->request->post('meta')) {
                    foreach ($meta as $meta_name => $meta_value) {
                        $model->setMeta($meta_name, $meta_value);
                    }
                }
                Yii::$app->getSession()->setFlash('success', Yii::t('writesdown', '{post_type} successfully saved.', [
                    'post_type' => $postType->post_type_sn,
                ]));

                return $this->redirect(['post/update', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'model'    => $model,
            'postType' => $postType,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @throws \Exception
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (!$model->postType || !Yii::$app->user->can($model->postType->post_type_permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        } elseif (!Yii::$app->user->can('editor') && Yii::$app->user->id !== $model->post_author) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        } elseif (!Yii::$app->user->can('author') && $model->post_status === $model::POST_STATUS_REVIEW) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        $terms = $model->terms;

        if ($model->delete()) {
            foreach ($terms as $term) {
                $term->updateAttributes(['term_count' => $term->term_count--]);
            }
        }

        return $this->redirect(['index', 'post_type' => $model->post_type]);
    }

    /**
     * Bulk action for Post triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === Post::POST_STATUS_PUBLISH) {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['post_status' => Post::POST_STATUS_PUBLISH]);
            }
        } elseif (Yii::$app->request->post('action') === Post::POST_STATUS_DRAFT) {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['post_status' => Post::POST_STATUS_DRAFT]);
            }
        } elseif (Yii::$app->request->post('action') === Post::POST_STATUS_PRIVATE) {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['post_status' => Post::POST_STATUS_PRIVATE]);
            }
        } elseif (Yii::$app->request->post('action') === Post::POST_STATUS_TRASH) {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['post_status' => Post::POST_STATUS_TRASH]);
            }
        } elseif (Yii::$app->request->post('action') === Post::POST_STATUS_REVIEW) {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->updateAttributes(['post_status' => Post::POST_STATUS_REVIEW]);
            }
        } elseif (Yii::$app->request->post('action') === 'delete') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $model = $this->findModel($id);
                $terms = $model->terms;
                if ($model->delete()) {
                    foreach ($terms as $term) {
                        $term->updateAttributes(['term_count' => $term->term_count--]);
                    }
                }
            }
        }
    }

    /**
     * Search POST model via AJAX with JSON as the response.
     */
    public function actionAjaxSearch()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Post::find()
            ->select(['id', 'post_title'])
            ->andWhere(['LIKE', 'post_title', Yii::$app->request->post('post_title')])
            ->limit(10);

        if ($postType = Yii::$app->request->post('post_type')) {
            $query->andWhere(['post_type' => $postType]);
        }

        return $query->all();
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the PostType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return PostType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findPostType($id)
    {
        if (($model = PostType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Term model based on its primary key value.
     * If the model is not found, it return false.
     *
     * @param integer $id
     *
     * @return Term|bool|null|static
     */
    protected function findTerm($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        }

        return false;
    }
}
