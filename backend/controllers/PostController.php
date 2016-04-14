<?php
/**
 * @link http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
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
 * @author Agiel K. Saputra <13nightevil@gmail.com>
 * @since 0.1.0
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
                        'allow' => true,
                        'roles' => ['subscriber'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * @param integer $type
     * @param null|string $user
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionIndex($type, $user = null)
    {
        $postType = $this->findPostType($type);
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $type, $user);

        if (!Yii::$app->user->can($postType->permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'postType' => $postType,
            'user' => $user,
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'update' page.
     *
     * @param integer $type Post type ID
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionCreate($type)
    {
        $model = new Post();
        $postType = $this->findPostType($type);
        $model->comment_status = Option::get('default_comment_status');

        if (!Yii::$app->user->can($postType->permission)) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->type = $postType->id;
            $model->date = date('Y-m-d H:i:s', strtotime($model->date));
            if ($model->save()) {
                if ($termIds = Yii::$app->request->post('termIds')) {
                    foreach ($termIds as $termId) {
                        $termRelationship = new TermRelationship();
                        $termRelationship->setAttributes([
                            'term_id' => $termId,
                            'post_id' => $model->id,
                        ]);
                        if ($termRelationship->save() && $term = $this->findTerm($termId)) {
                            $term->updateAttributes(['count' => ++$term->count]);
                        }
                    }
                }
                if ($meta = Yii::$app->request->post('meta')) {
                    foreach ($meta as $name => $value) {
                        $model->setMeta($name, $value);
                    }
                }
                Yii::$app->getSession()->setFlash('success',
                    Yii::t('writesdown', '{type} successfully saved.', ['type' => $postType->singular_name, ]));
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'postType' => $postType,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $this->getPermission($model);
        $postType = $model->postType;

        if ($model->load(Yii::$app->request->post())) {
            $model->date = date('Y-m-d H:i:s', strtotime($model->date));
            if ($model->save()) {
                if ($meta = Yii::$app->request->post('meta')) {
                    foreach ($meta as $name => $value) {
                        $model->setMeta($name, $value);
                    }
                }
                Yii::$app->getSession()->setFlash('success',
                    Yii::t('writesdown', '{type} successfully saved.', ['type' => $postType->singular_name, ]));
                return $this->redirect(['post/update', 'id' => $id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'postType' => $postType,
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @throws \Exception
     * @throws \yii\web\ForbiddenHttpException
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->getPermission($model);
        $terms = $model->terms;

        if ($model->delete()) {
            foreach ($terms as $term) {
                $term->updateAttributes(['count' => --$term->count]);
            }
        }

        return $this->redirect(['index', 'type' => $model->type]);
    }

    /**
     * Bulk action for Post triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') === Post::STATUS_PUBLISH) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $model->updateAttributes(['status' => Post::STATUS_PUBLISH]);
            }
        } elseif (Yii::$app->request->post('action') === Post::STATUS_DRAFT) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $model->updateAttributes(['status' => Post::STATUS_DRAFT]);
            }
        } elseif (Yii::$app->request->post('action') === Post::STATUS_PRIVATE) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $model->updateAttributes(['status' => Post::STATUS_PRIVATE]);
            }
        } elseif (Yii::$app->request->post('action') === Post::STATUS_TRASH) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $model->updateAttributes(['status' => Post::STATUS_TRASH]);
            }
        } elseif (Yii::$app->request->post('action') === Post::STATUS_REVIEW) {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $model->updateAttributes(['status' => Post::STATUS_REVIEW]);
            }
        } elseif (Yii::$app->request->post('action') === 'delete') {
            foreach (Yii::$app->request->post('ids', []) as $id) {
                $model = $this->findModel($id);
                $this->getPermission($model);
                $terms = $model->terms;
                if ($model->delete()) {
                    foreach ($terms as $term) {
                        $term->updateAttributes(['count' => --$term->count]);
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
            ->select(['id', 'title'])
            ->andWhere(['like', 'title', Yii::$app->request->post('title')])
            ->limit(10);

        if ($postType = Yii::$app->request->post('type')) {
            $query->andWhere(['type' => $postType]);
        }

        return $query->all();
    }

    /**
     * Get permission to access model by current user.
     * If the user does not obtain the permission, a 403 exeption will be thrown.
     *
     * @param $model Post
     * @throws ForbiddenHttpException
     */
    public function getPermission($model)
    {
        if (!$model->getPermission()) {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
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
