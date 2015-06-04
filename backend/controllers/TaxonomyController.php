<?php
/**
 * @file      TaxonomyController.php.
 * @date      6/4/2015
 * @time      5:10 AM
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
use yii\helpers\Html;

/* MODEL */
use common\models\Taxonomy;
use common\models\search\Taxonomy as TaxonomySearch;
use common\models\Term;
use common\models\search\Term as TermSearch;

/**
 * TaxonomyController implements the CRUD actions for Taxonomy model.
 *
 * @package backend\controllers
 * @author  Agiel K. Saputra <13nightevil@gmail.com>
 * @since   1.0
 */
class TaxonomyController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'delete', 'bulk-action', 'ajax-create'],
                        'allow'   => true,
                        'roles'   => ['administrator']
                    ],
                    [
                        'actions' => ['view', 'update-term', 'delete-term'],
                        'allow'   => true,
                        'roles'   => ['editor']
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete'      => ['post'],
                    'bulk-action' => ['post'],
                    'ajax-create' => ['post'],
                    'delete-term' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Taxonomy models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TaxonomySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * View single taxonomy and list all term from it.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $term = new Term();
        $searchModel = new TermSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        if ($term->load(Yii::$app->request->post())) {
            $term->taxonomy_id = $id;
            if ($term->save()) {
                return $this->redirect(['/taxonomy/view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model'        => $this->findModel($id),
            'term'         => $term,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Taxonomy model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Taxonomy();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Taxonomy model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Taxonomy model.
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
     * Update a term on page of view taxonomy.
     *
     * @param $id
     * @param $term_id
     *
     * @throws NotFoundHttpException
     * @return string|\yii\web\Response
     * @see actionView
     */
    public function actionUpdateTerm($id, $term_id)
    {

        $term = $this->findTerm($term_id);
        $searchModel = new TermSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $id);

        if ($term->load(Yii::$app->request->post())) {
            $term->taxonomy_id = $id;
            if ($term->save()) {
                return $this->redirect(['/taxonomy/view', 'id' => $id]);
            }
        }

        return $this->render('view', [
            'model'        => $this->findModel($id),
            'term'         => $term,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Delete an existing term of a taxonomy on 'view taxonomy' page.
     * If deletion is successful, the browser will be redirected to the 'view taxonomy' page.
     *
     * @param integer $id
     * @param integer $term_id
     *
     * @throws \Exception
     * @throws \yii\web\NotFoundHttpException
     * @return mixed
     */
    public function actionDeleteTerm($id, $term_id)
    {
        $this->findTerm($term_id)->delete();

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * Create taxonomy via ajax on post type create or update page
     */
    public function actionAjaxCreate()
    {
        $model = new Taxonomy();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            echo '<br />' . Html::label(Html::checkbox('taxonomy_ids[]', true, ['value' => $model->id]) . ' ' . $model->taxonomy_name);
        }
    }

    /**
     * Finds the Taxonomy model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Taxonomy the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Taxonomy::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Term model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Term the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findTerm($id)
    {
        if (($model = Term::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}