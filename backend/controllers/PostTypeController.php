<?php
/**
 * @file      PostTypeControl.php.
 * @date      6/4/2015
 * @time      5:14 AM
 * @author    Agiel K. Saputra <13nightevil@gmail.com>
 * @copyright Copyright (c) 2015 WritesDown
 * @license   http://www.writesdown.com/license/
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use common\components\Json;

/* MODEL */
use common\models\PostType;
use common\models\Taxonomy;
use common\models\PostTypeTaxonomy;
use common\models\search\PostType as PostTypeSearch;

/**
 * PostTypeController implements the CRUD actions for PostType model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
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
                        'roles'   => ['administrator']
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
            if (($postTypeTaxonomy = Yii::$app->request->post('PostTypeTaxonomy')) && ($taxonomyIds = Json::decode($postTypeTaxonomy['taxonomy_ids']))) {
                foreach ($taxonomyIds as $taxonomy_id) {
                    $postTypeTaxonomy = new PostTypeTaxonomy();
                    $postTypeTaxonomy->post_type_id = $model->id;
                    $postTypeTaxonomy->taxonomy_id = $taxonomy_id;
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
            // Delete all PostTypeTaxonomy where post_type_id this id
            PostTypeTaxonomy::deleteAll(['post_type_id' => $id]);
            // Refill PostTypeTaxonomy for this model
            if (($postTypeTaxonomy = Yii::$app->request->post('PostTypeTaxonomy')) && ($taxonomyIds = Json::decode($postTypeTaxonomy['taxonomy_ids']))) {
                foreach ($taxonomyIds as $taxonomy_id) {
                    $postTypeTaxonomy = new PostTypeTaxonomy();
                    $postTypeTaxonomy->post_type_id = $model->id;
                    $postTypeTaxonomy->taxonomy_id = $taxonomy_id;
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
     * Bulk action for Taxonomy
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}