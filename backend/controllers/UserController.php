<?php
/**
 * @file    UserControl.php.
 * @date    6/4/2015
 * @time    5:13 AM
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/* MODEL */
use common\models\User;
use common\models\search\User as UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'bulk-action'],
                        'allow'   => true,
                        'roles'   => ['administrator']
                    ],
                    [
                        'actions' => ['profile', 'view', 'reset-password'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'      => ['post'],
                    'bulk-action' => ['post'],
                ],
            ],
        ];
    }


    /**
     * Lists all User models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        // Set scenario to register, so that the password is required
        $model = new User(['scenario' => 'register']);
        if ($model->load(Yii::$app->request->post())) {
            $model->generateAuthKey();
            $model->setPassword($model->password);
            if ($model->save()) {
                Yii::$app->authManager->assign(Yii::$app->authManager->getRole($model->role), $model->id);

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
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
        if (Yii::$app->user->can('superadmin') || (Yii::$app->user->can('administrator') && !Yii::$app->authManager->checkAccess($model->id, 'administrator'))) {
            if ($model->load(Yii::$app->request->post())) {
                if($model->save()){
                    Yii::$app->authManager->revokeAll($id);
                    Yii::$app->authManager->assign(Yii::$app->authManager->getRole( $model->role ), $id);
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }

            return $this->render('update', [
                'model' => $model,
            ]);
        } else {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }
    }

    /**
     * Deletes an existing User model.
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

        if (Yii::$app->user->can('superadmin') || (Yii::$app->user->can('administrator') && !Yii::$app->authManager->checkAccess($model->id, 'administrator'))) {
            $model->delete();

            return $this->redirect(['index']);
        } else {
            throw new ForbiddenHttpException(Yii::t('writesdown', 'You are not allowed to perform this action.'));
        }

    }

    /**
     * Bulk-action for user in index page
     *
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionBulkAction()
    {
        if (($action = Yii::$app->request->post('action')) && ($ids = Yii::$app->request->post('ids'))) {
            if ($action === 'activated') {
                foreach ($ids as $id) {
                    $this->findModel($id)->updateAttributes(['status' => 10]);
                }
            } else if ($action === 'unactivated') {
                foreach ($ids as $id) {
                    $this->findModel($id)->updateAttributes(['status' => 5]);
                }
            } else if ($action === 'removed') {
                foreach ($ids as $id) {
                    $this->findModel($id)->updateAttributes(['status' => 0]);
                }
            } else if ($action === 'deleted') {
                foreach ($ids as $id) {
                    $this->findModel($id)->delete();
                }
            } else if ($action === 'changerole') {
                foreach ($ids as $id) {
                    Yii::$app->authManager->revokeAll($id);
                    Yii::$app->authManager->assign(Yii::$app->authManager->getRole(Yii::$app->request->post('role')), $id);
                }
            }
        }
    }


    /**
     * Update the user data for the user who has logged in.
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProfile()
    {
        $model = $this->findModel(Yii::$app->user->id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('profile', [
            'model' => $model,
        ]);
    }

    /**
     * Reset password for logged user.
     * It requires three use input for resetting password, they are the old password, new password, and repeat password.
     * The old password will be checked by User::passwordValidation.
     *
     * @return string|\yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionResetPassword()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $model->setScenario('resetPassword');
        if ($model->load(Yii::$app->request->post())) {
            $model->setPassword($model->password);
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            };
        }

        return $this->render('reset-password', [
            'model' => $model
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}