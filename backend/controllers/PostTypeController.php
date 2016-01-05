<?php
/**
 * @link      http://www.writesdown.com/
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use common\components\Json;
use common\models\PostType;
use common\models\PostTypeTaxonomy;
use common\models\search\PostType as PostTypeSearch;
use common\models\Taxonomy;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PostTypeController implements the CRUD actions for PostType model.
 *
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   0.1.0
 */
class PostTypeController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'bulk-action'],
                        'allow'   => true,
                        'roles'   => ['administrator'],
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
     * Lists all PostType models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PostType model.
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
     * Creates a new PostType and PostTypeTaxonomy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PostType();
        $taxonomies = ArrayHelper::map(Taxonomy::find()->all(), 'id', 'taxonomy_name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $postTypeTaxonomy = Yii::$app->request->post('PostTypeTaxonomy');
            if ($taxonomyIds = Json::decode($postTypeTaxonomy['taxonomyIds'])) {
                foreach ($taxonomyIds as $taxonomyId) {
                    $postTypeTaxonomy = new PostTypeTaxonomy();
                    $postTypeTaxonomy->setAttributes([
                        'post_type_id' => $model->id,
                        'taxonomy_id'  => $taxonomyId,
                    ]);
                    $postTypeTaxonomy->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model'      => $model,
            'taxonomy'   => new Taxonomy(),
            'taxonomies' => $taxonomies,
        ]);
    }

    /**
     * Updates an existing PostType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $taxonomies = ArrayHelper::map(Taxonomy::find()->all(), 'id', 'taxonomy_name');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            PostTypeTaxonomy::deleteAll(['post_type_id' => $id]);
            $postTypeTaxonomy = Yii::$app->request->post('PostTypeTaxonomy');
            if ($taxonomyIds = Json::decode($postTypeTaxonomy['taxonomyIds'])) {
                foreach ($taxonomyIds as $taxonomyId) {
                    $postTypeTaxonomy = new PostTypeTaxonomy();
                    $postTypeTaxonomy->setAttributes([
                        'post_type_id' => $model->id,
                        'taxonomy_id'  => $taxonomyId,
                    ]);
                    $postTypeTaxonomy->save();
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model'      => $model,
            'taxonomy'   => new Taxonomy(),
            'taxonomies' => $taxonomies,
        ]);
    }

    /**
     * Deletes an existing PostType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Bulk action for PostType triggered when button 'Apply' clicked.
     * The action depends on the value of the dropdown next to the button.
     * Only accept POST HTTP method.
     */
    public function actionBulkAction()
    {
        if (Yii::$app->request->post('action') == 'delete') {
            foreach (Yii::$app->request->post('ids') as $id) {
                $this->findModel($id)->delete();
            }
        }
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
    protected function findModel($id)
    {
        if (($model = PostType::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('writesdown', 'The requested page does not exist.'));
    }
}
